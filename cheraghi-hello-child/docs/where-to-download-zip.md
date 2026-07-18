# فایل `cheraghi-hello-child.zip` را از کجا دانلود کنم؟

## جواب کوتاه

فایل ZIP نباید داخل Pull Request باشد، چون بعضی سیستم‌ها خطای `Binary files are not supported` می‌دهند.

باید ZIP را روی کامپیوتر/سرور خودت بسازی. دستور ساخت ZIP این است:

```text
./scripts/package-cheraghi-theme.sh
```

بعد از اجرای دستور، فایل آماده نصب اینجا ساخته می‌شود:

```text
dist/cheraghi-hello-child.zip
```

همین فایل ساخته‌شده را باید در وردپرس آپلود کنی.

---

## اگر فایل‌ها را از GitHub یا Pull Request می‌بینی

از داخل Pull Request فایل ZIP را دانلود نکن، چون ZIP باینری است و ممکن است سیستم PR خطای زیر بدهد:

```text
Binary files are not supported
```

راه درست این است:

1. پروژه را از GitHub دانلود کن.
2. آن را Extract کن.
3. داخل پوشه پروژه دستور زیر را اجرا کن:

```text
./scripts/package-cheraghi-theme.sh
```

4. بعد فایل زیر ساخته می‌شود:

```text
dist/cheraghi-hello-child.zip
```

5. همین فایل ساخته‌شده را در وردپرس آپلود کن.

---

## اگر کل پروژه را دانلود کرده‌ای

اگر کل پروژه را به‌صورت ZIP دانلود کرده‌ای، اول آن را Extract کن.

بعد داخل پوشه پروژه این دستور را بزن:

```text
./scripts/package-cheraghi-theme.sh
```

بعد فایل زیر ساخته می‌شود:

```text
dist/cheraghi-hello-child.zip
```

---

## اگر پوشه `dist` را ندیدی

اگر پوشه `dist` یا فایل ZIP را ندیدی، باید ZIP را بسازی.

اگر روی کامپیوتر یا سرور دسترسی ترمینال داری، از ریشه پروژه این دستور را بزن:

```text
./scripts/package-cheraghi-theme.sh
```

بعد فایل ZIP اینجا ساخته می‌شود:

```text
dist/cheraghi-hello-child.zip
```

---

## بعد از دانلود ZIP چه کنم؟

برو وردپرس:

```text
نمایش ← پوسته‌ها ← افزودن پوسته تازه ← بارگذاری پوسته
```

بعد همین فایل را انتخاب کن:

```text
cheraghi-hello-child.zip
```

بعد بزن:

```text
هم‌اکنون نصب کن
```
