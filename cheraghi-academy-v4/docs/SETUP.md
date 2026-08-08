# راه‌اندازی V4

## 1) Project در ChatGPT
فایل `project/PROJECT_INSTRUCTIONS.md` را در Project Instructions پروژه «مقالات آکادمی سایت چراغی» قرار بده.

## 2) Skills
اگر Skills در حساب فعال است، پوشه‌های زیر را نصب/آپلود کن:
- `skills/CHERAGHI-ACADEMY-MASTER`
- `skills/SOURCE-GUARD`
- `skills/LEGAL-ANALYST`
- `skills/SEO-QC`

اگر Personal Skills در پلن/سطح حساب در دسترس نیست، محتوای Skillها را در Project Instructions و Work/Codex استفاده کن.

## 3) منابع
در Project یا Google Drive:
- PDF رسمی دفترچه C سال ۱۳۹۸
- کلید رسمی
- اصلاحیه‌های رسمی
- قوانین و منابع قابل استناد

## 4) WordPress Bridge
فایل `wordpress-bridge/cheraghi-academy-bridge-v4.php` را به عنوان افزونه نصب کن.
نسخه فعلی Alpha است؛ ابتدا روی staging تست شود.
احراز هویت REST را با حساب WordPress محدود و Application Password انجام بده؛ credential را داخل workflow یا GitHub هاردکد نکن.

Endpointها:
- GET `/wp-json/cheraghi-academy/v4/health`
- POST `/wp-json/cheraghi-academy/v4/draft`
- POST `/wp-json/cheraghi-academy/v4/verify/{id}`
- POST `/wp-json/cheraghi-academy/v4/publish/{id}`
- GET `/wp-json/cheraghi-academy/v4/status/{id}`

## 5) n8n
`n8n/cheraghi-academy-v4.json` را Import کن.
این فایل Blueprint است و عمداً credential یا endpoint خصوصی ندارد.
پس از Import، مرحله‌های واقعی Source/AI/Semrush/WordPress را با credentialهای خود n8n وصل کن.

## 6) سیاست انتشار
تا وقتی حداقل 5 مقاله پشت سر هم تمام Gateها را بدون خطا پاس نکرده‌اند، Publish خودکار فعال نشود.
خروجی پیش‌فرض Draft باشد.

## 7) امنیت و تست
- ابتدا staging.
- credentialها فقط در n8n Credentials/WordPress Application Password.
- REST endpointهای Publish نیازمند `publish_posts` هستند.
- Source/QC fail = توقف انتشار.
- قبل از فعال‌سازی زمان‌بندی، یک Security Audit n8n اجرا شود.
