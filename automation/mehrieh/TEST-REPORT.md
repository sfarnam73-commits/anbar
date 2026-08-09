# Test Report — Workflow v9.0.0 / Bridge 3.7.0

تاریخ بازبینی: 2026-08-09

## 1) Workflow

- JSON parse: **PASS**
- Nodes: **22**
- Code nodes: **13**
- تمام connection target/sourceها موجود: **PASS**
- JavaScript syntax تمام Code nodeها با `node --check`: **PASS**
- Cron: `0 10 * * *`: **PASS**
- Timezone: `Asia/Tehran`: **PASS**
- Manual mode = `dry_run`: **PASS**
- Schedule mode = `publish`: **PASS**
- direct `/wp-json/wp/v2/posts` publish: **ABSENT**
- score thresholds/gates قدیمی: **ABSENT**

## 2) Article pack embedded in Workflow

- IDs دقیق `mehrieh-006` تا `mehrieh-030`: **25/25 PASS**
- Primary Focus Keyword یکتا: **25/25 PASS**
- عبارت `دکتر فرزانه چراغی، وکیل همدان`: **25/25 PASS**
- Rank Math focus meta آماده‌شده به صورت `primary,brand`: **25/25 PASS**
- synthetic score fields در payload: **0**
- `CHERAGHI-SEO-AUTO`: **0**
- `CHERAGHI-RANKMATH-EDITORIAL`: **0**
- dependency به `cheraghi-law-premium/assets/images`: **0**
- Word count range: **1685 تا 2411**
- exact primary focus density range: **0.81% تا 1.99%**
- SEO title starts primary focus: **25/25 PASS**
- SEO title length <= 65 chars: **25/25 PASS**
- Meta length 115..158 chars: **25/25 PASS**
- Rank Math TOC marker: **25/25 PASS**
- حداقل 2 internal HTML link: **25/25 PASS**
- حداقل 1 followed external source: **25/25 PASS**
- FAQ visible text ↔ FAQ payload: **25/25 PASS**
- Article schema wordCount/description synced: **25/25 PASS**

## 3) Workflow logic execution

با اجرای واقعی JavaScript nodeها در test harness:

- Bridge 3.7 valid contract: **PASS**
- contract با `synthetic_score_gate=true` رد می‌شود: **PASS**
- انتخاب اولین unpublished از source-of-truth وردپرس: **PASS**
- Preflight تمام 25 مقاله: **25/25 PASS**
- Manual Dry Run حتی اگر امروز مقاله منتشر شده باشد فقط مقاله بعدی را بررسی می‌کند: **PASS**
- Schedule با `published_today=true` انتشار را متوقف می‌کند: **PASS**
- Queue after 24 published → article 030: **PASS**
- Queue after 25 published → completed: **PASS**
- Import evaluator بدون score: **PASS**
- Verify evaluator بدون readiness score: **PASS**
- Publish evaluator با `rank_math_seo_score=null`: **PASS**؛ هیچ عدد جایگزین ساخته نمی‌شود.
- protected Published path terminal: **PASS**

## 4) Bridge 3.7 PHP

- `php -l`: **PASS**
- Article slug map ↔ 25 مقاله embedded: **25/25 exact PASS**
- `rank_math_readiness_audit`: **ABSENT**
- `prepare_article_for_blog`: **ABSENT**
- auto focus-summary/media padding builders: **ABSENT**
- write به `rank_math_seo_score`: **ABSENT**
- automatic migration/repair of Published posts on activation: **ABSENT**

## 5) WordPress-like execution harness روی خود Bridge

- Status reports `synthetic_score_gate=false`: **PASS**
- Status reports `rank_math_actual_score_only=true`: **PASS**
- First article Import → Draft: **PASS**
- Bridge content mutation after Import: **NONE in harness**
- Rank Math score fabricated after Import: **NO**
- Primary + `دکتر فرزانه چراغی، وکیل همدان` stored in Rank Math focus meta: **PASS**
- Verify Draft: **PASS**
- First Draft Publish: **PASS**
- Retry same Published post: **idempotent PASS**
- Second new article same local day: **BLOCKED PASS**
- fresh concurrent daily lock: **BLOCKED PASS**
- stale 15-minute reservation with no published article: **SELF-RECOVERY PASS**
- real Rank Math write to `rank_math_seo_score=91`: reported as fresh **PASS**
- existing Rank Math score survives API update but becomes `stale_after_api_write`: **PASS**
- legacy Published exact slug detected in queue without article meta: **PASS**
- legacy Published Import: content/title unchanged **PASS**
- explicit sync attempt on Published: rejected, content unchanged **PASS**
- clean-state Import + Verify for entire pack: **25/25 PASS**
- clean-state content equality after Bridge Import: **25/25 PASS**

## 6) مواردی که عمداً ادعا نمی‌شوند

- **Production n8n run:** انجام نشده؛ Credential واقعی n8n/WordPress در این محیط وجود ندارد.
- **Final Rank Math score:** ادعا نمی‌شود. فقط Rank Math نصب‌شده روی وردپرس می‌تواند عدد نهایی معتبر را پس از تحلیل/ذخیره محاسبه کند.
- **Focus Keyword in URL:** slugهای انگلیسی قبلی حفظ شده‌اند، بنابراین این تست Rank Math ممکن است Fail بماند.
- **Media score:** تصویر غیرواقعی برای گرفتن نمره اضافه نشده است؛ امتیاز Media ممکن است کامل نباشد.

## Verdict

نسخه v9.0.0/3.7.0 از نظر تست‌های قابل اجرای خارج از Production، معماری «بدون امتیاز ساختگی + Published protection + یک انتشار روزانه + ARTICLES ONLY» را پاس می‌کند. Merge نهایی باید بعد از یک Dry Run واقعی و سپس یک اجرای Scheduled کنترل‌شده روی محیط واقعی انجام شود.
