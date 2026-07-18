# Cheraghi Hello Child

A lightweight Hello Elementor child theme prepared for `cheraghilaw.ir`.

## Install

1. Install and activate **Hello Elementor**.
2. Upload this folder to `wp-content/themes/cheraghi-hello-child`.
3. Activate **Cheraghi Hello Child**.
4. Keep Elementor Pro active.
5. Set consultation/payment URLs from **Appearance → Customize → لینک‌های مشاوره چراغی**.

## Approved Luxury Homepage Header + Hero

The approved navy-and-gold homepage top section is implemented as this shortcode:

```text
[cheraghi_home_hero]
```

### Exact Elementor setup

1. Open the homepage with Elementor.
2. Set the page layout to **Elementor Canvas** so another header is not displayed above the approved header.
3. Add a **Shortcode** widget as the first item.
4. Paste `[cheraghi_home_hero]`.
5. Do not place the design as one poster image. The heading, menu, buttons and statistics are real HTML and remain clickable, responsive and SEO-friendly.

### Set the real portrait

```text
Appearance → Customize → بخش اول صفحه اصلی → عکس واقعی دکتر فرزانه چراغی
```

Use the supplied formal lawyer portrait. A vertical WebP image around 850×1280 works well.

### Set the real logo

The header never uses a generated or substitute logo. It reads the actual WordPress Site Logo from:

```text
Appearance → Customize → Site Identity → Logo
```

After the user's real logo is selected there, the same logo appears in the approved header automatically.

### Included in the approved top section

- Full navy-and-gold responsive header
- Seven-item Persian navigation
- Consultation reservation button
- Real WordPress logo support
- Real portrait support
- Broad legal positioning instead of limiting the hero to international law
- Online consultation and legal services buttons
- 1,300+ consultations
- 5+ years of experience
- Hamadan Bar membership
- University teaching experience
- Desktop, tablet and mobile layouts

## Consultation URL Settings

```text
Appearance → Customize → لینک‌های مشاوره چراغی
```

Recommended URLs:

- `/consultation/` for the consultation landing page.
- `/consultation/online/` for online consultation and payment.
- `/consultation/phone/` for phone consultation and payment.
- `/services/` for the legal services hub.
- `/academy/` for the legal academy.
- `/blog/` for articles.
- `/about/` for About Dr. Cheraghi.
- `/contact/` for contact information.

## Consultation CTA Shortcode

```text
[cheraghi_consultation_cta label="دریافت مشاوره آنلاین" url="/consultation/online/" style="gold"]
```

## Theme Builder Templates To Create Later

1. Footer
2. Single Page
3. Single Post
4. Archive
5. Search Results
6. 404
7. Consultation Landing Page
8. Legal Service Page

The homepage header is already included inside `[cheraghi_home_hero]`, so do not add a second Elementor header to the homepage.

## Performance Notes

- Use local Persian fonts.
- Keep Enfold disabled after migration.
- Avoid heavy sliders.
- Compress images and prefer WebP/AVIF.
- Use a compatible cache plugin.

## Launch Checklist

See `docs/launch-checklist.md` before activating the theme on production.
