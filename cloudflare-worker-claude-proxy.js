export default {
  async fetch(request, env) {
    if (request.method === "OPTIONS") {
      return new Response(null, {
        headers: {
          "Access-Control-Allow-Origin": "*",
          "Access-Control-Allow-Methods": "GET, POST, OPTIONS",
          "Access-Control-Allow-Headers": "Content-Type, Authorization",
        },
      });
    }

    const url = new URL(request.url);

    // --- Pexels API Proxy ---
    if (url.pathname === "/pexels") {
      if (request.method !== "GET") {
        return Response.json({ error: "GET only for /pexels" }, {
          status: 405,
          headers: { "Access-Control-Allow-Origin": "*" },
        });
      }

      const query = url.searchParams.get("query");
      const perPage = url.searchParams.get("per_page") || "15";
      const apiKey = url.searchParams.get("api_key") || request.headers.get("Authorization");

      if (!query) {
        return Response.json({ error: "query parameter required" }, {
          status: 400,
          headers: { "Access-Control-Allow-Origin": "*" },
        });
      }

      if (!apiKey) {
        return Response.json({ error: "api_key parameter or Authorization header required" }, {
          status: 400,
          headers: { "Access-Control-Allow-Origin": "*" },
        });
      }

      let pexelsResponse;
      try {
        pexelsResponse = await fetch(
          `https://api.pexels.com/v1/search?query=${encodeURIComponent(query)}&per_page=${perPage}`,
          {
            headers: {
              "Authorization": apiKey,
            },
          }
        );
      } catch (e) {
        return Response.json(
          { error: "Failed to connect to Pexels API: " + e.message },
          { status: 502, headers: { "Access-Control-Allow-Origin": "*" } },
        );
      }

      const pexelsData = await pexelsResponse.text();
      return new Response(pexelsData, {
        status: pexelsResponse.status,
        headers: {
          "Content-Type": "application/json",
          "Access-Control-Allow-Origin": "*",
        },
      });
    }

    // --- Pexels Image Download Proxy ---
    if (url.pathname === "/pexels-image") {
      const imageUrl = url.searchParams.get("url");
      if (!imageUrl || !imageUrl.includes("pexels.com")) {
        return Response.json({ error: "valid pexels url required" }, {
          status: 400,
          headers: { "Access-Control-Allow-Origin": "*" },
        });
      }

      let imgResponse;
      try {
        imgResponse = await fetch(imageUrl);
      } catch (e) {
        return Response.json(
          { error: "Failed to download image: " + e.message },
          { status: 502, headers: { "Access-Control-Allow-Origin": "*" } },
        );
      }

      return new Response(imgResponse.body, {
        status: imgResponse.status,
        headers: {
          "Content-Type": imgResponse.headers.get("Content-Type") || "image/jpeg",
          "Access-Control-Allow-Origin": "*",
        },
      });
    }

    // --- Claude API Proxy (existing) ---
    if (request.method !== "POST") {
      return Response.json({ error: "POST only" }, { status: 405 });
    }

    let body;
    try {
      const rawText = await request.text();
      body = JSON.parse(rawText);
      if (typeof body === "string") {
        body = JSON.parse(body);
      }
    } catch (e) {
      return Response.json(
        { error: "Cannot parse body: " + e.message },
        { status: 400 },
      );
    }

    const apiKey = body.api_key;
    if (!apiKey) {
      return Response.json(
        { error: "api_key required", received_keys: Object.keys(body) },
        { status: 400 },
      );
    }

    const anthropicBody = {
      model: body.model || "claude-sonnet-4-6",
      max_tokens: body.max_tokens || 8000,
      messages: body.messages,
      stream: true,
    };

    let response;
    try {
      response = await fetch("https://api.anthropic.com/v1/messages", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "x-api-key": apiKey,
          "anthropic-version": "2023-06-01",
        },
        body: JSON.stringify(anthropicBody),
      });
    } catch (e) {
      return Response.json(
        { error: "Failed to connect to Anthropic API: " + e.message },
        { status: 502, headers: { "Access-Control-Allow-Origin": "*" } },
      );
    }

    if (!response.ok) {
      const errorText = await response.text();
      let errorData;
      try {
        errorData = JSON.parse(errorText);
      } catch {
        errorData = { raw: errorText };
      }
      return Response.json(errorData, {
        status: response.status,
        headers: { "Access-Control-Allow-Origin": "*" },
      });
    }

    const reader = response.body.getReader();
    const decoder = new TextDecoder();
    let fullText = "";
    let model = anthropicBody.model;
    let stopReason = "";
    let inputTokens = 0;
    let outputTokens = 0;
    let buffer = "";

    try {
      while (true) {
        const { done, value } = await reader.read();
        if (done) break;

        buffer += decoder.decode(value, { stream: true });
        const lines = buffer.split("\n");
        buffer = lines.pop();

        for (const line of lines) {
          if (!line.startsWith("data: ")) continue;
          const data = line.slice(6).trim();
          if (data === "[DONE]") continue;

          let event;
          try {
            event = JSON.parse(data);
          } catch {
            continue;
          }

          if (event.type === "message_start" && event.message) {
            model = event.message.model || model;
            if (event.message.usage) {
              inputTokens = event.message.usage.input_tokens || 0;
            }
          } else if (
            event.type === "content_block_delta" &&
            event.delta?.text
          ) {
            fullText += event.delta.text;
          } else if (event.type === "message_delta") {
            stopReason = event.delta?.stop_reason || stopReason;
            if (event.usage) {
              outputTokens = event.usage.output_tokens || 0;
            }
          } else if (event.type === "error") {
            return Response.json(
              { error: event.error },
              {
                status: 500,
                headers: { "Access-Control-Allow-Origin": "*" },
              },
            );
          }
        }
      }
    } catch (e) {
      if (fullText.length > 0) {
        const result = {
          content: [{ type: "text", text: fullText }],
          model: model,
          stop_reason: "partial",
          usage: { input_tokens: inputTokens, output_tokens: outputTokens },
        };
        return Response.json(result, {
          headers: { "Access-Control-Allow-Origin": "*" },
        });
      }
      return Response.json(
        { error: "Stream reading failed: " + e.message },
        { status: 500, headers: { "Access-Control-Allow-Origin": "*" } },
      );
    }

    const result = {
      content: [{ type: "text", text: fullText }],
      model: model,
      stop_reason: stopReason,
      usage: { input_tokens: inputTokens, output_tokens: outputTokens },
    };

    return Response.json(result, {
      headers: { "Access-Control-Allow-Origin": "*" },
    });
  },
};
