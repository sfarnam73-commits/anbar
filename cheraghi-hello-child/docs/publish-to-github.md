# چطور این پروژه را روی GitHub بگذارم و فایل ZIP را دانلود کنم؟

## وضعیت فعلی

این پروژه داخل محیط کاری ساخته شده است، اما تا وقتی روی GitHub push نشود، شما لینک دانلود عمومی ندارید.

فایل آماده نصب وردپرس این است:

```text
dist/cheraghi-hello-child.zip
```

## راه خیلی ساده برای ساخت ریپو در GitHub

1. برو به سایت GitHub:

```text
https://github.com
```

2. وارد اکانت خودت شو.
3. بالا سمت راست روی `+` بزن.
4. گزینه `New repository` را بزن.
5. اسم ریپو را بگذار:

```text
cheraghi-hello-child
```

6. گزینه `Private` یا `Public` را انتخاب کن.
   - اگر فقط خودت ببینی: `Private`
   - اگر لینک دانلود عمومی می‌خواهی: `Public`
7. روی `Create repository` بزن.

## اگر Git روی کامپیوترت داری

بعد از ساخت ریپو، GitHub چند دستور نشان می‌دهد. از داخل پوشه پروژه این‌ها را بزن:

```text
git remote add origin https://github.com/YOUR-USERNAME/cheraghi-hello-child.git
git branch -M main
git push -u origin main
```

به جای `YOUR-USERNAME` نام کاربری GitHub خودت را بگذار.

## بعد از Push، ZIP را از کجا دانلود کنم؟

ZIP داخل Git و Pull Request نگهداری نمی‌شود، چون بعضی سیستم‌ها خطای `Binary files are not supported` می‌دهند.

راه درست این است که بعد از دانلود پروژه از GitHub، روی کامپیوتر خودت این دستور را بزنی:

```text
./scripts/package-cheraghi-theme.sh
```

بعد فایل زیر ساخته می‌شود:

```text
dist/cheraghi-hello-child.zip
```

همین فایل ساخته‌شده را در وردپرس آپلود کن.

## اگر لینک دانلود مستقیم ZIP می‌خواهی

برای لینک دانلود مستقیم، ZIP را داخل Pull Request نگذار. بهتر است در GitHub از بخش `Releases` استفاده کنی و فایل `cheraghi-hello-child.zip` را آنجا آپلود کنی.

مسیر کلی:

```text
GitHub Repository → Releases → Draft a new release → Attach binaries
```

آنجا فایل ساخته‌شده را آپلود کن:

```text
dist/cheraghi-hello-child.zip
```

## اگر نمی‌توانی با Git کار کنی

راه بدون Git:

1. در GitHub یک ریپو بساز.
2. داخل ریپو روی `Add file` بزن.
3. گزینه `Upload files` را بزن.
4. فایل‌ها و پوشه‌های پروژه را آپلود کن.
5. فایل ZIP را داخل commit آپلود نکن؛ چون ممکن است خطای binary بدهد.
6. بعد روی `Commit changes` بزن.
7. برای ساخت ZIP، پروژه را دانلود کن و `./scripts/package-cheraghi-theme.sh` را اجرا کن، یا ZIP را در GitHub Releases آپلود کن.

## نکته مهم

من بدون دسترسی به اکانت GitHub شما یا یک ریموت آماده، نمی‌توانم از این محیط پروژه را روی GitHub شما منتشر کنم. برای این کار یا باید:

- ریموت GitHub و دسترسی push از قبل روی این محیط تنظیم شده باشد، یا
- شما خودتان ریپو بسازید و فایل‌ها را آپلود کنید، یا
- لینک/دسترسی امنی برای push فراهم شود.
