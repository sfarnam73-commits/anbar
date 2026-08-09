---
name: CHERAGHI-SEO-QC
description: کنترل نهایی علمی، ساختاری و سئوی مقالات آکادمی چراغی پیش از انتشار.
---

# SEO + QC AUDITOR

## Scientific QC
- تطبیق 4/4 سؤال با منبع.
- تطبیق پاسخ با کلید/اصلاحیه.
- بررسی صحت مواد و مستندات.
- بررسی تناقض، ابهام و نسبت‌دادن ادعای بی‌منبع.
- بررسی وجود تحلیل گزینه‌های غلط، مثال، دام تستی، نکته دکتر چراغی و نکته طلایی.

## Content QC
- هدف استاندارد مقاله 2500 تا 3500 کلمه است.
- مقاله زیر 2500 کلمه فقط وقتی قابل قبول است که افزودن متن بیشتر تکراری/بی‌ارزش باشد و دلیل ثبت شود.
- مقاله بالای 3500 کلمه مجاز است اگر برای تحلیل کامل چهار سؤال واقعاً لازم باشد.
- حذف تکرار و کش‌دادن بی‌ارزش.
- خوانایی بالا و لحن آموزشی حرفه‌ای.
- مقاله باید مستقل و مفید باشد، نه صرفاً پاسخنامه.

## SEO Intelligence
برای هر مقاله یک Focus Keyword مستقل بساز.
- Focus Keyword نباید با Focus Keyword مقاله دیگری در همان مجموعه یکسان باشد.
- بازه سؤال و درس در Long-tail تا حد امکان مشخص باشد.
- قبل از تأیید، خطر Keyword Cannibalization با مقالات قبلی پروژه بررسی شود.
- 4 تا 10 related/secondary keyword طبیعی تولید/اعتبارسنجی شود.

اگر Semrush در دسترس است، intent، keyword metrics موجود، related terms، questions و content gap را بررسی کن.
اگر Semrush داده نداد یا API Unit کافی نبود:
- هیچ عدد/داده‌ای را جعل نکن.
- `seo_data_status = SEO_DATA_LIMITED` ثبت کن.
- ساختار on-page را همچنان بر اساس موضوع و منبع آماده کن، ولی ادعای «Semrush verified» نکن.

## On-page SEO
- عنوان SEO طبیعی و غیرکلیک‌بیتی.
- Meta description دقیق.
- Slug کوتاه و قابل فهم.
- H2/H3 منظم.
- FAQ غیرتکراری.
- پیشنهاد لینک داخلی واقعی/مرتبط.
- استفاده طبیعی از Focus Keyword و مترادف‌ها.
- Keyword stuffing ممنوع.

## Gates
Scientific gates:
- ALL_4_QUESTIONS_SOURCE_MATCH = true
- ANSWER_KEY_MATCH = true
- LEGAL_SUPPORT = true
- NO_FABRICATION = true

Content gates:
- WORD_COUNT_POLICY_PASS = true
- NO_PADDING = true
- READABILITY_PASS = true

SEO gates:
- UNIQUE_FOCUS_KEYWORD = true
- NO_KEYWORD_CANNIBALIZATION = true
- SEO_TITLE_PRESENT = true
- META_PRESENT = true
- SLUG_PRESENT = true
- INTENT_PRESENT = true
- SEO_NATURAL = true

اگر معیار بحرانی شکست خورد: `NEEDS_REVIEW` و انتشار متوقف شود.
اگر فقط داده Semrush محدود باشد ولی بقیه Gateها پاس شوند: `SEO_DATA_LIMITED` ثبت شود و مقاله برای بازبینی انسانی/تکمیل داده SEO آماده بماند.
اگر همه معیارها پاس شدند: `READY_FOR_APPROVAL`.
