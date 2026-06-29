<?php
/**
 * Mobile 8 Store - Child Theme Functions
 * Website: mobile8.ir
 * Designer: Sina Farnam - https://sinafarnam.ir
 */

// Enqueue child theme styles with cache-busting timestamp
function dina_child_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'dina-style' ), '1.5.0.' . time() );
}
add_action( 'wp_enqueue_scripts', 'dina_child_enqueue_styles', 10010 );

// Early banner hide - before anything renders
function mobile8_early_hide_banner() {
    echo '<style id="m8-early-hide">.dina-head-img-msg-con,.dina-head-msg,#dinaHeadMsg,aside.dina-head-img-msg-con,.dina-apps-icon{display:none!important;height:0!important;overflow:hidden!important;}</style>';
}
add_action( 'wp_head', 'mobile8_early_hide_banner', 0 );

// Override Redux at DATABASE level — intercept get_option('di_data')
function mobile8_filter_redux_options( $value ) {
    if ( ! is_array( $value ) ) return $value;
    $value['custom_color']       = '#F57C00';
    $value['head_bg_color']      = '#FFFFFF';
    $value['mobile_head_bg_color'] = '#FFFFFF';
    $value['head_text_color']    = '#333333';
    $value['menu_bg_color']      = '#FFFFFF';
    $value['menu_text_color']    = '#333333';
    $value['search_bg_color']    = '#f5f5f5';
    $value['search_text_color']  = '#333333';
    $value['change_search_bg_color']   = true;
    $value['change_search_text_color'] = true;
    $value['search_btn_bg_color']      = '#F57C00';
    $value['search_btn_text_color']    = '#FFFFFF';
    $value['change_search_btn_bg_color']   = true;
    $value['change_search_btn_text_color'] = true;
    $value['add_btn_color']      = '#F57C00';
    $value['add_btn_text_color'] = '#FFFFFF';
    $value['price_color']        = '#F57C00';
    $value['dis_color']          = '#E53935';
    $value['dis_text_color']     = '#FFFFFF';
    $value['register_btn_color'] = '#F57C00';
    $value['register_btn_text_color'] = '#FFFFFF';
    $value['login_page_btn_color']    = '#F57C00';
    $value['login_page_btn_text_color'] = '#FFFFFF';
    $value['menu_bar_btn_color'] = 'btn-outline-warning';
    $value['footer_text_color']  = '#BBBBBB';
    $value['copy_bg_color']      = '#1a1a2e';
    $value['copy_text_color']    = '#999999';
    $value['show_msg']           = false;
    $value['show_img_msg']       = false;
    $value['show_apps']          = false;
    $value['site_tel']           = '09181717011';
    $value['site_email']         = 'Sfarnam73@gmail.com';
    $value['addr_text']          = 'فروشگاه آنلاین — ارسال به سراسر ایران';
    $value['show_faddr']         = false;
    $value['copy_text']          = 'تمامی حقوق مادی و معنوی برای <strong>موبایل ۸</strong> محفوظ است. | طراحی: <a href="https://sinafarnam.ir" target="_blank" rel="nofollow">سینا فرنام</a>';
    return $value;
}
add_filter( 'option_di_data', 'mobile8_filter_redux_options', 99999 );

// Also override global $di_data at multiple hooks as backup
function mobile8_override_redux_global() {
    global $di_data;
    if ( ! is_array( $di_data ) ) return;
    $di_data = mobile8_filter_redux_options( $di_data );
}
add_action( 'wp_loaded', 'mobile8_override_redux_global' );
add_action( 'wp', 'mobile8_override_redux_global' );
add_action( 'get_header', 'mobile8_override_redux_global' );
add_action( 'wp_head', 'mobile8_override_redux_global', 0 );
add_action( 'wp_head', 'mobile8_override_redux_global', 159 );

// Override ALL theme styles - inline at wp_head AND wp_footer for maximum override
function mobile8_override_css_vars() {
    echo mobile8_get_override_css();
}
add_action( 'wp_head', 'mobile8_override_css_vars', 99999 );
add_action( 'wp_footer', 'mobile8_override_css_vars', 1 );

