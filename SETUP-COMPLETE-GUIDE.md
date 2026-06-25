# راهنمای کامل نصب و راه‌اندازی — قدم به قدم

---

## مرحله ۱: حذف workflow قدیمی

1. مرورگر رو باز کن → برو به `http://localhost:5678`
2. از لیست workflow ها، روی **My workflow** (همون قبلیه) کلیک کن
3. بالا سمت راست روی **⋮** (سه نقطه) بزن
4. **Delete** رو بزن و تأیید کن

---

## مرحله ۲: ایمپورت workflow جدید

1. از صفحه اصلی n8n، بالا سمت راست روی **⋮** بزن
2. **Import from File** رو بزن
3. فایل **n8n-workflow-cheraghilaw-v2.json** رو که بهت دادم انتخاب کن
4. workflow باز میشه و همه نودها رو میبینی

---

## مرحله ۳: تنظیم Credential کلود (Anthropic)

> احتمالاً این رو قبلاً داری چون workflow قبلیت داشت. اگه داری از همون استفاده میشه.

اگه نداری:
1. سمت چپ n8n روی **⚙️ Settings** بزن
2. **Credentials** رو بزن
3. بالا سمت راست **Add Credential** بزن
4. سرچ کن: **Anthropic**
5. روی **Anthropic API** کلیک کن
6. فیلد **API Key** رو پر کن:
   - API Key شروع میشه با `sk-ant-api03-...`
   - اگه نداری از اینجا بگیر: https://console.anthropic.com/settings/keys
7. **Save** بزن
8. حالا برگرد به workflow → روی نود **تولید مقاله با Claude** دوبار کلیک کن
9. بالای نود، قسمت **Credential** رو ببین → **Anthropic account** رو انتخاب کن
10. **Back** بزن

---

## مرحله ۴: تنظیم Credential وردپرس

### ۴.۱ — ساخت Application Password در وردپرس

> ⚠️ مهم: رمز عادی وردپرس کار نمیکنه! باید Application Password بسازی.

1. برو به سایتت: `https://cheraghilaw.ir/wp-admin`
2. لاگین کن
3. از منوی سمت راست برو به: **کاربران** → **شناسنامه** (یا Profile)
4. اسکرول کن پایین تا برسی به بخش **گذرواژه‌های برنامه** (Application Passwords)
5. توی فیلد **نام گذرواژه جدید** بنویس: `n8n`
6. دکمه **افزودن گذرواژه جدید** رو بزن
7. ⚠️ **مهم**: یه رمز بلند نشون داده میشه (مثل `xxxx xxxx xxxx xxxx xxxx xxxx`)
8. **این رمز رو فوری کپی کن!** چون دیگه نشونت نمیده
9. این رمز رو یه جای امن ذخیره کن

### ۴.۲ — تنظیم Credential در n8n

1. توی n8n برو به **Settings** → **Credentials**
2. اگه قبلاً **Wordpress account** داری → روش کلیک کن و ویرایشش کن
3. اگه نداری → **Add Credential** → سرچ کن **WordPress**
4. فیلدها رو پر کن:

```
WordPress URL:     https://cheraghilaw.ir
Username:          نام کاربری ادمین وردپرست (مثلاً admin)
Password:          اون Application Password که کپی کردی (xxxx xxxx xxxx xxxx)
```

5. **Save** بزن
6. باید بنویسه **Connection tested successfully** ✅
7. اگه خطا داد:
   - مطمئن شو URL درسته (https نه http)
   - مطمئن شو Application Password رو گذاشتی نه رمز عادی
   - مطمئن شو REST API وردپرس فعاله

### ۴.۳ — وصل کردن به نود

1. برگرد به workflow
2. روی نود **انتشار در وردپرس** دوبار کلیک کن
3. قسمت **Credential** → **Wordpress account** رو انتخاب کن
4. **Back** بزن

---

