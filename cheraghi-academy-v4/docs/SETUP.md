# راه‌اندازی V4

## 1) Project در ChatGPT
فایل `project/PROJECT_INSTRUCTIONS.md` را در Project Instructions پروژه «مقالات آکادمی سایت چراغی» قرار بده.

## 2) Skills
در صورت دسترسی، Skillهای زیر فعال باشند:
- `skills/CHERAGHI-ACADEMY-MASTER`
- `skills/SOURCE-GUARD`
- `skills/LEGAL-ANALYST`
- `skills/DR-CHERAGHI-NOTE`
- `skills/SEO-QC`

## 3) منابع
در Project/Drive منبع رسمی دفترچه، کلید و اصلاحیه‌ها قرار بگیرد. Source Guard بدون متن دقیق سؤال و چهار گزینه اجازه عبور نمی‌دهد.

## 4) WordPress Bridge
فایل `wordpress-bridge/cheraghi-academy-bridge-v4.php` ابتدا روی staging نصب شود. Credential در GitHub یا JSON workflow هاردکد نشود.

## 5) n8n — LOOP واقعی
فایل `n8n/cheraghi-academy-v4.json` اکنون Full Loop است.

منطق اجرا:
1. مدنی 1–20 چون DONE است حذف می‌شود.
2. تمام 25 مقاله باقی‌مانده از 21–24 تا 117–120 در یک اجرا ساخته می‌شوند؛ هر مقاله یک item مستقل n8n است.
3. هر item به Source Hook می‌رود.
4. Source Guard برای همان مقاله چهار سؤال را بررسی می‌کند.
5. مقاله فاقد منبع دقیق BLOCK می‌شود، اما بقیه itemها همچنان ارزیابی می‌شوند.
6. مقاله عبورکرده وارد Legal Analyst + Dr Cheraghi Note می‌شود.
7. سپس SEO/Semrush و QC اجرا می‌شود.
8. فقط item پاس‌شده اجازه ورود به WordPress Draft Hook را دارد.
9. انتهای اجرا Loop Summary تعداد draft-ready و blocked را گزارش می‌کند.

این مدل وابسته به «اولین بسته» نیست و کل باقی‌مانده دفترچه را در یک اجرای واقعی item-based پردازش می‌کند.

## 6) Hookهایی که هنوز باید به سرویس واقعی وصل شوند
- `Source Payload Hook - CONNECT DRIVE/PDF`
- `Legal Agent Hook - CONNECT CHATGPT/CODEX`
- `Semrush Hook - OPTIONAL`
- `WordPress Draft Hook - CONNECT BRIDGE V4`

این Hookها عمداً credential ندارند.

## 7) سیاست انتشار
- خروجی پیش‌فرض Draft.
- Source fail یا QC fail = انتشار ممنوع.
- حداقل 5 Draft متوالی بدون خطا قبل از هرگونه Publish خودکار.
- Publish endpoint فقط برای محتوای approved.

## 8) امنیت
- ابتدا staging.
- WordPress Application Password محدود.
- Credentialها فقط در n8n Credentials.
- قبل از Schedule، Security Audit اجرا شود.