function mobile8_get_override_css() {
    return '
    <style id="mobile8-overrides">
    :root, html, body, html body, body.rtl, html[dir="rtl"] body {
        --dina-custom-color: #F57C00 !important;
        --woocommerce: #F57C00 !important;
        --dina-head-bg-color: #FFFFFF !important;
        --dina-mobile-head-bg-color: #FFFFFF !important;
        --dina-head-text-color: #333333 !important;
        --dina-menu-bg-color: #FFFFFF !important;
        --dina-menu-text-color: #333333 !important;
        --dina-footer-text-color: #BBBBBB !important;
        --dina-add-btn-color: #F57C00 !important;
        --dina-add-btn-text-color: #FFFFFF !important;
        --dina-register-btn-color: #F57C00 !important;
        --dina-register-btn-text-color: #FFFFFF !important;
        --dina-register-btn-hover-color: #E65100 !important;
        --dina-register-btn-hover-text-color: #FFFFFF !important;
        --dina-login-page-btn-color: #F57C00 !important;
        --dina-login-page-btn-text-color: #FFFFFF !important;
        --dina-login-page-btn-hover-color: #E65100 !important;
        --dina-login-page-btn-hover-text-color: #FFFFFF !important;
        --dina-price-color: #F57C00 !important;
        --dina-dis-color: #E53935 !important;
        --dina-dis-text-color: #FFFFFF !important;
        --dina-copy-bg-color: #111111 !important;
        --dina-copy-text-color: #999999 !important;
        --dina-woo-btn-bg: #E65100 !important;
        --dina-msg-bgcolor: #F57C00 !important;
        --dina-msg-fcolor: #FFFFFF !important;
        --dina-search-bg-color: #f5f5f5 !important;
        --dina-search-text-color: #333333 !important;
        --dina-search-btn-bg-color: #F57C00 !important;
        --dina-search-btn-text-color: #FFFFFF !important;
        --dina-main-font: IRANYekan, Dana, Tahoma, sans-serif !important;
        --dina-md-font: IRANYekan, Dana, Tahoma, sans-serif !important;
        --dina-fd-font: IRANYekan, Dana, Tahoma, sans-serif !important;
    }

    /* HIDE: promotional banner + app icons */
    .dina-head-img-msg-con, .dina-head-img-msg, aside.dina-head-img-msg-con,
    .dina-head-msg, #dinaHeadMsg { display: none !important; }
    .dina-apps-icon { display: none !important; }

    /* ====== HEADER TOP BAR: Orange ====== */
    .dina-header-top-bar,
    .row.dina-header-top-bar,
    html body .dina-header-top-bar,
    html body .row.dina-header-top-bar,
    div.dina-header-top-bar,
    [class*="dina-header-top-bar"] { background-color: #F57C00 !important; }
    .dina-header-top-bar *,
    .dina-head-contact a,
    .dina-head-phone, .dina-head-phone a,
    .dina-head-email, .dina-head-email a,
    .dina-head-menu a,
    .dina-head-contact i,
    html body .dina-head-contact a,
    html body .dina-head-phone, html body .dina-head-phone a,
    html body .dina-head-email, html body .dina-head-email a,
    html body .dina-head-menu a,
    html body .dina-head-contact i { color: #FFFFFF !important; }

    /* ====== HEADER MAIN: White ====== */
    .dina-header,
    .container-fluid.dina-header,
    .container-fluid.dina-header.header,
    .dina-site-header .dina-header,
    header.dina-site-header .container-fluid,
    html body .dina-header,
    html body .container-fluid.dina-header,
    html body header.dina-site-header .container-fluid { background-color: #FFFFFF !important; }
    html body .dina-site-header { border-bottom: 2px solid #f0f0f0 !important; }

    /* ====== NAVBAR: White background ====== */
    .navbar,
    .dina-navbar,
    .dina-navbar .navbar,
    .dina-navbar .container,
    .dina-navbar .dina-nav-con,
    .dina-nav-boxed .navbar .dina-nav-con,
    div.dina-navbar,
    nav.navbar,
    html body .navbar,
    html body .dina-navbar,
    html body .dina-navbar .navbar,
    html body .dina-navbar .container,
    html body .dina-navbar .dina-nav-con,
    html body .dina-nav-boxed .navbar .dina-nav-con,
    html body div.dina-navbar,
    html body nav.navbar,
    [class*="dina-navbar"],
    [class*="dina-navbar"] .navbar,
    [class*="dina-navbar"] nav,
    [class*="dina-navbar"] .dina-nav-con { background: #FFFFFF !important; background-color: #FFFFFF !important; }
    .dina-navbar { border-bottom: 2px solid #F57C00 !important; }

    /* Navbar text: dark */
    .dina-navbar .nav-link,
    .dina-navbar .navbar-nav a,
    .dina-navbar .navbar-nav li a,
    .dina-navbar i,
    .navbar-nav > li > a,
    html body .dina-navbar .nav-link,
    html body .dina-navbar .navbar-nav a,
    html body .navbar-nav > li > a,
    html body .dina-navbar i { color: #333333 !important; }
    .dina-navbar .nav-link:hover,
    .dina-navbar .navbar-nav a:hover,
    .navbar-nav > li:hover > a,
    html body .dina-navbar .nav-link:hover,
    html body .dina-navbar .navbar-nav a:hover { color: #F57C00 !important; }

    /* Menu bar button (مجله) - orange pill */
    .dina-menu-bar-btn,
    html body .dina-menu-bar-btn,
    a.dina-menu-bar-btn,
    .dina-navbar .dina-menu-bar-btn,
    [class*="menu-bar-btn"] { background-color: #F57C00 !important; color: #FFFFFF !important;
        border-color: #F57C00 !important; border-radius: 20px !important; padding: 5px 15px !important; }
    .dina-menu-bar-btn:hover,
    html body .dina-menu-bar-btn:hover { background-color: #E65100 !important; }

    /* Navbar dropdown menus */
    .dina-navbar .dropdown-menu,
    .navbar .dropdown-menu,
    .dina-mega-menu { background-color: #FFFFFF !important; }
    .dina-navbar .dropdown-menu a,
    .dina-mega-menu a { color: #333333 !important; }
    .dina-navbar .dropdown-menu a:hover,
    .dina-mega-menu a:hover { color: #F57C00 !important; background-color: #f5f5f5 !important; }

    /* ====== FOOTER: Clean dark ====== */
    .dina-sfooter,
    .container-fluid.dina-sfooter,
    footer.dina-sfooter,
    html body .dina-sfooter,
    html body .container-fluid.dina-sfooter,
    html body footer.dina-sfooter,
    [class*="dina-sfooter"] { background-color: #1a1a2e !important; }
    .dina-sfooter h1, .dina-sfooter h2,
    .dina-sfooter h3, .dina-sfooter h4,
    .dina-sfooter h5, html body .dina-sfooter h1, html body .dina-sfooter h2,
    html body .dina-sfooter h3, html body .dina-sfooter h4,
    html body .dina-sfooter h5 { color: #FFFFFF !important; }
    .dina-sfooter, .dina-sfooter p,
    .dina-sfooter span, .dina-sfooter li,
    .dina-footer-widget, .dina-footer-widget *,
    html body .dina-sfooter, html body .dina-sfooter p,
    html body .dina-sfooter span, html body .dina-sfooter li,
    html body .dina-footer-widget, html body .dina-footer-widget * { color: #BBBBBB !important; }
    .dina-sfooter a, html body .dina-sfooter a { color: #DDDDDD !important; }
    .dina-sfooter a:hover, html body .dina-sfooter a:hover { color: #F57C00 !important; }
    .dina-footer-addr, html body .dina-footer-addr { border-top: 1px solid rgba(255,255,255,0.1) !important;
        padding-top: 20px !important; margin-top: 20px !important; }
    .dina-foot-tel i, html body .dina-foot-tel i { color: #F57C00 !important; }

    /* COPYRIGHT: darker */
    .dina-copyright,
    .container-fluid.dina-copyright,
    html body .dina-copyright,
    html body .container-fluid.dina-copyright,
    [class*="dina-copyright"] { background-color: #111111 !important; }
    .dina-copyright, .dina-copyright *,
    html body .dina-copyright, html body .dina-copyright * { color: #999999 !important; }
    .dina-copyright a, html body .dina-copyright a { color: #F57C00 !important; }

    /* Footer widgets titles */
    .dina-footer-widget .widget-title,
    .dina-sfooter .widget-title,
    html body .dina-footer-widget .widget-title,
    html body .dina-sfooter .widget-title { color: #FFFFFF !important;
        border-bottom: 2px solid #F57C00 !important; padding-bottom: 10px !important; }

    /* ====== BUTTONS: Orange ====== */
    .dina-add-to-cart-btn,
    .single_add_to_cart_button,
    .btn-dina,
    .button.alt,
    html body .dina-add-to-cart-btn,
    html body .single_add_to_cart_button,
    html body .btn-dina,
    html body .button.alt { background-color: #F57C00 !important; color: #FFFFFF !important; border-color: #F57C00 !important; }
    .dina-add-to-cart-btn:hover,
    .single_add_to_cart_button:hover,
    .btn-dina:hover,
    html body .dina-add-to-cart-btn:hover,
    html body .single_add_to_cart_button:hover,
    html body .btn-dina:hover { background-color: #E65100 !important; border-color: #E65100 !important; }

    /* SEARCH: orange button */
    .dina-search-btn,
    .dina-search-icon,
    button.dina-search-btn,
    html body .dina-search-btn,
    html body button.dina-search-btn { background-color: #F57C00 !important; color: #FFFFFF !important; }

    /* Header user icons dark */
    .dina-user-con a,
    .dina-user-con i,
    .dina-user-con span,
    html body .dina-user-con a,
    html body .dina-user-con i,
    html body .dina-user-con span { color: #333333 !important; }
    .dina-user-con a:hover,
    html body .dina-user-con a:hover { color: #F57C00 !important; }

    /* Compact header */
    .dina-logo-box, html body .dina-logo-box { padding: 8px 0 !important; }
    .dina-logo-box img { max-height: 50px !important; }
    </style>';
}

// Replace ALL demo text AND force styles via JS
function mobile8_replace_branding() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // FORCE hide promo banner
        var banners = document.querySelectorAll('.dina-head-img-msg-con, .dina-head-msg, #dinaHeadMsg, aside.dina-head-img-msg-con');
        banners.forEach(function(b) { b.style.cssText = 'display:none!important;height:0!important;overflow:hidden!important;'; });

        // FORCE navbar white background via JS
        var navbars = document.querySelectorAll('.dina-navbar, .dina-navbar .navbar, .dina-navbar .dina-nav-con, .navbar, .dina-nav-boxed .navbar .dina-nav-con');
        navbars.forEach(function(n) { n.style.cssText += 'background:#FFFFFF!important;background-color:#FFFFFF!important;'; });
        // FORCE navbar text dark
        var navLinks = document.querySelectorAll('.dina-navbar .nav-link, .dina-navbar .navbar-nav a, .navbar-nav > li > a');
        navLinks.forEach(function(a) { a.style.cssText += 'color:#333333!important;'; });
        // FORCE navbar border
        var navbarEl = document.querySelector('.dina-navbar');
        if(navbarEl) navbarEl.style.cssText += 'border-bottom:2px solid #F57C00!important;';

        // FORCE top bar orange
        var topBars = document.querySelectorAll('.dina-header-top-bar, .row.dina-header-top-bar');
        topBars.forEach(function(t) { t.style.cssText += 'background-color:#F57C00!important;'; });
        var topBarLinks = document.querySelectorAll('.dina-header-top-bar a, .dina-header-top-bar span, .dina-header-top-bar i, .dina-head-contact a, .dina-head-phone, .dina-head-phone a, .dina-head-email, .dina-head-email a, .dina-head-menu a');
        topBarLinks.forEach(function(l) { l.style.cssText += 'color:#FFFFFF!important;'; });

        // FORCE header white
        var headers = document.querySelectorAll('.dina-header, .container-fluid.dina-header');
        headers.forEach(function(h) { h.style.cssText += 'background-color:#FFFFFF!important;'; });

        // FORCE footer dark
        var footers = document.querySelectorAll('.dina-sfooter, .container-fluid.dina-sfooter');
        footers.forEach(function(f) { f.style.cssText += 'background-color:#1a1a2e!important;'; });
        var copyrights = document.querySelectorAll('.dina-copyright, .container-fluid.dina-copyright');
        copyrights.forEach(function(c) { c.style.cssText += 'background-color:#111111!important;'; });

        // FORCE menu bar button orange
        var menuBtns = document.querySelectorAll('.dina-menu-bar-btn, a.dina-menu-bar-btn');
        menuBtns.forEach(function(b) { b.style.cssText += 'background-color:#F57C00!important;color:#FFFFFF!important;border-color:#F57C00!important;border-radius:20px!important;'; });

        // FORCE hide app icons
        var appIcons = document.querySelectorAll('.dina-apps-icon');
        appIcons.forEach(function(a) { a.style.cssText = 'display:none!important;'; });

        // Text replacements using TreeWalker for ALL text nodes
        var replacements = [
            ['دیناکالا', 'موبایل ۸'],
            ['دینا کالا', 'موبایل ۸'],
            ['DinaKala', 'Mobile 8'],
            ['Dina Kala', 'Mobile 8'],
            ['نسخه نمایشی قالب', 'تمامی حقوق'],
            ['فروشگاه ابزار', 'فروشگاه گجت و لوازم'],
            ['ابزارآلات صنعتی، ساختمانی و خانگی', 'موبایل، گجت و لوازم جانبی'],
            ['ابزارآلات صنعتی', 'گجت و لوازم جانبی'],
            ['ابزار برقی و شارژی', 'موبایل و تبلت'],
            ['در حوزه ابزار', 'در حوزه گجت و موبایل'],
            ['ابزار دستی، تجهیزات جوشکاری، ابزار نجاری و ساختمانی، و لوازم بادی', 'هندزفری، ساعت هوشمند، شارژر، کابل، قاب، پاوربانک و لوازم جانبی'],
            ['ابزار دستی', 'لوازم جانبی'],
            ['تجهیزات جوشکاری', 'شارژر و کابل'],
            ['ابزار نجاری و ساختمانی', 'قاب و کاور'],
            ['لوازم بادی', 'پاوربانک'],
            ['Bosch، Makita، DeWalt، Ronix، Tosan', 'Samsung، Apple، Xiaomi، Huawei، JBL'],
            ['Bosch', 'Samsung'], ['Makita', 'Apple'], ['DeWalt', 'Xiaomi'], ['Ronix', 'Huawei'], ['Tosan', 'JBL'],
            ['یک مرجع تخصصی ابزار', 'یک مرجع تخصصی گجت و موبایل'],
            ['تعمیر و تأمین قطعات یدکی', 'مشاوره و راهنمایی خرید'],
            ['استان تهران، شهر تهران، خیابان مرکزی، ساختمان مرکزی، پلاک 7', 'فروشگاه آنلاین — ارسال به سراسر ایران'],
            ['استان تهران', 'ارسال به سراسر'],
            ['شهر تهران', 'ایران'],
            ['خیابان مرکزی', ''],
            ['ساختمان مرکزی', ''],
            ['پلاک 7', ''],
            ['مجله ابزار', 'مجله موبایل ۸'],
            ['ابزار برق', 'موبایل']
        ];

        var walker = document.createTreeWalker(
            document.body,
            NodeFilter.SHOW_TEXT,
            null,
            false
        );
        var node;
        while (node = walker.nextNode()) {
            var text = node.nodeValue;
            if (!text || !text.trim()) continue;
            var changed = false;
            for (var i = 0; i < replacements.length; i++) {
                if (text.indexOf(replacements[i][0]) !== -1) {
                    text = text.split(replacements[i][0]).join(replacements[i][1]);
                    changed = true;
                }
            }
            if (changed) node.nodeValue = text;
        }
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'mobile8_replace_branding', 99999 );

// Auto-create pages on theme activation
function mobile8_create_pages() {
    $pages = array(
        'about-us' => array(
            'title'   => 'درباره ما',
            'content' => mobile8_about_content(),
        ),
        'faq' => array(
            'title'   => 'سوالات متداول',
            'content' => mobile8_faq_content(),
        ),
        'terms' => array(
            'title'   => 'شرایط و قوانین',
            'content' => mobile8_terms_content(),
        ),
        'contact-us' => array(
            'title'   => 'تماس با ما',
            'content' => mobile8_contact_content(),
        ),
        'order-tracking' => array(
            'title'   => 'رهگیری سفارشات',
            'content' => mobile8_tracking_content(),
        ),
    );

    foreach ( $pages as $slug => $page ) {
        if ( ! get_page_by_path( $slug ) ) {
            wp_insert_post( array(
                'post_title'   => $page['title'],
                'post_name'    => $slug,
                'post_content' => $page['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
            ) );
        }
    }
}
add_action( 'after_switch_theme', 'mobile8_create_pages' );

// Run page creation once if pages don't exist
function mobile8_maybe_create_pages() {
    if ( ! get_page_by_path( 'about-us' ) ) {
        mobile8_create_pages();
    }
}
add_action( 'init', 'mobile8_maybe_create_pages' );

// ==========================================
// PAGE CONTENTS
// ==========================================

function mobile8_about_content() {
    return '
<div class="m8-about-hero">
    <h1>فروشگاه تخصصی گجت و لوازم</h1>
    <p style="font-size: 18px; opacity: 0.9;">از سال ۱۳۹۸ در کنار شما هستیم</p>
</div>

<div class="m8-about-stats">
    <div class="m8-about-stat">
        <span class="stat-number">+۶</span>
        <span class="stat-label">سال تجربه</span>
    </div>
    <div class="m8-about-stat">
        <span class="stat-number">۵</span>
        <span class="stat-label">نفر تیم حرفه‌ای</span>
    </div>
    <div class="m8-about-stat">
        <span class="stat-number">+۲۴۰</span>
        <span class="stat-label">محصول متنوع</span>
    </div>
    <div class="m8-about-stat">
        <span class="stat-number">۳۱</span>
        <span class="stat-label">استان تحت پوشش</span>
    </div>
</div>

<h2>داستان ما</h2>
<p style="line-height: 2.2; font-size: 15px;">
فروشگاه <strong>موبایل ۸</strong> از سال ۱۳۹۸ فعالیت خود را در زمینه فروش موبایل، گجت و لوازم جانبی آغاز کرد. ما با شروع کار در استان‌های <strong>کردستان و همدان</strong>، به تدریج خدمات خود را به سراسر ایران گسترش دادیم.
</p>
<p style="line-height: 2.2; font-size: 15px;">
تیم ۵ نفره ما متعهد است بهترین محصولات را با <strong>کیفیت تضمین‌شده</strong> و <strong>قیمت مناسب</strong> به دست شما برساند. تمامی محصولات قبل از ارسال توسط کارشناسان ما تست و بررسی می‌شوند.
</p>

<h2>چرا موبایل ۸؟</h2>
<ul style="line-height: 2.5; font-size: 15px;">
    <li>✅ تست محصول قبل از ارسال (امکان ارسال ویدیوی تست)</li>
    <li>✅ ارسال سریع — سعی می‌کنیم فردای روز سفارش محصول را ارسال کنیم</li>
    <li>✅ ارسال به سراسر ایران با پست و تیپاکس</li>
    <li>✅ امکان پرداخت درب منزل برای خریدهای بالای ۱,۵۰۰,۰۰۰ تومان</li>
    <li>✅ پشتیبانی و مشاوره قبل از خرید</li>
</ul>

<h2>ارتباط با ما</h2>
<p style="line-height: 2.2; font-size: 15px;">
📞 تلفن: <a href="tel:09181717011">۰۹۱۸۱۷۱۷۰۱۱</a><br>
📧 ایمیل: <a href="mailto:Sfarnam73@gmail.com">Sfarnam73@gmail.com</a><br>
📸 اینستاگرام: <a href="https://instagram.com/mobile_8" target="_blank">mobile_8@</a><br>
📱 تلگرام: <a href="https://t.me/sinafarnam8" target="_blank">sinafarnam8@</a><br>
💬 واتساپ: <a href="https://wa.me/989188111504" target="_blank">۰۹۱۸۸۱۱۱۵۰۴</a>
</p>

<p style="text-align: center; margin-top: 40px; color: #999; font-size: 13px;">
طراحی سایت: <a href="https://sinafarnam.ir" target="_blank" rel="nofollow">سینا فرنام</a>
</p>';
}

function mobile8_faq_content() {
    return '
<div class="m8-faq-section">

<h2 style="text-align: center; margin-bottom: 30px;">سوالات متداول مشتریان</h2>

<div class="m8-faq-item">
    <div class="m8-faq-question">روش‌های ارسال سفارش چیست؟</div>
    <div class="m8-faq-answer">
        ارسال سفارشات از طریق <strong>شرکت پست</strong> و <strong>تیپاکس</strong> انجام می‌شود. در صورتی که عجله دارید، با پرداخت هزینه پیک، امکان ارسال از طریق <strong>باربری</strong> یا <strong>اتوبوس</strong> نیز وجود دارد. روش ارسال بر اساس ترجیح مشتری انتخاب می‌شود.
    </div>
</div>

<div class="m8-faq-item">
    <div class="m8-faq-question">مدت زمان ارسال سفارش چقدر است؟</div>
    <div class="m8-faq-answer">
        به محض ثبت سفارش، تیم ما تلاش می‌کند <strong>فردای همان روز</strong> محصول را ارسال کند. سپس بسته به روش ارسال (پست یا تیپاکس) و مقصد، بین ۱ تا ۵ روز کاری به دست شما می‌رسد.
    </div>
</div>

<div class="m8-faq-item">
    <div class="m8-faq-question">آیا محصولات قبل از ارسال تست می‌شوند؟</div>
    <div class="m8-faq-answer">
        بله، <strong>تمامی محصولات قبل از ارسال</strong> توسط کارشناسان ما بررسی و تست می‌شوند. در صورت درخواست مشتری، <strong>ویدیوی تست محصول</strong> نیز قبل از ارسال تهیه و ارسال می‌شود.
    </div>
</div>

<div class="m8-faq-item">
    <div class="m8-faq-question">شرایط مرجوعی محصول چیست؟</div>
    <div class="m8-faq-answer">
        مرجوعی <strong>فقط در صورت خرابی محصول</strong> امکان‌پذیر است. لازم به ذکر است:<br>
        - محصولاتی که با <strong>قیمت عمده</strong> فروخته می‌شوند، به هیچ وجه مرجوعی ندارند.<br>
        - محصولات <strong>اوپن‌باکس</strong> قبل از ارسال تست می‌شوند و با آگاهی مشتری ارسال می‌گردند.
    </div>
</div>

<div class="m8-faq-item">
    <div class="m8-faq-question">آیا امکان پرداخت درب منزل وجود دارد؟</div>
    <div class="m8-faq-answer">
        بله، برای خریدهای بالای <strong>۱,۵۰۰,۰۰۰ تومان</strong> امکان پرداخت درب منزل با پرداخت بیعانه وجود دارد. برای هماهنگی با شماره <a href="tel:09181717011">۰۹۱۸۱۷۱۷۰۱۱</a> تماس بگیرید.
    </div>
</div>

<div class="m8-faq-item">
    <div class="m8-faq-question">آیا ارسال رایگان دارید؟</div>
    <div class="m8-faq-answer">
        خیر، هزینه ارسال بر اساس <strong>وزن بسته</strong> و <strong>شرکت حمل‌ونقل</strong> انتخابی مشتری محاسبه می‌شود. ارسال توسط پست، تیپاکس، باربری یا اتوبوس قابل انجام است.
    </div>
</div>

<div class="m8-faq-item">
    <div class="m8-faq-question">محصولات اوپن‌باکس چیست؟</div>
    <div class="m8-faq-answer">
        محصولات اوپن‌باکس، کالاهایی هستند که جعبه آن‌ها باز شده اما استفاده نشده یا کم‌استفاده هستند. این محصولات با <strong>قیمت مناسب‌تر</strong> عرضه می‌شوند و قبل از ارسال توسط تیم ما <strong>تست کامل</strong> می‌شوند.
    </div>
</div>

<div class="m8-faq-item">
    <div class="m8-faq-question">چگونه سفارش خود را پیگیری کنم؟</div>
    <div class="m8-faq-answer">
        پس از ارسال سفارش، <strong>کد رهگیری</strong> برای شما ارسال می‌شود. می‌توانید از طریق صفحه <a href="/order-tracking">رهگیری سفارشات</a> یا تماس با شماره <a href="tel:09181717011">۰۹۱۸۱۷۱۷۰۱۱</a> وضعیت سفارش خود را پیگیری کنید.
    </div>
</div>

</div>';
}

function mobile8_terms_content() {
    return '
<div class="m8-terms-section">

<p style="font-size: 15px; line-height: 2;">
لطفاً قبل از خرید از فروشگاه <strong>موبایل ۸</strong>، شرایط و قوانین زیر را به دقت مطالعه فرمایید. ثبت سفارش به منزله پذیرش تمامی شرایط زیر می‌باشد.
</p>

<h2>شرایط خرید</h2>
<ul>
    <li>تمامی قیمت‌ها به <strong>تومان</strong> و بر اساس نرخ روز محاسبه می‌شوند.</li>
    <li>قیمت محصولات ممکن است بدون اطلاع قبلی تغییر کند.</li>
    <li>موجودی محصولات محدود است و ثبت سفارش به منزله رزرو محصول نیست.</li>
    <li>در صورت ناموجود شدن محصول پس از ثبت سفارش، مبلغ پرداختی به طور کامل عودت داده می‌شود.</li>
</ul>

<h2>شرایط ارسال</h2>
<ul>
    <li>ارسال سفارشات از طریق <strong>پست</strong> و <strong>تیپاکس</strong> به سراسر ایران انجام می‌شود.</li>
    <li>در صورت درخواست مشتری، ارسال از طریق <strong>باربری</strong> یا <strong>اتوبوس</strong> نیز امکان‌پذیر است.</li>
    <li>هزینه ارسال بر عهده خریدار است و بر اساس وزن و مقصد محاسبه می‌شود.</li>
    <li>تلاش می‌شود سفارشات <strong>فردای روز ثبت</strong> ارسال گردند.</li>
    <li>مسئولیت آسیب‌های ناشی از حمل‌ونقل بر عهده شرکت حمل‌کننده می‌باشد.</li>
</ul>

<h2>شرایط پرداخت</h2>
<ul>
    <li>پرداخت آنلاین از طریق درگاه بانکی <strong>تومن</strong> انجام می‌شود.</li>
    <li>امکان <strong>پرداخت درب منزل</strong> برای خریدهای بالای ۱,۵۰۰,۰۰۰ تومان با پرداخت بیعانه وجود دارد.</li>
    <li>برای هماهنگی پرداخت درب منزل با شماره ۰۹۱۸۱۷۱۷۰۱۱ تماس بگیرید.</li>
</ul>

<h2>شرایط مرجوعی و بازگشت کالا</h2>
<ul>
    <li>مرجوعی <strong>فقط در صورت خرابی محصول</strong> امکان‌پذیر است.</li>
    <li>تمامی محصولات قبل از ارسال تست و بررسی می‌شوند.</li>
    <li>محصولاتی که با <strong>قیمت عمده</strong> فروخته می‌شوند، تحت هیچ شرایطی قابل مرجوعی نیستند.</li>
    <li>محصولات <strong>اوپن‌باکس</strong> قبل از ارسال تست شده و با اطلاع و رضایت مشتری ارسال می‌گردند.</li>
    <li>در صورت درخواست، ویدیوی تست محصول قبل از ارسال تهیه می‌شود.</li>
    <li>درخواست مرجوعی باید حداکثر تا <strong>۴۸ ساعت</strong> پس از دریافت محصول ثبت شود.</li>
    <li>هزینه ارسال مرجوعی بر عهده خریدار است مگر آن‌که خرابی از جانب فروشگاه باشد.</li>
</ul>

<h2>گارانتی</h2>
<ul>
    <li>گارانتی محصولات بر اساس نوع کالا و برند متفاوت است و در صفحه محصول ذکر شده است.</li>
    <li>گارانتی شامل خرابی‌های ناشی از استفاده نادرست، ضربه یا آب‌خوردگی نمی‌شود.</li>
</ul>

<h2>حریم خصوصی</h2>
<ul>
    <li>اطلاعات شخصی مشتریان نزد فروشگاه محفوظ بوده و به هیچ شخص ثالثی ارائه نخواهد شد.</li>
    <li>اطلاعات تماس مشتری صرفاً برای هماهنگی ارسال و پشتیبانی استفاده می‌شود.</li>
</ul>

<p style="text-align: center; margin-top: 40px; padding: 20px; background: #FFF3E0; border-radius: 10px; border-right: 4px solid #F57C00;">
    در صورت داشتن هرگونه سوال درباره شرایط و قوانین، با ما تماس بگیرید:<br>
    📞 <a href="tel:09181717011">۰۹۱۸۱۷۱۷۰۱۱</a> &nbsp; | &nbsp;
    📧 <a href="mailto:Sfarnam73@gmail.com">Sfarnam73@gmail.com</a>
</p>

</div>';
}

function mobile8_contact_content() {
    return '
<div style="max-width: 900px; margin: 0 auto; padding: 30px 15px;">

<p style="text-align: center; font-size: 16px; line-height: 2; margin-bottom: 30px;">
برای ارتباط با <strong>فروشگاه موبایل ۸</strong> از طریق راه‌های زیر اقدام کنید.
تیم ما آماده پاسخگویی به سوالات و مشاوره خرید شماست.
</p>

<div class="m8-contact-cards">

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">📞</div>
        <h3>تماس تلفنی</h3>
        <a href="tel:09181717011" style="font-size: 20px; font-weight: 700; color: #F57C00 !important;">۰۹۱۸۱۷۱۷۰۱۱</a>
        <p style="color: #999; margin-top: 10px; font-size: 13px;">شنبه تا پنج‌شنبه ۹ صبح تا ۹ شب</p>
    </div>

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">💬</div>
        <h3>واتساپ</h3>
        <a href="https://wa.me/989188111504" target="_blank" style="font-size: 18px; font-weight: 700; color: #25D366 !important;">۰۹۱۸۸۱۱۱۵۰۴</a>
        <p style="color: #999; margin-top: 10px; font-size: 13px;">پاسخگویی سریع در واتساپ</p>
    </div>

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">📧</div>
        <h3>ایمیل</h3>
        <a href="mailto:Sfarnam73@gmail.com" style="font-size: 16px; font-weight: 700;">Sfarnam73@gmail.com</a>
        <p style="color: #999; margin-top: 10px; font-size: 13px;">پاسخگویی در کمتر از ۲۴ ساعت</p>
    </div>

</div>

<div style="margin-top: 30px;">
<div class="m8-contact-cards">

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">📸</div>
        <h3>اینستاگرام</h3>
        <a href="https://instagram.com/mobile_8" target="_blank" style="font-weight: 700; color: #E4405F !important;">mobile_8@</a>
        <p style="color: #999; margin-top: 10px; font-size: 13px;">جدیدترین محصولات و تخفیف‌ها</p>
    </div>

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">📱</div>
        <h3>تلگرام</h3>
        <a href="https://t.me/sinafarnam8" target="_blank" style="font-weight: 700; color: #0088CC !important;">sinafarnam8@</a>
        <p style="color: #999; margin-top: 10px; font-size: 13px;">کانال تلگرام فروشگاه</p>
    </div>

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">🏪</div>
        <h3>فروشگاه آنلاین</h3>
        <p style="font-weight: 600;">فروشگاه اینترنتی</p>
        <p style="color: #999; margin-top: 5px; font-size: 13px;">ارسال به سراسر ایران<br>کردستان و همدان</p>
    </div>

</div>
</div>

</div>';
}

function mobile8_tracking_content() {
    return '
<div style="max-width: 700px; margin: 0 auto; padding: 40px 15px; text-align: center;">

<div style="font-size: 60px; margin-bottom: 20px;">📦</div>
<h2 style="margin-bottom: 15px;">رهگیری سفارش</h2>
<p style="line-height: 2; color: #666; margin-bottom: 30px;">
برای پیگیری وضعیت سفارش خود، از لینک‌های زیر استفاده کنید یا با ما تماس بگیرید.
</p>

<div style="display: grid; gap: 15px; max-width: 400px; margin: 0 auto 30px;">
    <a href="https://tracking.post.ir" target="_blank" style="display: block; padding: 15px 25px; background: #F57C00; color: #fff !important; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 16px;">
        📮 رهگیری پست
    </a>
    <a href="https://tipax.ir/tracking" target="_blank" style="display: block; padding: 15px 25px; background: #1976D2; color: #fff !important; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 16px;">
        🚚 رهگیری تیپاکس
    </a>
</div>

<div style="background: #FFF3E0; padding: 25px; border-radius: 12px; border-right: 4px solid #F57C00;">
    <p style="margin: 0; line-height: 2;">
        <strong>کد رهگیری ندارید؟</strong><br>
        با شماره <a href="tel:09181717011" style="font-weight: 700; color: #F57C00 !important;">۰۹۱۸۱۷۱۷۰۱۱</a> تماس بگیرید<br>
        یا در <a href="https://wa.me/989188111504" target="_blank" style="font-weight: 700; color: #25D366 !important;">واتساپ</a> پیام دهید
    </p>
</div>

[woocommerce_order_tracking]

</div>';
}

// RC Products Shortcode
function mobile8_rc_products_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'limit'   => 8,
        'columns' => 4,
        'cat'     => 'کوادکوپتر',
    ), $atts );

    return do_shortcode( sprintf(
        '[products limit="%d" columns="%d" category="%s" orderby="date" order="DESC"]',
        $atts['limit'],
        $atts['columns'],
        $atts['cat']
    ) );
}
add_shortcode( 'mobile8_rc', 'mobile8_rc_products_shortcode' );