## مرحله ۵: تنظیم Credential برای HTTP Request (Rank Math)

نود **ثبت سئو Rank Math** هم نیاز به Credential داره:

1. توی n8n برو به **Settings** → **Credentials**
2. اگه قبلاً **Unnamed credential** (از نوع HTTP Basic Auth) داری → ویرایشش کن
3. اگه نداری → **Add Credential** → سرچ کن **HTTP Basic Auth**
4. فیلدها رو پر کن:

```
User:       همون نام کاربری ادمین وردپرس
Password:   همون Application Password که ساختی
```

5. **Save** بزن
6. برگرد به workflow → روی نود **ثبت سئو Rank Math** دوبار کلیک کن
7. Credential رو وصل کن

---

## مرحله ۶: ساخت ربات تلگرام

### ۶.۱ — ساخت Bot

1. توی تلگرام سرچ کن: **@BotFather**
2. روش کلیک کن و **Start** بزن
3. بنویس: `/newbot`
4. اسم ربات رو بذار (مثلاً: `چراغی بات`)
5. یه username بده (مثلاً: `cheraghi_law_bot`) — باید به `bot` ختم بشه
6. BotFather یه **Token** بهت میده. مثل این:
```
7123456789:AAHxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```
7. **این Token رو کپی کن**

### ۶.۲ — گرفتن Chat ID خودت

1. توی تلگرام سرچ کن: **@userinfobot**
2. روش کلیک کن و **Start** بزن
3. یه عدد بهت میده — این **Chat ID** توئه (مثلاً `123456789`)
4. **این عدد رو کپی کن**

### ۶.۳ — تنظیم Credential تلگرام در n8n

1. توی n8n برو به **Settings** → **Credentials**
2. **Add Credential** → سرچ کن **Telegram**
3. فیلد رو پر کن:

```
Access Token:    همون Token که از BotFather گرفتی
```

4. **Save** بزن

### ۶.۴ — وصل کردن به نود

1. برگرد به workflow
2. روی نود **اطلاع‌رسانی تلگرام** دوبار کلیک کن
3. **Credential** → Telegram رو انتخاب کن
4. فیلد **Chat ID** → اون عدد Chat ID رو بذار (مثلاً `123456789`)
5. **Back** بزن

---

## مرحله ۷: تست workflow

### تست دستی (مهم! حتماً اول تست کن)

1. مطمئن شو همه نودها Credential دارن (هیچکدوم علامت ⚠️ نداشته باشه)
2. دکمه **Test Workflow** رو بزن (پایین صفحه)
3. صبر کن تا اجرا بشه (۳۰ تا ۶۰ ثانیه طول میکشه)
4. هر نود باید سبز بشه ✅
5. چک کن:
   - مقاله توی وردپرس منتشر شده؟ → برو `cheraghilaw.ir/wp-admin` → نوشته‌ها
   - پیام تلگرام اومده؟
   - مقاله کلمه کلیدی و متا داره؟

### اگه خطا گرفتی:

**خطا در نود Claude:**
- API Key درسته؟ → Settings → Credentials → Anthropic → تست کن
- موجودی API داری؟ → https://console.anthropic.com/settings/billing

**خطا در نود وردپرس:**
- Application Password درسته؟
- آدرس سایت درسته؟ (https)
- REST API فعاله؟ → توی مرورگر برو به: `https://cheraghilaw.ir/wp-json/wp/v2/posts` — اگه JSON نشون داد یعنی اوکیه

**خطا در نود Rank Math:**
- Rank Math نصبه روی وردپرس؟
- اگه Rank Math نداری، این نود رو Disable کن (مشکلی پیش نمیاد)

**خطا در نود تلگرام:**
- Token درسته؟
- Chat ID درسته؟
- حتماً یه بار به ربات خودت توی تلگرام `/start` بفرست

---

## مرحله ۸: فعال‌سازی زمان‌بند

بعد از اینکه تست دستی موفق بود:

