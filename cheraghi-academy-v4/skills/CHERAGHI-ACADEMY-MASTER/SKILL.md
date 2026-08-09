---
name: CHERAGHI-ACADEMY-MASTER
description: تولید، تحلیل، کنترل و آماده‌سازی مقالات آکادمی دکتر فرزانه چراغی از دفترچه‌های آزمون وکالت؛ هر چهار سؤال یک مقاله، با قفل منبع، تحلیل حقوقی، نکته دکتر چراغی، SEO و QC چندمرحله‌ای.
---

# CHERAGHI ACADEMY MASTER

## Trigger
وقتی کاربر درخواست ادامه/ساخت/بازبینی مقالات آکادمی از دفترچه آزمون وکالت را می‌دهد، این Skill را اجرا کن.

## 1) Load State
- فایل وضعیت آزمون را بخوان.
- اولین بسته 4 سؤالی با وضعیت TODO یا NEEDS_REVIEW را انتخاب کن.
- بسته‌های DONE را بدون درخواست صریح بازنویسی نکن.
- اگر current_batch وجود دارد، همان را ادامه بده.

## 2) Source Guard — اجباری
- Skill `CHERAGHI-SOURCE-GUARD` را اجرا کن.
- متن سؤال و گزینه‌ها را فقط از فایل رسمی/اصلی استخراج کن.
- شماره سؤال، گزینه، پاسخ یا ماده را از حافظه حدس نزن.
- وجود منبع در وب به‌تنهایی کافی نیست؛ متن دقیق باید در دسترس pipeline باشد.
- اگر منبع ناقص/مبهم است: STOP = NEEDS_SOURCE_REVIEW.

## 3) Answer Key Verification
- کلید رسمی آزمون و اصلاحیه‌های رسمی را بررسی کن.
- در صورت تعارض، اصلاحیه رسمی مقدم است.
- اصلاحیه‌های دفترچه C سال ۱۳۹۸ از state خوانده شوند؛ دست‌نویس یا حافظه مقدم نیست.
- اگر پاسخ رسمی محل اختلاف است، اختلاف را شفاف ثبت کن و Final نده تا بررسی شود.

## 4) Legal Analysis
Skill `CHERAGHI-LEGAL-ANALYST` را اجرا کن. برای هر سؤال:
1. متن دقیق سؤال
2. چهار گزینه
3. پاسخ صحیح
4. مبنای قانونی/اصولی
5. تحلیل مرحله‌به‌مرحله
6. علت رد هر گزینه غلط
7. دام تستی
8. مثال ساده
9. نکته دکتر چراغی
10. نکته طلایی آزمون

## 5) Dr Cheraghi Note
پس از تأیید تحلیل، Skill `CHERAGHI-DR-NOTE` اجرا شود.
- 2 تا 5 جمله.
- ساده، کاربردی و آموزشی.
- تکرار تحلیل رسمی نباشد.
- هیچ نظر شخصی، حکم یا ادعای ساختگی به نام دکتر چراغی تولید نکن.
- اگر سؤال `NEEDS_LEGAL_REVIEW` است، این بخش تولید نشود.

## 6) Long-form Article Standard
مقاله باید یک محتوای آکادمی واقعی و مستقل باشد، نه پاسخنامه کوتاه.
- H1 طبیعی
- مقدمه
- فهرست 4 سؤال
- چهار بخش تحلیلی کامل
- جمع‌بندی
- نکات مرور سریع
- FAQ
- منابع/مستندات

### Word-count policy
- هدف استاندارد هر مقاله: 2500 تا 3500 کلمه.
- حداقل قابل قبول: 2500 کلمه، مگر اینکه افزودن متن بیشتر باعث تکرار یا افت کیفیت شود؛ در این حالت خروجی باید دلیل کوتاه ثبت کند.
- سقف مصنوعی وجود ندارد؛ اگر تحلیل کامل چهار سؤال به بیش از 3500 کلمه نیاز داشت، گسترش مجاز است.
- اصل حاکم: «گسترش فقط با افزودن ارزش». پرکردن مقاله با تکرار، عبارت‌های کلی یا پاراگراف‌های بی‌فایده ممنوع است.

