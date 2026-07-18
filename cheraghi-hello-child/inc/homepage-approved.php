<?php
/**
 * Approved neon homepage header and hero for Cheraghi Law.
 *
 * This file intentionally overrides the first homepage shortcode with the
 * design approved in the conversation: navy, gold, neon portrait arch,
 * premium Persian typography, real WordPress logo with an FC crest fallback,
 * and a unified professional statistics bar.
 *
 * @package Cheraghi_Hello_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Return the custom WordPress logo or the approved FC crest fallback.
 */
function cheraghi_approved_home_logo(): string {
    $logo = function_exists('cheraghi_get_home_brand_logo')
        ? cheraghi_get_home_brand_logo()
        : '';

    if ('' !== trim($logo)) {
        return $logo;
    }

    return sprintf(
        '<img class="cheraghi-approved-header__logo-image" src="%1$s" alt="لوگوی دکتر فرزانه چراغی" width="116" height="116" loading="eager" decoding="async">',
        esc_url(get_stylesheet_directory_uri() . '/assets/images/cheraghi-fc-crest.svg')
    );
}

remove_shortcode('cheraghi_home_hero');

add_shortcode('cheraghi_home_hero', function (array $atts): string {
    $atts = shortcode_atts([
        'title'            => 'دکتر فرزانه چراغی',
        'credential'       => 'وکیل پایه یک دادگستری',
        'description'      => 'ارائه خدمات حقوقی تخصصی، مشاوره آنلاین و پیگیری پرونده‌ها با تعهد، دقت و تجربه‌ای که به نتیجه می‌رسد.',
        'consultation_url' => cheraghi_get_theme_url('cheraghi_online_consultation_url', '/consultation/'),
        'services_url'     => home_url('/services/'),
        'image'            => function_exists('cheraghi_get_home_hero_image') ? cheraghi_get_home_hero_image() : '',
    ], $atts, 'cheraghi_home_hero');

    $logo_markup = cheraghi_approved_home_logo();

    $image_markup = '<div class="cheraghi-approved-hero__placeholder"><span>عکس رسمی دکتر چراغی</span></div>';
    if (is_string($atts['image']) && '' !== trim($atts['image'])) {
        $image_markup = sprintf(
            '<img class="cheraghi-approved-hero__portrait" src="%1$s" alt="دکتر فرزانه چراغی، وکیل پایه یک دادگستری" loading="eager" decoding="async" fetchpriority="high">',
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
    <div class="cheraghi-approved-page" dir="rtl">
        <header class="cheraghi-approved-header" aria-label="سربرگ اصلی سایت">
            <div class="cheraghi-approved-header__inner">
                <a class="cheraghi-approved-header__brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="صفحه اصلی دکتر فرزانه چراغی">
                    <?php echo $logo_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </a>

                <nav class="cheraghi-approved-header__nav" aria-label="منوی اصلی">
                    <?php foreach ($nav_items as $index => $item) : ?>
                        <a class="<?php echo 0 === $index ? 'is-active' : ''; ?>" href="<?php echo esc_url($item['url']); ?>">
                            <?php echo esc_html($item['label']); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <a class="cheraghi-approved-header__reserve" href="<?php echo esc_url($atts['consultation_url']); ?>" data-consultation-cta="true">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2v3M17 2v3M4 8h16M5 4h14a2 2 0 0 1 2 2v14H3V6a2 2 0 0 1 2-2Zm3 8h3v3H8v-3Z"/></svg>
                    <span>رزرو مشاوره</span>
                </a>

                <details class="cheraghi-approved-header__mobile-menu">
                    <summary aria-label="باز کردن منوی سایت"><span></span><span></span><span></span></summary>
                    <div class="cheraghi-approved-header__mobile-panel">
                        <?php foreach ($nav_items as $item) : ?>
                            <a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a>
                        <?php endforeach; ?>
                        <a class="cheraghi-approved-header__mobile-reserve" href="<?php echo esc_url($atts['consultation_url']); ?>">رزرو مشاوره آنلاین</a>
                    </div>
                </details>
            </div>
        </header>

        <main id="content" class="cheraghi-approved-hero" aria-labelledby="cheraghi-approved-hero-title">
            <div class="cheraghi-approved-hero__light cheraghi-approved-hero__light--top" aria-hidden="true"></div>
            <div class="cheraghi-approved-hero__light cheraghi-approved-hero__light--bottom" aria-hidden="true"></div>
            <svg class="cheraghi-approved-hero__watermark" viewBox="0 0 260 260" aria-hidden="true">
                <path d="M130 34v132M84 66h92M67 78 36 136h62L67 78Zm126 0-31 58h62l-31-58ZM84 182h92M101 166h58M72 202h116"/>
            </svg>

            <div class="cheraghi-approved-hero__inner">
                <div class="cheraghi-approved-hero__grid">
                    <div class="cheraghi-approved-hero__visual">
                        <div class="cheraghi-approved-hero__neon cheraghi-approved-hero__neon--outer" aria-hidden="true"></div>
                        <div class="cheraghi-approved-hero__neon cheraghi-approved-hero__neon--inner" aria-hidden="true"></div>
                        <div class="cheraghi-approved-hero__portrait-frame">
                            <span class="cheraghi-approved-hero__library" aria-hidden="true"></span>
                            <?php echo $image_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            <span class="cheraghi-approved-hero__portrait-shade" aria-hidden="true"></span>
                            <span class="cheraghi-approved-hero__spark cheraghi-approved-hero__spark--one" aria-hidden="true"></span>
                            <span class="cheraghi-approved-hero__spark cheraghi-approved-hero__spark--two" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="cheraghi-approved-hero__content">
                        <div class="cheraghi-approved-hero__ornament" aria-hidden="true"><span></span><i></i><span></span></div>
                        <h1 id="cheraghi-approved-hero-title"><?php echo esc_html($atts['title']); ?></h1>
                        <p class="cheraghi-approved-hero__credential"><b>✧</b><?php echo esc_html($atts['credential']); ?><b>✧</b></p>
                        <div class="cheraghi-approved-hero__divider" aria-hidden="true"><span></span><i></i><span></span></div>
                        <p class="cheraghi-approved-hero__description"><?php echo esc_html($atts['description']); ?></p>

                        <div class="cheraghi-approved-hero__actions">
                            <a class="cheraghi-approved-hero__primary" href="<?php echo esc_url($atts['consultation_url']); ?>" data-consultation-cta="true">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 18 4 21v-5a8 8 0 1 1 4 2Zm1-8h6M9 13h4"/></svg>
                                دریافت مشاوره آنلاین
                            </a>
                            <a class="cheraghi-approved-hero__secondary" href="<?php echo esc_url($atts['services_url']); ?>">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v16M7 6h10M5 19h14M8 8l-3 6h6L8 8Zm8 0-3 6h6l-3-6Z"/></svg>
                                مشاهده خدمات حقوقی
                            </a>
                        </div>
                    </div>
                </div>

                <section class="cheraghi-approved-hero__stats" aria-label="سوابق و آمار حرفه‌ای">
                    <article class="cheraghi-approved-hero__stat">
                        <svg viewBox="0 0 48 48" aria-hidden="true"><path d="M13 21a7 7 0 1 0 0-14 7 7 0 0 0 0 14Zm22 0a7 7 0 1 0 0-14 7 7 0 0 0 0 0 14ZM24 25a8 8 0 1 0 0-16 8 8 0 0 0 0 0 16ZM4 41v-4c0-7 5-11 11-11M44 41v-4c0-7-5-11-11-11M11 41v-5c0-7 5-11 13-11s13 4 13 11v5H11Z"/></svg>
                        <div><strong>+۱۳۰۰</strong><span>مشاوره حقوقی</span></div>
                    </article>
                    <article class="cheraghi-approved-hero__stat">
                        <svg viewBox="0 0 48 48" aria-hidden="true"><path d="M24 5 8 12v11c0 10 7 17 16 21 9-4 16-11 16-21V12L24 5Zm0 8v23M16 18h16M14 36h20"/></svg>
                        <div><strong>بیش از ۵ سال</strong><span>سابقه فعالیت حرفه‌ای</span></div>
                    </article>
                    <article class="cheraghi-approved-hero__stat">
                        <svg viewBox="0 0 48 48" aria-hidden="true"><path d="M24 7 7 15l17 8 17-8-17-8ZM12 21v12c7 5 17 5 24 0V21M41 18v17"/></svg>
                        <div><strong>عضو کانون وکلا</strong><span>دادگستری همدان</span></div>
                    </article>
                    <article class="cheraghi-approved-hero__stat">
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