1. بالای workflow، سمت راست یه دکمه **Inactive/Active** هست
2. روش بزن تا **Active** بشه (سبز میشه)
3. از الان هر روز ساعت ۱۰ صبح خودکار اجرا میشه

### نکته مهم درباره ساعت:
- n8n ممکنه ساعت UTC داشته باشه
- ساعت ۱۰ صبح تهران = ساعت ۶:۳۰ صبح UTC (تابستان) یا ۶:۳۰ (زمستان)
- اگه ساعت دقیق نبود:
  1. روی نود **Schedule Trigger** دوبار کلیک کن
  2. ساعت رو عوض کن
  3. یا توی Settings n8n، Timezone رو بذار `Asia/Tehran`

### تنظیم Timezone در n8n:
1. فایل `.env` یا `docker-compose.yml` رو باز کن (بستگی داره چجوری نصبه)
2. اضافه کن:
```
GENERIC_TIMEZONE=Asia/Tehran
```
3. n8n رو ریستارت کن

---

## مرحله ۹: نصب Google Search Console روی سایت

> این کار توی n8n نیست — توی خود وردپرس و گوگل انجام میشه.

### ۹.۱ — ثبت سایت در Search Console

1. برو به: https://search.google.com/search-console
2. با اکانت گوگلت لاگین کن
3. سمت چپ بالا کلیک کن → **Add property**
4. **URL prefix** رو انتخاب کن
5. بنویس: `https://cheraghilaw.ir`
6. **Continue** بزن

### ۹.۲ — تأیید مالکیت

بهترین روش با **Rank Math**:

1. توی گوگل سرچ کنسول، روش **HTML tag** رو انتخاب کن
2. یه کد مثل این نشون میده:
```
<meta name="google-site-verification" content="xxxxxxxxxxxxxxxxxx" />
```
3. فقط اون مقدار `content` رو کپی کن (بدون تگ)
4. برو به وردپرس → **Rank Math** → **General Settings** → **Webmaster Tools**
5. فیلد **Google Search Console** رو پیدا کن
6. اون کد رو Paste کن
7. **Save Changes** بزن
8. برگرد به گوگل و **Verify** بزن
9. باید بگه **Ownership verified** ✅

### ۹.۳ — ثبت سایت‌مپ

1. توی Google Search Console سمت چپ بزن **Sitemaps**
2. توی فیلد بالا بنویس: `sitemap_index.xml`
3. **Submit** بزن
4. باید وضعیتش بشه **Success** ✅

> حالا هر بار مقاله جدید منتشر بشه، workflow خودکار به گوگل پینگ میزنه تا سریع‌تر ایندکس بشه.

---

## خلاصه کل Credential ها

| نود | نوع Credential | چی لازمه |
|-----|---------------|----------|
| تولید مقاله با Claude | Anthropic API | API Key از console.anthropic.com |
| انتشار در وردپرس | WordPress | URL + Username + Application Password |
| ثبت سئو Rank Math | HTTP Basic Auth | Username + Application Password |
| اطلاع‌رسانی تلگرام | Telegram API | Bot Token از @BotFather |

---

## سوالات رایج

**س: اگه ۱۵ تا مقاله تموم بشه چی؟**
ج: از اول شروع میکنه. ولی میتونی موضوعات جدید اضافه کنی — بگو تا بهت بگم چجوری.

**س: میتونم یه مقاله رو حذف کنم؟**
ج: بله، از پیشخوان وردپرس → نوشته‌ها → حذف

**س: اگه بخوام مقاله draft باشه نه publish؟**
ج: روی نود «انتشار در وردپرس» کلیک کن → Additional Fields → Status رو عوض کن به `draft`

**س: هزینه Claude API چقدره؟**
ج: هر مقاله تقریباً ۰.۰۵ تا ۰.۱۰ دلار هزینه داره. ماهی ۱۵ مقاله حدود ۱.۵ دلار.
