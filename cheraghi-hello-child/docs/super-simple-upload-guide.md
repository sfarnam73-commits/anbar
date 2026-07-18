# راهنمای خیلی ساده: این فایل را دقیقاً کجا بگذارم؟

## اول فقط این را بدان

تو فقط با این فایل کار داری:

```text
dist/cheraghi-hello-child.zip
```

یعنی لازم نیست پوشه‌ها و فایل‌های دیگر را دستکاری کنی.

---

## روش خیلی ساده از داخل وردپرس

### قدم ۱: وارد پنل وردپرس شو

در مرورگر بزن:

```text
https://cheraghilaw.ir/wp-admin
```

نام کاربری و رمز وردپرس را وارد کن.

---

### قدم ۲: برو قسمت پوسته‌ها

از منوی سمت راست وردپرس برو به:

```text
نمایش ← پوسته‌ها
```

اگر وردپرس انگلیسی بود:

```text
Appearance → Themes
```

---

### قدم ۳: افزودن پوسته تازه را بزن

بالای صفحه روی این دکمه بزن:

```text
افزودن پوسته تازه
```

اگر انگلیسی بود:

```text
Add New Theme
```

---

### قدم ۴: بارگذاری پوسته را بزن

بالای صفحه روی این گزینه بزن:

```text
بارگذاری پوسته
```

اگر انگلیسی بود:

```text
Upload Theme
```

---

### قدم ۵: فایل ZIP را انتخاب کن

روی انتخاب فایل بزن و این فایل را انتخاب کن:

```text
dist/cheraghi-hello-child.zip
```

بعد دکمه نصب را بزن:

```text
هم‌اکنون نصب کن
```

اگر انگلیسی بود:

```text
Install Now
```

---

### قدم ۶: اگر خطای قالب والد داد

اگر وردپرس گفت قالب والد پیدا نشد یا `hello-elementor` نصب نیست، یعنی باید اول قالب Hello Elementor را نصب کنی.

برای نصب Hello Elementor برو به:

```text
نمایش ← پوسته‌ها ← افزودن پوسته تازه
```

در سرچ بنویس:

```text
Hello Elementor
```

بعد نصب و فعالش کن.

سپس دوباره فایل زیر را آپلود کن:

```text
dist/cheraghi-hello-child.zip
```

---

### قدم ۷: قالب را فعال کن

بعد از نصب، وردپرس یک دکمه نشان می‌دهد:

```text
فعال کردن
```

روی آن بزن.

اگر انگلیسی بود:

```text
Activate
```

---

## بعد از فعال کردن، پیش‌نمایش را کجا ببینم؟

بعد از اینکه قالب نصب شد، این لینک را در مرورگر باز کن:

```text
https://cheraghilaw.ir/wp-content/themes/cheraghi-hello-child/preview/homepage-concept.html
```

اگر صفحه باز شد، یعنی فایل درست جای خودش است.

اگر 404 داد، یعنی یا قالب نصب نشده، یا اسم پوشه عوض شده، یا هاست اجازه دیدن فایل را نداده.

---

## اگر بخواهی با File Manager هاست انجام بدهی

اگر از پنل وردپرس نتوانستی نصب کنی، این روش دوم است.

### قدم ۱: وارد هاست شو

وارد پنل هاست شو. معمولاً چیزی شبیه این‌هاست:

```text
cPanel
DirectAdmin
File Manager
```

---

### قدم ۲: File Manager را باز کن

داخل هاست روی این گزینه بزن:

```text
File Manager
```

---

### قدم ۳: برو داخل public_html

داخل فایل منیجر برو به:

```text
public_html
```

---

### قدم ۴: برو داخل wp-content

بعد برو داخل:

```text
wp-content
```

---

### قدم ۵: برو داخل themes

بعد برو داخل:

```text
themes
```

پس مسیر کامل می‌شود:

```text
public_html/wp-content/themes/
```

---

### قدم ۶: فایل ZIP را اینجا آپلود کن

داخل همین پوشه `themes` فایل زیر را آپلود کن:

```text
dist/cheraghi-hello-child.zip
```

---

### قدم ۷: فایل ZIP را Extract کن

روی فایل ZIP کلیک کن و گزینه Extract را بزن.

بعد از Extract باید این پوشه ساخته شود:

```text
public_html/wp-content/themes/cheraghi-hello-child/
```

داخل این پوشه باید این فایل‌ها را ببینی:

```text
style.css
functions.php
README.md
preview
assets
docs
```

اگر به جای این، این حالت را دیدی، اشتباه است:

```text
public_html/wp-content/themes/cheraghi-hello-child/cheraghi-hello-child/style.css
```

یعنی یک پوشه اضافه وسط مسیر افتاده. باید پوشه داخلی را بیاوری بالا تا مسیر درست شود:

```text
public_html/wp-content/themes/cheraghi-hello-child/style.css
```

---

## خلاصه نهایی در یک خط

اگر از وردپرس می‌روی:

```text
نمایش ← پوسته‌ها ← افزودن پوسته تازه ← بارگذاری پوسته ← dist/cheraghi-hello-child.zip
```

اگر از هاست می‌روی:

```text
public_html/wp-content/themes/cheraghi-hello-child/
```

و فایل اصلی قالب باید اینجا باشد:

```text
public_html/wp-content/themes/cheraghi-hello-child/style.css
```