## 7) SEO Routing — کلیدواژه اختصاصی هر مقاله
Skill `CHERAGHI-SEO-QC` را اجرا کن.

برای هر بسته 4 سؤالی، قبل از نهایی‌سازی مقاله یک Focus Keyword مستقل انتخاب کن.
- Focus Keyword دو مقاله در یک دفترچه نباید یکسان باشد.
- موضوع/بازه سؤال باید در کلیدواژه Long-tail بازتاب داشته باشد.
- از رقابت داخلی و Keyword Cannibalization جلوگیری کن.
- اگر Semrush در دسترس است، intent، volume/KD در صورت وجود، related terms، questions و content gap را بررسی کن.
- اگر Semrush در دسترس نیست، وضعیت `SEO_DATA_LIMITED` ثبت کن؛ هیچ داده Semrush را حدس نزن.

برای مقاله سؤال‌های 21 تا 24 آیین دادرسی مدنی 1398، seed پیشنهادی:
`تحلیل سوالات ۲۱ تا ۲۴ آیین دادرسی مدنی آزمون وکالت ۱۳۹۸`
و عبارت گسترده‌تر قابل استفاده در عنوان:
`تحلیل سوالات آیین دادرسی مدنی آزمون وکالت ۱۳۹۸`

سپس تولید کن:
- primary/focus keyword یکتا
- 4 تا 10 related/secondary keywords طبیعی
- search intent
- SEO title
- meta description
- slug
- H2/H3 map
- FAQ opportunities
- internal-link suggestions

Keyword stuffing ممنوع است. استفاده از کلیدواژه باید طبیعی و تابع خوانایی باشد.

## 8) Quality Gates
برای هر سؤال چهار Gate اجرا کن:
- SOURCE_MATCH
- KEY_MATCH
- LEGAL_SUPPORT
- LOGIC_CONSISTENCY

برای مقاله:
- NO_FABRICATION
- NO_DUPLICATION
- READABILITY
- SEO_NATURAL
- UNIQUE_FOCUS_KEYWORD
- NO_KEYWORD_CANNIBALIZATION
- DR_CHERAGHI_NOTE_PRESENT
- ALL_4_QUESTIONS_COMPLETE
- ARTICLE_SCHEMA_VALID
- WORD_COUNT_POLICY_PASS

خروجی ساختاری باید با `schemas/article-contract.json` سازگار باشد.
اگر هر Gate بحرانی شکست خورد: status = NEEDS_REVIEW و Publish ممنوع.

## 9) Featured Image Brief
بعد از قفل نهایی محتوا، Brief تصویر شاخص تولید کن:
- فقط عنوان مقاله روی تصویر مگر کاربر خلافش را بخواهد.
- هویت بصری حقوقی چراغی.
- بدون متن توضیحی اضافه.

## 10) Publish Policy
پیش‌فرض: DRAFT.
فقط با تأیید صریح یا Rule تأییدشده پروژه به APPROVED/PUBLISHED تغییر بده.
حداقل 5 مقاله متوالی باید تمام Gateها را پاس کنند تا پیشنهاد فعال‌سازی Publish خودکار مطرح شود.

## 11) Tool Routing
- Drive/Files = source truth
- Web = قوانین، اصلاحیه رسمی و verification
- Semrush = SEO intelligence
- Canva/Image = featured image
- GitHub/Codex = workflow/plugin code, version control, tests
- n8n = orchestration فقط؛ منبع حقیقت یا نویسنده حقوقی نیست

## Output Status
در پایان فقط یکی از این وضعیت‌ها معتبر است:
- FINAL_DRAFT
- NEEDS_SOURCE_REVIEW
- NEEDS_LEGAL_REVIEW
- NEEDS_SEO_REVIEW
- SEO_DATA_LIMITED
- READY_FOR_APPROVAL
- PUBLISHED
