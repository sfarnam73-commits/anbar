<?php
if (!defined('ABSPATH')) exit;

define('BJ_CHILD_DIR', get_stylesheet_directory());
define('BJ_CHILD_URI', get_stylesheet_directory_uri());
define('BJ_VERSION', '1.0.0');

// =========================================
// ENQUEUE ASSETS
// =========================================
add_action('wp_enqueue_scripts', 'bj_child_assets', 20);
function bj_child_assets() {
    wp_enqueue_style('vazirmatn', 'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap', [], null);
    wp_enqueue_style('bj-main', BJ_CHILD_URI . '/assets/css/bazarjooje.css', ['vazirmatn'], BJ_VERSION);

    wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js', [], '4.4.7', true);
    wp_enqueue_script('bj-main', BJ_CHILD_URI . '/assets/js/bazarjooje.js', ['jquery', 'chartjs'], BJ_VERSION, true);
    wp_localize_script('bj-main', 'bjSite', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('bj_front_nonce'),
        'homeurl' => home_url('/'),
    ]);
}

// =========================================
// REGISTER MENUS
// =========================================
add_action('after_setup_theme', 'bj_child_setup');
function bj_child_setup() {
    register_nav_menus([
        'bj_primary'    => 'منوی اصلی بازار جوجه',
        'bj_footer'     => 'منوی فوتر',
        'bj_quick'      => 'دسترسی سریع فوتر',
        'bj_articles'   => 'مقالات پرطرفدار فوتر',
    ]);

    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
}

// =========================================
// REGISTER SIDEBARS
// =========================================
add_action('widgets_init', 'bj_child_widgets');
function bj_child_widgets() {
    register_sidebar([
        'name'          => 'سایدبار اخبار',
        'id'            => 'bj-news-sidebar',
        'before_widget' => '<div class="sb-box">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="sb-head">',
        'after_title'   => '</div>',
    ]);
    register_sidebar([
        'name'          => 'سایدبار فوتر',
        'id'            => 'bj-footer-sidebar',
        'before_widget' => '<div class="footer-col">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ]);
}

// =========================================
// REGISTER POST CATEGORIES
// =========================================
add_action('init', 'bj_custom_taxonomies');
function bj_custom_taxonomies() {
    $news_cats = [
        'market-news'    => 'اخبار بازار',
        'health-news'    => 'بهداشت طیور',
        'export-news'    => 'صادرات و واردات',
        'tech-news'      => 'فناوری و نوآوری',
        'feed-news'      => 'نهاده‌های دامی',
        'regulation-news'=> 'مقررات و سیاست‌ها',
    ];
    foreach ($news_cats as $slug => $name) {
        if (!term_exists($slug, 'category')) {
            wp_insert_term($name, 'category', ['slug' => $slug]);
        }
    }
}

// =========================================
// CUSTOM WALKER FOR MAIN NAV
// =========================================
class BJ_Nav_Walker extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<div class="dropdown">';
    }
    public function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</div>';
    }
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $has_children = in_array('menu-item-has-children', $item->classes ?? []);
        if ($depth === 0) {
            $output .= '<li class="nav-item' . ($item->current ? ' active' : '') . '">';
            $output .= '<a href="' . esc_url($item->url) . '">' . esc_html($item->title);
            if ($has_children) $output .= ' ▾';
            $output .= '</a>';
        } else {
            $output .= '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
        }
    }
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        if ($depth === 0) $output .= '</li>';
    }
}

