# Cheraghi Mehrieh Automation — Real Rank Math v9

این شاخه نسخه‌ی بازطراحی‌شده‌ی اتوماسیون مقالات مهریه 006 تا 030 است.

## اصل اصلی

- هیچ `internal SEO score`، `uniqueness score` یا `Rank Math readiness score` برای تصمیم انتشار ساخته نمی‌شود.
- Bridge هیچ نمره‌ای را به جای Rank Math جعل یا محاسبه نمی‌کند.
- اگر `rank_math_seo_score` واقعاً توسط Rank Math ذخیره شده باشد، فقط همان عدد گزارش می‌شود؛ در غیر این صورت مقدار `null` برمی‌گردد.
- انتشار به یک امتیاز تقلیدی وابسته نشده است.

## تغییرات ایمنی

- Manual Trigger فقط Import + Verify را تست می‌کند و منتشر نمی‌کند.
- Schedule روزانه ساعت 10 تهران اجازه Publish دارد.
- Bridge حداکثر یک مقاله جدید پروژه را در هر روز محلی وردپرس منتشر می‌کند.
- محتوای Published به صورت پیش‌فرض بازنویسی نمی‌شود.
- Sync محتوای Published فقط با `sync_existing_published=true` ممکن است؛ Workflow این مقدار را `false` می‌فرستد.
- مقاله قدیمی با slug دقیق و بدون `_cheraghi_article_id` به عنوان legacy شناسایی می‌شود تا Duplicate ساخته نشود؛ محتوا نیز محافظت می‌شود.
- مقصد فقط `post` + hub `articles` + دسته‌های `legal-articles` و `family-mahrieh` است.

## سیاست محتوا

- بلوک‌های عمومی SEO-padding قبلی از 25 مقاله حذف شده‌اند.
- عبارت «دکتر فرزانه چراغی، وکیل همدان» در هر 25 مقاله وجود دارد.
- لینک‌های داخلی واقعی در هر مقاله وجود دارند.
- TOC Rank Math در هر مقاله حفظ شده است.
- تصاویر وابسته به مسیر قالب قدیمی از محتوای خودکار حذف شده‌اند؛ نبود تصویر باعث توقف انتشار نمی‌شود و نمره واقعی Rank Math ممکن است به همین دلیل چند امتیاز کمتر باشد.

## فایل‌ها

- `automation/mehrieh/CHERAGHILAW-MEHRIEH-ARTICLES-ONLY-REAL-RANKMATH-v9.0.0.json`
- `wordpress-plugin/cheraghi-content-bridge-v3/cheraghi-content-bridge-v3.php` (v3.7.0)
- `automation/mehrieh/TEST-REPORT.md`
