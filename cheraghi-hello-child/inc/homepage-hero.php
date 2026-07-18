<?php
/**
 * Luxury homepage header and hero for Cheraghi Law.
 *
 * Add [cheraghi_home_hero] to an Elementor Shortcode widget on a page
 * using the Elementor Canvas template.
 *
 * @package Cheraghi_Hello_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Return the selected homepage portrait URL.
 */
function cheraghi_get_home_hero_image(): string {
    $custom_image = get_theme_mod('cheraghi_home_hero_image', '');

    if (is_string($custom_image) && '' !== trim($custom_image)) {
        return $custom_image;
    }

    $front_page_id = (int) get_option('page_on_front');
    if ($front_page_id > 0 && has_post_thumbnail($front_page_id)) {
        $featured_image = get_the_post_thumbnail_url($front_page_id, 'full');
        if (is_string($featured_image)) {
            return $featured_image;
        }
    }

    return '';
}

/**
 * Return the real WordPress site logo, never a generated placeholder logo.
 */
function cheraghi_get_home_brand_logo(): string {
    $custom_logo_id = (int) get_theme_mod('custom_logo', 0);

    if ($custom_logo_id > 0) {
        return (string) wp_get_attachment_image(
            $custom_logo_id,
            'full',
            false,
            [
                'class'    => 'cheraghi-luxury-header__logo-image',
                'alt'      => get_bloginfo('name'),
                'loading'  => 'eager',
                'decoding' => 'async',
            ]
        );
    }

    return '';
}

add_action('customize_register', function (WP_Customize_Manager $wp_customize): void {
    $wp_customize->add_section('cheraghi_homepage_hero', [
        'title'       => 'بخش اول صفحه اصلی',
        'description' => 'عکس واقعی دکتر چراغی و متن‌های بخش اول صفحه اصلی را تنظیم کنید. لوگو از «هویت سایت» خوانده می‌شود.',
        'priority'    => 34,
    ]);

    $wp_customize->add_setting('cheraghi_home_hero_image', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize,
        'cheraghi_home_hero_image',
        [
            'label'       => 'عکس واقعی دکتر فرزانه چراغی',
            'description' => 'همان عکس رسمی وکالت را انتخاب کنید. پیشنهاد: WebP عمودی با عرض حداقل ۸۵۰ پیکسل.',
            'section'     => 'cheraghi_homepage_hero',
            'settings'    => 'cheraghi_home_hero_image',
        ]
    ));
});