// =========================================
// POPULAR POSTS WIDGET
// =========================================
class BJ_Popular_Posts_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct('bj_popular', 'پربازدیدترین‌ها (بازار جوجه)');
    }

    public function widget($args, $instance) {
        $count = $instance['count'] ?? 5;
        $posts = get_posts([
            'posts_per_page' => $count,
            'meta_key'       => 'post_views_count',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
        ]);

        if (empty($posts)) {
            $posts = get_posts(['posts_per_page' => $count, 'orderby' => 'comment_count']);
        }

        echo $args['before_widget'];
        echo $args['before_title'] . '🔥 پربازدیدترین‌ها' . $args['after_title'];
        echo '<div class="sb-body">';
        $i = 1;
        foreach ($posts as $post) {
            $views = get_post_meta($post->ID, 'post_views_count', true) ?: '0';
            echo '<div class="pop-item">';
            echo '<span class="pop-num">' . bj_to_persian($i) . '</span>';
            echo '<div><div class="pop-txt"><a href="' . get_permalink($post) . '">' . esc_html($post->post_title) . '</a></div>';
            echo '<div class="pop-date">' . bj_to_persian(number_format($views)) . ' بازدید</div></div>';
            echo '</div>';
            $i++;
        }
        echo '</div>';
        echo $args['after_widget'];
    }

    public function form($instance) {
        $count = $instance['count'] ?? 5;
        echo '<p><label>تعداد: <input type="number" name="' . $this->get_field_name('count') . '" value="' . esc_attr($count) . '" min="1" max="10"></label></p>';
    }

    public function update($new_instance, $old_instance) {
        return ['count' => intval($new_instance['count'])];
    }
}
add_action('widgets_init', function() {
    register_widget('BJ_Popular_Posts_Widget');
});

// =========================================
// TRACK POST VIEWS
// =========================================
add_action('wp_head', 'bj_track_post_views');
function bj_track_post_views() {
    if (!is_single()) return;
    if (is_user_logged_in() && current_user_can('manage_options')) return;

    global $post;
    $count = intval(get_post_meta($post->ID, 'post_views_count', true));
    update_post_meta($post->ID, 'post_views_count', $count + 1);
}

// =========================================
// PERSIAN NUMBER HELPER
// =========================================
function bj_to_persian($str) {
    $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    $latin = ['0','1','2','3','4','5','6','7','8','9'];
    return str_replace($latin, $persian, (string) $str);
}

