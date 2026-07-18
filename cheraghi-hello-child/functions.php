<?php
/**
 * Cheraghi Hello Child Theme functions.
 *
 * @package Cheraghi_Hello_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CHERAGHI_CHILD_VERSION', '1.1.0');

add_action('after_setup_theme', function (): void {
    load_child_theme_textdomain('cheraghi-hello-child', get_stylesheet_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);
});

add_action('wp_enqueue_scripts', function (): void {
    wp_enqueue_style(
        'hello-elementor-parent',
        get_template_directory_uri() . '/style.css',
        [],
        wp_get_theme('hello-elementor')->get('Version')
    );

    wp_enqueue_style(
        'cheraghi-child-style',
        get_stylesheet_uri(),
        ['hello-elementor-parent'],
        CHERAGHI_CHILD_VERSION
    );

    wp_enqueue_script(
        'cheraghi-child-main',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        [],
        CHERAGHI_CHILD_VERSION,
        true
    );
}, 20);

add_action('init', function (): void {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
});

add_action('wp_enqueue_scripts', function (): void {
    if (!is_admin()) {
        wp_dequeue_script('wp-embed');
    }
}, 100);

add_filter('script_loader_tag', function (string $tag, string $handle, string $src): string {
    $defer_handles = [
        'cheraghi-child-main',
    ];

    if (in_array($handle, $defer_handles, true)) {
        return '<script src="' . esc_url($src) . '" defer></script>';
    }

    return $tag;
}, 10, 3);

add_filter('excerpt_length', function (): int {
    return 24;
});

add_filter('excerpt_more', function (): string {
    return '…';
});

function cheraghi_get_theme_url(string $key, string $fallback): string {
    $value = get_theme_mod($key, '');

    if (!is_string($value) || '' === trim($value)) {
        return home_url($fallback);
    }

    return $value;
}

add_action('customize_register', function (WP_Customize_Manager $wp_customize): void {
    $wp_customize->add_section('cheraghi_consultation_links', [
        'title'       => 'لینک‌های مشاوره چراغی',
        'description' => 'آدرس دکمه‌های مشاوره و پرداخت را بدون ویرایش کد تنظیم کنید.',
        'priority'    => 35,
    ]);

    $settings = [
        'cheraghi_consultation_landing_url' => [
            'label'    => 'صفحه اصلی مشاوره',
            'fallback' => home_url('/consultation/'),
        ],
        'cheraghi_online_consultation_url' => [
            'label'    => 'لینک مشاوره آنلاین / پرداخت آنلاین',
            'fallback' => home_url('/consultation/online/'),
        ],
        'cheraghi_phone_consultation_url' => [
            'label'    => 'لینک مشاوره تلفنی / پرداخت تلفنی',
            'fallback' => home_url('/consultation/phone/'),
        ],
        'cheraghi_whatsapp_url' => [
            'label'    => 'لینک واتساپ یا پیام‌رسان',
            'fallback' => '',
        ],
    ];

    foreach ($settings as $setting_id => $setting) {
        $wp_customize->add_setting($setting_id, [
            'default'           => $setting['fallback'],
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ]);

        $wp_customize->add_control($setting_id, [
            'type'        => 'url',
            'section'     => 'cheraghi_consultation_links',
            'label'       => $setting['label'],
            'description' => 'مثال: https://cheraghilaw.ir/consultation/online/',
        ]);
    }
});

add_action('wp_body_open', function (): void {
    echo '<a class="cheraghi-skip-link" href="#content">رفتن به محتوای اصلی</a>';
});

/**
 * Shortcode: [cheraghi_consultation_cta label="دریافت مشاوره آنلاین" url="/consultation/"]
 */
add_shortcode('cheraghi_consultation_cta', function (array $atts): string {
    $atts = shortcode_atts([
        'label' => 'دریافت مشاوره آنلاین',
        'url'   => cheraghi_get_theme_url('cheraghi_consultation_landing_url', '/consultation/'),
        'style' => 'primary',
    ], $atts, 'cheraghi_consultation_cta');

    $class = 'cheraghi-cta';
    if ('gold' === $atts['style']) {
        $class .= ' cheraghi-cta--gold';
    }

    return sprintf(
        '<a class="%1$s" href="%2$s" data-consultation-cta="true">%3$s</a>',
        esc_attr($class),
        esc_url($atts['url']),
        esc_html($atts['label'])
    );
});

add_action('wp_footer', function (): void {
    if (is_admin()) {
        return;
    }

    $phone_url = apply_filters(
        'cheraghi_phone_consultation_url',
        cheraghi_get_theme_url('cheraghi_phone_consultation_url', '/consultation/phone/')
    );
    $online_url = apply_filters(
        'cheraghi_online_consultation_url',
        cheraghi_get_theme_url('cheraghi_online_consultation_url', '/consultation/online/')
    );
    ?>
    <div class="cheraghi-sticky-consultation" aria-label="دسترسی سریع مشاوره">
        <a class="cheraghi-cta cheraghi-cta--gold" href="<?php echo esc_url($online_url); ?>">مشاوره آنلاین</a>
        <a class="cheraghi-cta" href="<?php echo esc_url($phone_url); ?>">مشاوره تلفنی</a>
    </div>
    <?php
});


add_action('wp_head', function (): void {
    if (is_admin()) {
        return;
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'LegalService',
        '@id'      => home_url('/#legalservice'),
        'name'     => get_bloginfo('name'),
        'url'      => home_url('/'),
        'areaServed' => [
            '@type' => 'Country',
            'name'  => 'Iran',
        ],
        'availableChannel' => [
            [
                '@type' => 'ServiceChannel',
                'name'  => 'مشاوره آنلاین',
                'url'   => cheraghi_get_theme_url('cheraghi_online_consultation_url', '/consultation/online/'),
            ],
            [
                '@type' => 'ServiceChannel',
                'name'  => 'مشاوره تلفنی',
                'url'   => cheraghi_get_theme_url('cheraghi_phone_consultation_url', '/consultation/phone/'),
            ],
        ],
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}, 30);
