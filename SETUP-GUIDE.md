# راهنمای راه‌اندازی سیستم انتشار مقاله خودکار

## سایت: cheraghilaw.ir
## ابزار: n8n Cloud + Claude API + WordPress + Google Sheets

---

## مرحله ۱: ساخت Google Sheet

یه Google Sheet بساز با این ستون‌ها:

| keyword | secondary_keywords | word_count | status | published_date | post_url |
|---------|-------------------|------------|--------|---------------|----------|
| مهریه | مهریه چیست، نحوه مطالبه مهریه، مهریه زن | 2000 | pending | | |
| طلاق توافقی | شرایط طلاق توافقی، مراحل طلاق توافقی، هزینه طلاق توافقی | 1500 | pending | | |
| حضانت فرزند | حق حضانت، حضانت بعد از طلاق، سن حضانت | 2000 | pending | | |
| نفقه | نفقه زن، نحوه مطالبه نفقه، میزان نفقه | 1500 | pending | | |
| ارث | قانون ارث، سهم الارث، تقسیم ارث | 2000 | pending | | |
| وکیل خانواده | انتخاب وکیل خانواده، هزینه وکیل، وکیل طلاق | 500 | pending | | |
| عقد موقت | صیغه موقت، شرایط عقد موقت، حقوق زن در عقد موقت | 1500 | pending | | |
| تمکین | تمکین زن، عدم تمکین، تمکین خاص و عام | 1000 | pending | | |

### نکات مهم:
- ستون `status` باید `pending` باشه برای مقالاتی که هنوز منتشر نشدن
- بعد از انتشار، خودکار به `published` تغییر میکنه
- هر وقت خواستی مقاله جدید اضافه کنی، یه ردیف با `pending` اضافه کن
- `word_count` تعداد کلمات مقاله رو مشخص میکنه

---

## مرحله ۲: تنظیم Credentials در n8n

### ۲.۱ — Claude API Key
1. برو به **Settings → Credentials** در n8n
2. بجای Credential، از **Variables** استفاده کن:
   - برو به **Settings → Variables**
   - یه Variable بساز به نام `CLAUDE_API_KEY`
   - مقدارش رو بذار API Key کلود (شروع میشه با `sk-ant-...`)

### ۲.۲ — Google Sheets
1. در n8n برو به **Credentials → Add Credential**
2. نوع `Google Sheets OAuth2` رو انتخاب کن
3. مراحل احراز هویت گوگل رو طی کن
4. دسترسی به Google Sheets رو بده

### ۲.۳ — WordPress
1. در n8n برو به **Credentials → Add Credential**
2. نوع `WordPress` رو انتخاب کن
3. اطلاعات زیر رو وارد کن:
   - **URL**: `https://cheraghilaw.ir`
   - **Username**: نام کاربری ادمین وردپرس
   - **Password**: رمز Application Password (نه رمز معمولی!)

#### ساخت Application Password در وردپرس:
1. برو به **پیشخوان وردپرس → کاربران → ویرایش حساب**
2. اسکرول کن پایین تا بخش **Application Passwords**
3. یه اسم بذار (مثلاً `n8n`) و روی **Add New** بزن
4. رمز ایجاد شده رو کپی کن و در n8n وارد کن

---

## مرحله ۳: ایمپورت Workflow

1. در n8n برو به **Workflows**
2. روی **⋮** (سه نقطه) بالا سمت راست بزن
3. **Import from File** رو بزن
4. فایل `n8n-workflow-cheraghilaw.json` رو آپلود کن
5. workflow باز میشه

---

## مرحله ۴: تنظیم نودها

### ۴.۱ — نود Google Sheets (خواندن)
- Google Sheet ای که ساختی رو انتخاب کن
- شیت مربوطه رو انتخاب کن
- Credential گوگل رو وصل کن

### ۴.۲ — نود WordPress
- Credential وردپرس رو وصل کن
- تست کن ببین اتصال برقراره

### ۴.۳ — نود Google Sheets (آپدیت)
- همون Google Sheet رو انتخاب کن
- Credential رو وصل کن

---

## مرحله ۵: تست

1. یه ردیف تست توی شیت بذار با status = pending
2. در n8n روی **Test Workflow** بزن
3. ببین مقاله ساخته میشه و توی وردپرس منتشر میشه
4. اگه اوکی بود، workflow رو **Active** کن

---

## مرحله ۶: نصب Google Search Console

### روی وردپرس:
1. برو به [Google Search Console](https://search.google.com/search-console)
2. **Add Property** بزن
3. آدرس `https://cheraghilaw.ir` رو وارد کن
4. روش تأیید **HTML Tag** رو انتخاب کن
5. تگ متا رو کپی کن

### نصب روی سایت:
- **اگه Yoast SEO داری**: برو به SEO → General → Webmaster Tools → Google verification code
- **اگه Rank Math داری**: برو به Rank Math → General Settings → Webmaster Tools
- **یا** از افزونه **Site Kit by Google** استفاده کن (پیشنهاد میکنم)

### Site Kit:
1. افزونه **Site Kit by Google** رو نصب کن
2. با اکانت گوگلت لاگین کن
3. Search Console خودکار وصل میشه
4. میتونی Analytics هم اضافه کنی

---

## نکات تکمیلی

### تنظیم Timezone
- توی workflow، timezone روی `Asia/Tehran` تنظیم شده
- n8n Cloud ممکنه UTC باشه، پس ساعت ۱۰ صبح تهران = ساعت ۶:۳۰ UTC
- اگه ساعت دقیق نبود، trigger رو ادیت کن

### مانیتورینگ
- توی n8n میتونی **Executions** رو ببینی
- اگه خطایی بخوره، ایمیل نوتیفیکیشن بذار

### پیشنهاد کلمات کلیدی حقوقی
برای شروع، این موضوعات ترافیک خوبی دارن:
- مهریه و مطالبه مهریه
- طلاق توافقی و مراحلش
- حضانت فرزند
- نفقه زن
- ارث و تقسیم ارث
- وکیل خانواده
- عقد موقت
- تمکین
- خیانت زناشویی و حکم قانونی
- اجاره‌نامه و حقوق مستاجر
- چک برگشتی
- دیه و مجازات
- شکایت کیفری
- وصیت‌نامه