add_shortcode('cheraghi_home_hero', function (array $atts): string {
    $atts = shortcode_atts([
        'title'            => 'دکتر فرزانه چراغی',
        'credential'       => 'وکیل پایه یک دادگستری | مشاوره و پیگیری تخصصی پرونده‌های حقوقی و کیفری',
        'description'      => 'ارائه خدمات تخصصی حقوقی، مشاوره آنلاین و پیگیری پرونده‌ها با تحلیل دقیق، پاسخ‌گویی شفاف و رویکردی حرفه‌ای.',
        'consultation_url' => cheraghi_get_theme_url('cheraghi_online_consultation_url', '/consultation/'),
        'services_url'     => home_url('/services/'),
        'image'            => cheraghi_get_home_hero_image(),
    ], $atts, 'cheraghi_home_hero');

    $logo_markup = cheraghi_get_home_brand_logo();
    $image_markup = '<div class="cheraghi-home-hero__placeholder" aria-label="عکس دکتر فرزانه چراغی هنوز انتخاب نشده است"><span>عکس رسمی دکتر چراغی</span></div>';

    if (is_string($atts['image']) && '' !== trim($atts['image'])) {
        $image_markup = sprintf(
            '<img class="cheraghi-home-hero__portrait" src="%1$s" alt="دکتر فرزانه چراغی، وکیل پایه یک دادگستری" loading="eager" decoding="async" fetchpriority="high">',
            esc_url($atts['image'])
        );
    }

    $nav_items = [
        ['label' => 'صفحه اصلی', 'url' => home_url('/')],
        ['label' => 'خدمات حقوقی', 'url' => home_url('/services/')],
        ['label' => 'مشاوره آنلاین', 'url' => home_url('/consultation/')],
        ['label' => 'آکادمی حقوقی', 'url' => home_url('/academy/')],
        ['label' => 'مقالات', 'url' => home_url('/blog/')],
        ['label' => 'درباره دکتر چراغی', 'url' => home_url('/about/')],
        ['label' => 'تماس با ما', 'url' => home_url('/contact/')],
    ];

    ob_start();
    ?>
    <div class="cheraghi-luxury-top" dir="rtl">
        <header class="cheraghi-luxury-header" aria-label="سربرگ اصلی سایت">
            <div class="cheraghi-luxury-header__inner">
                <a class="cheraghi-luxury-header__brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="صفحه اصلی دکتر فرزانه چراغی">
                    <?php if ('' !== $logo_markup) : ?>
                        <span class="cheraghi-luxury-header__logo"><?php echo $logo_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    <?php endif; ?>
                    <span class="cheraghi-luxury-header__brand-copy">
                        <strong>دکتر فرزانه چراغی</strong>
                        <small>وکیل پایه یک دادگستری</small>
                    </span>
                </a>

                <nav class="cheraghi-luxury-header__nav" aria-label="منوی اصلی">
                    <?php foreach ($nav_items as $index => $item) : ?>
                        <a class="<?php echo 0 === $index ? 'is-active' : ''; ?>" href="<?php echo esc_url($item['url']); ?>">
                            <?php echo esc_html($item['label']); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <a class="cheraghi-luxury-header__reserve" href="<?php echo esc_url($atts['consultation_url']); ?>" data-consultation-cta="true">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2v3M17 2v3M4 8h16M5 4h14a2 2 0 0 1 2 2v14H3V6a2 2 0 0 1 2-2Zm3 8h3v3H8v-3Z"/></svg>
                    <span>رزرو مشاوره</span>
                </a>

                <details class="cheraghi-luxury-header__mobile-menu">
                    <summary aria-label="باز کردن منوی سایت"><span></span><span></span><span></span></summary>
                    <div class="cheraghi-luxury-header__mobile-panel">
                        <?php foreach ($nav_items as $item) : ?>
                            <a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a>
                        <?php endforeach; ?>
                        <a class="cheraghi-luxury-header__mobile-reserve" href="<?php echo esc_url($atts['consultation_url']); ?>">رزرو مشاوره آنلاین</a>
                    </div>
                </details>
            </div>
        </header>

        <main id="content" class="cheraghi-home-hero" aria-labelledby="cheraghi-home-hero-title">
            <svg class="cheraghi-home-hero__watermark" viewBox="0 0 240 240" aria-hidden="true">
                <path d="M120 30v128M80 62h80M62 72l-28 54h56L62 72Zm116 0-28 54h56l-28-54ZM78 172h84M94 158h52M70 188h100"/>
            </svg>
            <div class="cheraghi-home-hero__glow cheraghi-home-hero__glow--one" aria-hidden="true"></div>
            <div class="cheraghi-home-hero__glow cheraghi-home-hero__glow--two" aria-hidden="true"></div>

            <div class="cheraghi-home-hero__inner">
                <div class="cheraghi-home-hero__grid">
                    <div class="cheraghi-home-hero__visual">
                        <div class="cheraghi-home-hero__portrait-ring" aria-hidden="true"></div>
                        <div class="cheraghi-home-hero__portrait-frame">
                            <?php echo $image_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </div>
                    </div>

                    <div class="cheraghi-home-hero__content">
                        <div class="cheraghi-home-hero__ornament" aria-hidden="true"><span></span><i></i><span></span></div>
                        <h1 id="cheraghi-home-hero-title"><?php echo esc_html($atts['title']); ?></h1>
                        <p class="cheraghi-home-hero__credential"><?php echo esc_html($atts['credential']); ?></p>
                        <div class="cheraghi-home-hero__divider" aria-hidden="true"><span></span><svg viewBox="0 0 24 24"><path d="M12 3v16M7 6h10M5 19h14M8 8l-3 6h6L8 8Zm8 0-3 6h6l-3-6Z"/></svg><span></span></div>
                        <p class="cheraghi-home-hero__description"><?php echo esc_html($atts['description']); ?></p>

                        <div class="cheraghi-home-hero__actions">
                            <a class="cheraghi-home-hero__primary" href="<?php echo esc_url($atts['consultation_url']); ?>" data-consultation-cta="true">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2v3M17 2v3M4 8h16M5 4h14a2 2 0 0 1 2 2v14H3V6a2 2 0 0 1 2-2Zm3 8h3v3H8v-3Z"/></svg>
                                رزرو مشاوره آنلاین
                            </a>
                            <a class="cheraghi-home-hero__secondary" href="<?php echo esc_url($atts['services_url']); ?>">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v16M7 6h10M5 19h14M8 8l-3 6h6L8 8Zm8 0-3 6h6l-3-6Z"/></svg>
                                مشاهده خدمات حقوقی
                            </a>
                        </div>
                    </div>
                </div>

                <section class="cheraghi-home-hero__stats" aria-label="سوابق و آمار حرفه‌ای">
                    <article class="cheraghi-home-hero__stat">
                        <svg viewBox="0 0 48 48" aria-hidden="true"><path d="M13 21a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm22 0a7 7 0 1 0 0-14 7 7 0 0 0 0 0 14ZM24 25a8 8 0 1 0 0-16 8 8 0 0 0 0 0 16ZM4 41v-4c0-7 5-11 11-11M44 41v-4c0-7-5-11-11-11M11 41v-5c0-7 5-11 13-11s13 4 13 11v5H11Z"/></svg>
                        <div><strong>+۱۳۰۰</strong><span>مشاوره حقوقی</span></div>
                    </article>
                    <article class="cheraghi-home-hero__stat">
                        <svg viewBox="0 0 48 48" aria-hidden="true"><path d="M7 17h34v25H7V17Zm9 0v-5h16v5M4 25h40M20 25v5h8v-5"/></svg>
                        <div><strong>بیش از ۵ سال</strong><span>سابقه فعالیت حرفه‌ای</span></div>
                    </article>
                    <article class="cheraghi-home-hero__stat">
                        <svg viewBox="0 0 48 48" aria-hidden="true"><path d="M24 5 8 12v11c0 10 7 17 16 21 9-4 16-11 16-21V12L24 5Zm0 8v23M16 18h16M14 36h20"/></svg>
                        <div><strong>عضو کانون وکلا</strong><span>کانون وکلای دادگستری همدان</span></div>
                    </article>
                    <article class="cheraghi-home-hero__stat">
                        <svg viewBox="0 0 48 48" aria-hidden="true"><path d="m4 16 20-9 20 9-20 9L4 16Zm8 6v12c7 5 17 5 24 0V22M42 18v16"/></svg>
                        <div><strong>مدرس دانشگاه</strong><span>فعال در آموزش حقوق</span></div>
                    </article>
                </section>
            </div>
        </main>
    </div>
    <?php

    return (string) ob_get_clean();
});
