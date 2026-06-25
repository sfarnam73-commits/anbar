export default {
  async fetch(request, env) {
    if (request.method === "OPTIONS") {
      return new Response(null, {
        headers: {
          "Access-Control-Allow-Origin": "*",
          "Access-Control-Allow-Methods": "POST, OPTIONS",
          "Access-Control-Allow-Headers": "Content-Type",
        },
      });
    }

    if (request.method !== "POST") {
      return Response.json({ error: "POST only" }, { status: 405 });
    }

    let body;
    try {
      const rawText = await request.text();
      body = JSON.parse(rawText);
      // handle double-encoded JSON from n8n
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
    };

    const response = await fetch("https://api.anthropic.com/v1/messages", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "x-api-key": apiKey,
        "anthropic-version": "2023-06-01",
      },
      body: JSON.stringify(anthropicBody),
    });

    const data = await response.json();

    return Response.json(data, {
      headers: {
        "Access-Control-Allow-Origin": "*",
      },
    });
  },
};
