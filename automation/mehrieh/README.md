# Cheraghi Mehrieh Automation — Real Rank Math v9.0.0

این نسخه برای مقالات مهریه `006` تا `030` و فقط هاب **مقالات** طراحی شده است.

## اصل غیرقابل‌مذاکره

- هیچ `internal SEO score`، `uniqueness score` یا `Rank Math readiness score` ساخته یا برای انتشار استفاده نمی‌شود.
- Bridge هیچ عددی را به جای Rank Math محاسبه نمی‌کند.
- تنها عددی که با نام SEO score گزارش می‌شود `rank_math_seo_score` است؛ آن هم فقط اگر خود Rank Math آن را ذخیره کرده باشد.
- پس از API write، اگر امتیاز قدیمی وجود داشته باشد حذف نمی‌شود، اما تا محاسبه دوباره Rank Math با وضعیت `stale_after_api_write` گزارش می‌شود.

## ایمنی انتشار

- Manual Trigger یک **Dry Run واقعی** است: فقط Status + انتخاب مقاله + Preflight محلی اجرا می‌شود و هیچ Import/Publish در وردپرس انجام نمی‌دهد.
- Schedule روزانه ساعت `10:00` با timezone `Asia/Tehran` مسیر انتشار را اجرا می‌کند.
- Bridge علاوه بر Workflow، در سمت وردپرس هم حد `1` انتشار جدید در هر روز محلی را با قفل اتمیک enforce می‌کند.
- نوشته `Published` به‌صورت پیش‌فرض و حتی با درخواست sync توسط این نسخه بازنویسی نمی‌شود.
- نوشته قدیمی Published با slug دقیق پروژه در صف «منتشرشده» شناخته می‌شود تا Duplicate ساخته نشود.
- مقصد Draft/Publish جدید فقط `post` + `_cheraghi_content_hub=articles` + دسته‌های `legal-articles` و `family-mahrieh` است.

## سیاست محتوا و Rank Math

- 25 مقاله داخل خود Workflow قرار دارند؛ نیازی به فایل خارجی یا GitHub در زمان اجرای n8n نیست.
- paddingهای قدیمی `CHERAGHI-SEO-AUTO` و `CHERAGHI-RANKMATH-EDITORIAL` حذف شده‌اند.
- Bridge 3.7 متن مقاله را برای «بالا بردن نمره» بازنویسی نمی‌کند؛ محتوای واردشده باید byte-for-byte همان محتوای بسته باشد (به‌جز رفتار عادی WordPress در محیط واقعی).
- Primary Focus Keyword هر مقاله در SEO title/meta/content قرار دارد و عبارت دقیق `دکتر فرزانه چراغی، وکیل همدان` به‌عنوان Focus Keyword ثانویه Rank Math ذخیره می‌شود.
- TOC، لینک داخلی و حداقل یک منبع خارجی Follow در HTML هر مقاله وجود دارد.
- Slugهای انگلیسی قبلی برای جلوگیری از شکستن URLها حفظ شده‌اند؛ بنابراین تست «Focus Keyword in URL» در Rank Math ممکن است پاس نشود.
- تصویر مصنوعی یا وابسته به مسیر یک قالب خاص اضافه نشده است؛ Media test Rank Math ممکن است تا زمان افزودن تصویر واقعی امتیاز کامل نگیرد.

## فایل‌های اجرایی

- `CHERAGHILAW-MEHRIEH-ARTICLES-ONLY-REAL-RANKMATH-v9.0.0.json`
- `../../wordpress-plugin/cheraghi-content-bridge-v3/cheraghi-content-bridge-v3.php` — Bridge `3.7.0`
- `tests/validate.py`
- `tests/workflow_logic.js`
- `tests/bridge_harness.php`
- `TEST-REPORT.md`

## نکته n8n

فایل Export شامل Credential محرمانه نیست. بعد از Import باید همان **HTTP Basic Auth Credential وردپرس** را روی چهار HTTP node انتخاب کنید: Status، Import، Verify و Publish. هیچ username/password داخل GitHub ذخیره نشده است.

## محدودیت تست

این شاخه با تست‌های استاتیک، اجرای JavaScript و یک WordPress-like PHP harness تست شده است. تا زمانی که Workflow با Credential واقعی روی `cheraghilaw.ir` اجرا نشود، تست Production و عدد نهایی Rank Math قابل ادعا نیست.
