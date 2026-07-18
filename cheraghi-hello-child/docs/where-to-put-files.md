# دقیقاً فایل‌ها را کجا بگذارم؟

## جواب خیلی کوتاه

فقط همین پوشه باید روی سایت وردپرسی آپلود شود:

```text
cheraghi-hello-child
```

این پوشه باید داخل مسیر قالب‌های وردپرس قرار بگیرد:

```text
wp-content/themes/cheraghi-hello-child/
```

بعد از آپلود، مسیر فایل اصلی قالب باید این باشد:

```text
wp-content/themes/cheraghi-hello-child/style.css
```

اگر `style.css` دقیقاً در این مسیر نبود، قالب درست نصب نشده است.

## روش پیشنهادی ۱: نصب از پنل وردپرس

1. روی کامپیوتر، فقط پوشه `cheraghi-hello-child` را ZIP کنید.
2. نام فایل ZIP بهتر است این باشد:

```text
cheraghi-hello-child.zip
```

3. وارد پنل وردپرس شوید.
4. بروید به:

```text
نمایش ← پوسته‌ها ← افزودن پوسته تازه ← بارگذاری پوسته
```

5. فایل `cheraghi-hello-child.zip` را آپلود کنید.
6. بعد از نصب، قالب `Cheraghi Hello Child` را فعال کنید.
7. اگر وردپرس گفت قالب والد `Hello Elementor` نصب نیست، اول قالب `Hello Elementor` را نصب کنید.

## روش پیشنهادی ۲: آپلود از File Manager هاست یا FTP

1. وارد File Manager هاست شوید.
2. به مسیر زیر بروید:

```text
public_html/wp-content/themes/
```

در بعضی هاست‌ها ممکن است مسیر سایت فرق کند، ولی بخش آخر همیشه همین است:

```text
wp-content/themes/
```

3. پوشه `cheraghi-hello-child` را اینجا آپلود کنید.
4. نتیجه نهایی باید این باشد:

```text
public_html/wp-content/themes/cheraghi-hello-child/style.css
public_html/wp-content/themes/cheraghi-hello-child/functions.php
public_html/wp-content/themes/cheraghi-hello-child/preview/homepage-concept.html
```

5. بعد وارد وردپرس شوید و از مسیر زیر قالب را فعال کنید:

```text
نمایش ← پوسته‌ها
```

## لینک دیدن پیش‌نمایش بعد از آپلود

بعد از اینکه پوشه قالب در جای درست قرار گرفت، این لینک را در مرورگر باز کنید:

```text
https://cheraghilaw.ir/wp-content/themes/cheraghi-hello-child/preview/homepage-concept.html
```

اگر این لینک 404 داد، یعنی یکی از این موارد اتفاق افتاده است:

- پوشه `cheraghi-hello-child` هنوز آپلود نشده است.
- پوشه در مسیر اشتباه آپلود شده است.
- اسم پوشه تغییر کرده است.
- فایل `preview/homepage-concept.html` داخل پوشه نیست.
- هاست دسترسی مستقیم به فایل‌های داخل theme را بسته است.

## چه چیزهایی را نباید آپلود کنید؟

این‌ها را داخل وردپرس آپلود نکنید:

```text
.git
scripts
index.html پروژه فعلی
فایل‌های خارج از پوشه cheraghi-hello-child
```

برای وردپرس، فقط پوشه زیر لازم است:

```text
cheraghi-hello-child
```

## بعد از فعال‌سازی قالب چه کنم؟

1. Elementor Pro فعال باشد.
2. از مسیر زیر لینک‌های مشاوره را تنظیم کنید:

```text
نمایش ← سفارشی‌سازی ← لینک‌های مشاوره چراغی
```

3. لینک مشاوره آنلاین را روی صفحه پرداخت یا فرم پرداخت بگذارید.
4. لینک مشاوره تلفنی را روی صفحه رزرو/پرداخت تلفنی بگذارید.
5. بعد صفحه اصلی، هدر و فوتر را در Elementor Theme Builder بسازید.

## هشدار مهم

این قالب هنوز جایگزین کامل طراحی فعلی سایت نیست. این یک پایه سبک + پیش‌نمایش پیشنهادی است. برای مهاجرت کامل، باید در Elementor Pro صفحه‌ها، هدر، فوتر، قالب نوشته‌ها و مسیر پرداخت ساخته و تست شوند.
