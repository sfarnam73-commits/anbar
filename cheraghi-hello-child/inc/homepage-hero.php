<?php
/**
 * Homepage hero section for Cheraghi Law.
 *
 * Add the shortcode [cheraghi_home_hero] to an Elementor Shortcode widget.
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

add_action('customize_register', function (WP_Customize_Manager $wp_customize): void {
    $wp_customize->add_section('cheraghi_homepage_hero', [
        'title'       => 'تصویر بخش اول صفحه اصلی',
        'description' => 'عکس رسمی دکتر فرزانه چراغی را برای بخش اول صفحه اصلی انتخاب کنید.',
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
            'label'       => 'عکس رسمی دکتر چراغی',
            'description' => 'پیشنهاد: تصویر عمودی WebP با عرض حداقل ۹۰۰ پیکسل.',
            'section'     => 'cheraghi_homepage_hero',
            'settings'    => 'cheraghi_home_hero_image',
        ]
    ));
});

add_shortcode('cheraghi_home_hero', function (array $atts): string {
    $atts = shortcode_atts([
        'title'            => 'دکتر فرزانه چراغی',
        'credential'       => 'وکیل پایه یک دادگستری و دکترای حقوق بین‌الملل',
        'description'      => 'ارائه خدمات تخصصی حقوقی، مشاوره آنلاین و پیگیری پرونده‌ها با تحلیل دقیق، پاسخ‌گویی شفاف و رویکردی حرفه‌ای.',
        'consultation_url' => cheraghi_get_theme_url('cheraghi_online_consultation_url', '/consultation/online/'),
        'services_url'     => home_url('/services/'),
        'image'            => cheraghi_get_home_hero_image(),
    ], $atts, 'cheraghi_home_hero');

    $image_markup = '<div class="cheraghi-home-hero__placeholder" aria-label="محل قرارگیری عکس رسمی دکتر فرزانه چراغی"><span>FC</span></div>';

    if (is_string($atts['image']) && '' !== trim($atts['image'])) {
        $image_markup = sprintf(
            '<img class="cheraghi-home-hero__portrait" src="%1$s" alt="دکتر فرزانه چراغی، وکیل پایه یک دادگستری" loading="eager" decoding="async" fetchpriority="high">',
            esc_url($atts['image'])
        );
    }

    ob_start();
    ?>
    <section class="cheraghi-home-hero" aria-labelledby="cheraghi-home-hero-title">
        <div class="cheraghi-home-hero__ornament cheraghi-home-hero__ornament--one" aria-hidden="true"></div>
        <div class="cheraghi-home-hero__ornament cheraghi-home-hero__ornament--two" aria-hidden="true"></div>

        <div class="cheraghi-container cheraghi-home-hero__grid">
            <div class="cheraghi-home-hero__content">
                <span class="cheraghi-home-hero__eyebrow">وکیل پایه یک دادگستری</span>
                <h1 id="cheraghi-home-hero-title"><?php echo esc_html($atts['title']); ?></h1>
                <p class="cheraghi-home-hero__credential"><?php echo esc_html($atts['credential']); ?></p>
                <p class="cheraghi-home-hero__description"><?php echo esc_html($atts['description']); ?></p>

                <div class="cheraghi-home-hero__actions">
                    <a class="cheraghi-cta cheraghi-cta--gold" href="<?php echo esc_url($atts['consultation_url']); ?>" data-consultation-cta="true">
                        دریافت مشاوره آنلاین
                    </a>
                    <a class="cheraghi-home-hero__secondary" href="<?php echo esc_url($atts['services_url']); ?>">
                        مشاهده خدمات حقوقی
                    </a>
                </div>

                <div class="cheraghi-home-hero__stats" aria-label="سوابق و آمار حرفه‌ای">
                    <div class="cheraghi-home-hero__stat"><strong>+۱۳۰۰</strong><span>مشاوره حقوقی</span></div>
                    <div class="cheraghi-home-hero__stat"><strong>+۵ سال</strong><span>سابقه فعالیت</span></div>
                    <div class="cheraghi-home-hero__stat"><strong>عضو کانون</strong><span>وکلای همدان</span></div>
                    <div class="cheraghi-home-hero__stat"><strong>مدرس</strong><span>دانشگاه</span></div>
                </div>
            </div>

            <div class="cheraghi-home-hero__visual">
                <div class="cheraghi-home-hero__frame">
                    <?php echo $image_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <div class="cheraghi-home-hero__seal" aria-hidden="true">
                        <span>FC</span>
                        <small>CHERAGHI LAW</small>
                    </div>
                    <div class="cheraghi-home-hero__badge">
                        <strong>دکترای حقوق بین‌الملل</strong>
                        <span>تحلیل عمیق و پیگیری حرفه‌ای پرونده</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php

    return (string) ob_get_clean();
});
