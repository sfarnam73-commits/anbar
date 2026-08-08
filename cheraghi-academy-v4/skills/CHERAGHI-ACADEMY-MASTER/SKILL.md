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

## 2) Source Guard
- متن سؤال و گزینه‌ها را فقط از فایل رسمی/اصلی استخراج کن.
- شماره سؤال، گزینه، پاسخ یا ماده را از حافظه حدس نزن.
- اگر منبع ناقص/مبهم است: STOP = NEEDS_SOURCE_REVIEW.

## 3) Answer Key Verification
- کلید رسمی آزمون و اصلاحیه‌های رسمی را بررسی کن.
- در صورت تعارض، اصلاحیه رسمی مقدم است.
- اگر پاسخ رسمی محل اختلاف است، اختلاف را شفاف ثبت کن و Final نده تا بررسی شود.

## 4) Legal Analysis
برای هر سؤال:
1. متن دقیق سؤال
2. گزینه‌ها
3. پاسخ صحیح
4. مبنای قانونی/اصولی
5. تحلیل مرحله‌به‌مرحله
6. علت رد هر گزینه غلط
7. دام تستی
8. مثال ساده
9. نکته دکتر چراغی
10. نکته طلایی آزمون

### نکته دکتر چراغی
- 2 تا 5 جمله.
- ساده، کاربردی و آموزشی.
- تکرار تحلیل رسمی نباشد.
- هیچ نظر شخصی، حکم یا ادعای ساختگی به نام دکتر چراغی تولید نکن.
- فقط از تحلیل حقوقی تأییدشده نتیجه‌گیری آموزشی کن.

## 5) Long-form Article
مقاله را به یک محتوای آکادمی واقعی تبدیل کن، نه پاسخنامه کوتاه.
- H1 طبیعی
- مقدمه
- فهرست 4 سؤال
- چهار بخش تحلیلی
- جمع‌بندی
- نکات مرور سریع
- FAQ
- منابع/مستندات

اصل: «گسترش فقط با افزودن ارزش». از کش‌دادن متن برای رسیدن به تعداد کلمه خودداری کن.

## 6) SEO Routing
اگر Semrush در دسترس است و SEO ارزش افزوده دارد:
- intent
- primary keyword
- related terms
- content gaps
- FAQ opportunities
را بررسی و به شکل طبیعی اعمال کن.

سپس:
- SEO title
- meta description
- slug
- internal-link suggestions
را آماده کن.

## 7) Quality Gates
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
- DR_CHERAGHI_NOTE_PRESENT
- ALL_4_QUESTIONS_COMPLETE

اگر هر Gate شکست خورد: status = NEEDS_REVIEW و Publish ممنوع.

## 8) Featured Image Brief
بعد از قفل نهایی محتوا، Brief تصویر شاخص تولید کن:
- فقط عنوان مقاله روی تصویر مگر کاربر خلافش را بخواهد.
- هویت بصری حقوقی چراغی.
- بدون متن توضیحی اضافه.

## 9) Publish Policy
پیش‌فرض: DRAFT.
فقط با تأیید صریح یا Rule تأییدشده پروژه به APPROVED/PUBLISHED تغییر بده.

## 10) Tool Routing
- Drive/Files = source truth
- Web = laws, official corrections, current verification
- Semrush = SEO intelligence
- Canva/Image = featured image
- GitHub/Codex = workflow/plugin code, version control, testing

## Output Status
در پایان یکی از این وضعیت‌ها را برگردان:
- FINAL_DRAFT
- NEEDS_SOURCE_REVIEW
- NEEDS_LEGAL_REVIEW
- NEEDS_SEO_REVIEW
- READY_FOR_APPROVAL
- PUBLISHED
