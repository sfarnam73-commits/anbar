# Test Report — v9.0.0 / Bridge 3.7.0

## Static

- Workflow JSON parse: PASS
- n8n node references/connections: PASS
- JavaScript syntax for all 10 Code nodes (`node --check`): PASS
- PHP syntax (`php -l`): PASS
- Malformed n8n expression scan: PASS

## Article pack

- IDs 006..030 exactly: 25/25 PASS
- Unique primary focus keyword: 25/25 PASS
- Synthetic score fields absent: 25/25 PASS
- Generic `CHERAGHI-RANKMATH-EDITORIAL` padding absent: 25/25 PASS
- Old `CHERAGHI-SEO-AUTO` padding absent: 25/25 PASS
- Old theme image path dependency absent: 25/25 PASS
- Exact phrase `دکتر فرزانه چراغی، وکیل همدان`: 25/25 PASS
- Rank Math TOC marker: 25/25 PASS
- Internal links: minimum 2/article PASS
- External source links: minimum 3/article PASS
- Visible FAQ covers package FAQ items: PASS
- Article schema wordCount synchronized with current content: PASS
- Word count range after removing padding: 1584..2145

## Executed logic tests

- Article Preflight JS against every article: 25/25 PASS
- Bridge status gate with Bridge 3.7 contract: PASS
- Verify evaluator with Draft response: PASS
- Verify evaluator with existing Published response: PASS
- Publish result evaluator with no Rank Math score available: PASS (`PENDING_RANK_MATH_RECALCULATION`)

## Executed PHP harness

A WordPress-like stub harness called the real Bridge methods.

- First Draft publish: PASS
- Synthetic score gate reported false: PASS
- Rank Math score remains null when Rank Math has not actually calculated it: PASS
- Second new article on same local day blocked by `cheraghi_daily_publish_lock`: PASS
- Retry of the already-published same post is idempotent success: PASS
- Legacy published post found by exact slug with no article-id: PASS
- Legacy published title/content remains unchanged: PASS
- Project article-id is attached so a duplicate is not created on the next queue run: PASS

## Not claimed

This is not a live production test against the user's n8n credentials and WordPress database. The final Rank Math number can only be authoritative after Rank Math itself analyzes the post on the live site.