// =========================================
// JALALI DATE HELPER
// =========================================
function bj_jalali_date($format = '', $timestamp = null) {
    if (!$timestamp) $timestamp = time();
    $d = date('d', $timestamp);
    $m = date('m', $timestamp);
    $y = date('Y', $timestamp);

    $g_d_m = [0,31,59,90,120,151,181,212,243,273,304,334];
    $gy2 = ($m > 2) ? ($y + 1) : $y;
    $days = 355666 + (365*$y) + intval(($gy2+3)/4) - intval(($gy2+99)/100) + intval(($gy2+399)/400) + $d + $g_d_m[$m-1];
    $jy = -1595 + (33 * intval($days/12053));
    $days %= 12053;
    $jy += 4 * intval($days/1461);
    $days %= 1461;
    if ($days > 365) {
        $jy += intval(($days-1)/365);
        $days = ($days-1)%365;
    }
    if ($days < 186) {
        $jm = 1 + intval($days/31);
        $jd = 1 + ($days%31);
    } else {
        $jm = 7 + intval(($days-186)/30);
        $jd = 1 + (($days-186)%30);
    }

    $months = ['','فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
    $weekdays = ['یکشنبه','دوشنبه','سه‌شنبه','چهارشنبه','پنجشنبه','جمعه','شنبه'];
    $dayOfWeek = $weekdays[date('w', $timestamp)];

    if ($format === 'short') {
        return bj_to_persian($jd) . ' ' . $months[$jm];
    }
    return $dayOfWeek . ' ' . bj_to_persian($jd) . ' ' . $months[$jm] . ' ' . bj_to_persian($jy);
}

// =========================================
// CUSTOM EXCERPT LENGTH
// =========================================
add_filter('excerpt_length', function() { return 25; });
add_filter('excerpt_more', function() { return '...'; });

// =========================================
// CONTACT FORM AJAX HANDLER
// =========================================
add_action('wp_ajax_bj_contact_submit', 'bj_contact_submit');
add_action('wp_ajax_nopriv_bj_contact_submit', 'bj_contact_submit');
function bj_contact_submit() {
    if (!wp_verify_nonce($_POST['bj_contact_nonce'] ?? '', 'bj_contact_form')) {
        wp_send_json_error('nonce_fail');
    }

    $name    = sanitize_text_field($_POST['bj_name'] ?? '');
    $email   = sanitize_email($_POST['bj_email'] ?? '');
    $phone   = sanitize_text_field($_POST['bj_phone'] ?? '');
    $subject = sanitize_text_field($_POST['bj_subject'] ?? '');
    $message = sanitize_textarea_field($_POST['bj_message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error('missing_fields');
    }

    $subjects = [
        'general' => 'سوال عمومی',
        'prices'  => 'استعلام قیمت',
        'cooperation' => 'پیشنهاد همکاری',
        'ads'     => 'تبلیغات',
        'bug'     => 'گزارش مشکل',
        'other'   => 'سایر',
    ];
    $subject_label = $subjects[$subject] ?? $subject;

    $to = get_option('admin_email');
    $mail_subject = 'پیام جدید از سایت بازار جوجه — ' . $subject_label;
    $body  = "نام: {$name}\n";
    $body .= "ایمیل: {$email}\n";
    $body .= "تلفن: {$phone}\n";
    $body .= "موضوع: {$subject_label}\n\n";
    $body .= "پیام:\n{$message}";

    $headers = ['Content-Type: text/plain; charset=UTF-8', "Reply-To: {$name} <{$email}>"];

    $sent = wp_mail($to, $mail_subject, $body, $headers);
    if ($sent) {
        wp_send_json_success();
    } else {
        wp_send_json_error('mail_fail');
    }
}

// =========================================
// AUTO-CREATE PRICE/TOOL PAGES
// =========================================
add_action('admin_init', 'bj_create_default_pages');
function bj_create_default_pages() {
    if (get_option('bj_default_pages_created')) return;

    $pages = [
        ['title' => 'قیمت‌های روزانه بازار', 'slug' => 'prices', 'template' => 'page-prices.php', 'parent' => ''],
        ['title' => 'جوجه یکروزه', 'slug' => 'chick', 'template' => 'page-prices-chick.php', 'parent' => 'prices'],
        ['title' => 'مرغ زنده', 'slug' => 'chicken', 'template' => 'page-prices-chicken.php', 'parent' => 'prices'],
        ['title' => 'تخم مرغ', 'slug' => 'egg', 'template' => 'page-prices-egg.php', 'parent' => 'prices'],
        ['title' => 'نهاده‌های دامی', 'slug' => 'feed', 'template' => 'page-prices-feed.php', 'parent' => 'prices'],
        ['title' => 'مقایسه قیمت استان‌ها', 'slug' => 'province-prices', 'template' => 'page-province-prices.php', 'parent' => ''],
        ['title' => 'تقویم مرغدار', 'slug' => 'calendar', 'template' => 'page-calendar.php', 'parent' => ''],
        ['title' => 'دایرکتوری مرغداری', 'slug' => 'directory', 'template' => 'page-directory.php', 'parent' => ''],
    ];

    $slug_to_id = [];
    $created = [];

    foreach ($pages as $p) {
        $existing = get_page_by_path($p['slug']);
        if ($existing) {
            $slug_to_id[$p['slug']] = $existing->ID;
            continue;
        }

        $parent_id = $p['parent'] ? ($slug_to_id[$p['parent']] ?? 0) : 0;

        $id = wp_insert_post([
            'post_title'  => $p['title'],
            'post_name'   => $p['slug'],
            'post_type'   => 'page',
            'post_status' => 'publish',
            'post_parent' => $parent_id,
        ]);

        if ($id && !is_wp_error($id)) {
            update_post_meta($id, '_wp_page_template', $p['template']);
            $slug_to_id[$p['slug']] = $id;
            $created[] = $p['title'];
        }
    }

    update_option('bj_default_pages_created', 1);
    if ($created) update_option('bj_default_pages_created_list', $created);
    flush_rewrite_rules();
}

add_action('admin_notices', 'bj_default_pages_notice');
function bj_default_pages_notice() {
    $created = get_option('bj_default_pages_created_list');
    if (empty($created)) return;
    echo '<div class="notice notice-success is-dismissible"><p>✅ صفحات بازار جوجه به‌صورت خودکار ساخته شدند: ' . esc_html(implode('، ', $created)) . '</p></div>';
    delete_option('bj_default_pages_created_list');
}

// =========================================
// DISABLE JANNAH STYLES ON FRONT PAGE
// =========================================
add_action('wp_enqueue_scripts', 'bj_manage_parent_styles', 100);
function bj_manage_parent_styles() {
    if (is_front_page()) {
        wp_dequeue_style('flavor-flavor-style');
    }
}
