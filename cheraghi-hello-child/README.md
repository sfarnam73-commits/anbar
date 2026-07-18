# Cheraghi Hello Child

A lightweight Hello Elementor child theme prepared for migrating `cheraghilaw.ir` from Enfold to a faster Elementor-based setup.

## Install

1. Install and activate **Hello Elementor**.
2. Upload this folder to `wp-content/themes/cheraghi-hello-child`.
3. Activate **Cheraghi Hello Child**.
4. Keep Elementor Pro active.
5. Rebuild templates in Elementor Theme Builder.
6. Set consultation/payment URLs from **Appearance → Customize → لینک‌های مشاوره چراغی**.

## Publish To GitHub

If you cannot find a download button, publish the project to GitHub and download `dist/cheraghi-hello-child.zip` from there. See `docs/publish-to-github.md`.

## Where To Download The ZIP

The ready-to-upload WordPress theme ZIP is generated locally with `./scripts/package-cheraghi-theme.sh` into `dist/cheraghi-hello-child.zip`. The ZIP is intentionally not committed because PR systems may reject binary files. If you do not know how to create it, read `docs/where-to-download-zip.md`.

## Super Simple Upload Guide

If you are not technical and only want to know exactly where to put the file, read `docs/super-simple-upload-guide.md`.

## Where To Upload The Files

Upload only the `cheraghi-hello-child` folder to:

```text
wp-content/themes/cheraghi-hello-child/
```

For exact WordPress, File Manager, FTP, and preview-link instructions, see `docs/where-to-put-files.md`.

## Visual Concept Preview

Open `preview/homepage-concept.html` in a browser to review the proposed modern homepage direction before rebuilding it in Elementor Pro. After uploading the theme folder to the live site, the preview URL will be `https://cheraghilaw.ir/wp-content/themes/cheraghi-hello-child/preview/homepage-concept.html`.

## Consultation URL Settings

After activation, set payment and consultation URLs from:

```text
Appearance → Customize → لینک‌های مشاوره چراغی
```

This keeps online/phone consultation buttons editable without touching PHP files.

## Consultation CTAs

Use this shortcode anywhere Elementor accepts shortcodes:

```text
[cheraghi_consultation_cta label="دریافت مشاوره آنلاین" url="/consultation/online/" style="gold"]
```

Recommended URLs:

- `/consultation/online/` for online consultation and payment.
- `/consultation/phone/` for phone consultation and payment.
- `/consultation/` for the landing page that explains all consultation types.

## Elementor Theme Builder Templates

Create these templates in Elementor Pro:

1. Header
2. Footer
3. Single Page
4. Single Post
5. Archive
6. Search Results
7. 404
8. Consultation Landing Page
9. Legal Service Page

## Performance Notes

- Use local fonts instead of remote font providers.
- Keep Enfold disabled after migration.
- Avoid large sliders and heavy animation stacks.
- Compress images and prefer WebP/AVIF where the server supports it.
- Use a cache plugin compatible with the hosting environment.

## Launch Checklist

See `docs/launch-checklist.md` before activating the new theme on the production site.
