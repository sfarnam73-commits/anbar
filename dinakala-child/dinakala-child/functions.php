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

// Create or update pages with SEO content
function mobile8_create_pages() {
    $pages = mobile8_get_pages_data();
    foreach ( $pages as $slug => $page ) {
        $existing = get_page_by_path( $slug );
        if ( ! $existing ) {
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

function mobile8_maybe_create_pages() {
    if ( ! get_page_by_path( 'about-us' ) ) {
        mobile8_create_pages();
    }
}
add_action( 'init', 'mobile8_maybe_create_pages' );

// Update existing pages with new SEO content (runs once)
function mobile8_update_pages_seo() {
    if ( get_option( 'mobile8_seo_v3' ) ) return;
    $pages = mobile8_get_pages_data();
    foreach ( $pages as $slug => $page ) {
        $existing = get_page_by_path( $slug );
        if ( $existing ) {
            wp_update_post( array(
                'ID'           => $existing->ID,
                'post_title'   => $page['title'],
                'post_content' => $page['content'],
            ) );
        }
    }
    update_option( 'mobile8_seo_v3', true );
}
add_action( 'init', 'mobile8_update_pages_seo' );

function mobile8_get_pages_data() {
    return array(
        'about-us' => array(
            'title'   => 'درباره ما | فروشگاه اینترنتی موبایل ۸',
            'content' => mobile8_about_content(),
        ),
        'faq' => array(
            'title'   => 'سوالات متداول | خرید آنلاین موبایل و لوازم جانبی',
            'content' => mobile8_faq_content(),
        ),
        'terms' => array(
            'title'   => 'شرایط و قوانین خرید | فروشگاه موبایل ۸',
            'content' => mobile8_terms_content(),
        ),
        'contact-us' => array(
            'title'   => 'تماس با ما | فروشگاه موبایل ۸ — مشاوره خرید',
            'content' => mobile8_contact_content(),
        ),
        'order-tracking' => array(
            'title'   => 'رهگیری سفارش | پیگیری مرسوله پستی و تیپاکس',
            'content' => mobile8_tracking_content(),
        ),
    );
}

// Schema.org JSON-LD for SEO
function mobile8_schema_markup() {
    if ( ! is_page() ) return;
    $page_slug = get_post_field( 'post_name', get_queried_object_id() );

    if ( $page_slug === 'about-us' || $page_slug === 'contact-us' ) {
        echo '<script type="application/ld+json">' . wp_json_encode( array(
            '@context' => 'https://schema.org',
            '@type'    => 'OnlineStore',
            'name'     => 'فروشگاه موبایل ۸',
            'url'      => 'https://mobile8.ir',
            'description' => 'فروشگاه اینترنتی موبایل ۸ — خرید آنلاین گوشی موبایل، تبلت، هندزفری، ساعت هوشمند و لوازم جانبی با ارسال به سراسر ایران',
            'telephone'   => '+989181717011',
            'email'       => 'Sfarnam73@gmail.com',
            'foundingDate' => '2019',
            'areaServed'  => array( '@type' => 'Country', 'name' => 'Iran' ),
            'contactPoint' => array(
                '@type'            => 'ContactPoint',
                'telephone'        => '+989181717011',
                'contactType'      => 'customer service',
                'availableLanguage'=> 'Persian',
            ),
            'sameAs' => array(
                'https://instagram.com/mobile_8',
                'https://t.me/sinafarnam8',
                'https://wa.me/989188111504',
            ),
        ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
    }

    if ( $page_slug === 'faq' ) {
        $faqs = array(
            array('q' => 'روش‌های ارسال سفارش در فروشگاه موبایل ۸ چیست؟', 'a' => 'ارسال سفارشات از طریق شرکت پست و تیپاکس انجام می‌شود. همچنین امکان ارسال از طریق باربری یا اتوبوس نیز وجود دارد.'),
            array('q' => 'مدت زمان ارسال سفارش چقدر است؟', 'a' => 'تیم ما تلاش می‌کند فردای همان روز محصول را ارسال کند. بسته به روش ارسال و مقصد، بین ۱ تا ۵ روز کاری به دست شما می‌رسد.'),
            array('q' => 'آیا محصولات قبل از ارسال تست می‌شوند؟', 'a' => 'بله، تمامی محصولات قبل از ارسال توسط کارشناسان ما بررسی و تست می‌شوند. در صورت درخواست، ویدیوی تست محصول نیز تهیه می‌شود.'),
            array('q' => 'شرایط مرجوعی محصول چیست؟', 'a' => 'مرجوعی فقط در صورت خرابی محصول امکان‌پذیر است. محصولات عمده و اوپن‌باکس شرایط خاص خود را دارند.'),
            array('q' => 'آیا امکان پرداخت درب منزل وجود دارد؟', 'a' => 'بله، برای خریدهای بالای ۱,۵۰۰,۰۰۰ تومان امکان پرداخت درب منزل با پرداخت بیعانه وجود دارد.'),
            array('q' => 'آیا ارسال رایگان دارید؟', 'a' => 'هزینه ارسال بر اساس وزن بسته و شرکت حمل‌ونقل انتخابی مشتری محاسبه می‌شود.'),
            array('q' => 'محصولات اوپن‌باکس چیست؟', 'a' => 'محصولات اوپن‌باکس کالاهایی هستند که جعبه آن‌ها باز شده اما استفاده نشده یا کم‌استفاده هستند و با قیمت مناسب‌تر و تست کامل عرضه می‌شوند.'),
            array('q' => 'چگونه سفارش خود را پیگیری کنم؟', 'a' => 'پس از ارسال، کد رهگیری برای شما ارسال می‌شود. از طریق صفحه رهگیری سفارشات یا تماس با ۰۹۱۸۱۷۱۷۰۱۱ وضعیت سفارش را پیگیری کنید.'),
        );
        $main_entity = array();
        foreach ( $faqs as $faq ) {
            $main_entity[] = array(
                '@type' => 'Question',
                'name'  => $faq['q'],
                'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $faq['a'] ),
            );
        }
        echo '<script type="application/ld+json">' . wp_json_encode( array(
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $main_entity,
        ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
    }
}
add_action( 'wp_head', 'mobile8_schema_markup', 5 );

// ==========================================
// SEO-OPTIMIZED PAGE CONTENTS
// ==========================================

function mobile8_about_content() {
    return '
<article itemscope itemtype="https://schema.org/AboutPage">

<div class="m8-about-hero">
    <h1 itemprop="name">فروشگاه اینترنتی موبایل ۸ — خرید آنلاین گوشی، تبلت و لوازم جانبی</h1>
    <p style="font-size: 18px; opacity: 0.9;">از سال ۱۳۹۸ در کنار شما هستیم — ارسال به سراسر ایران</p>
</div>

<div class="m8-about-stats">
    <div class="m8-about-stat"><span class="stat-number">+۶</span><span class="stat-label">سال تجربه</span></div>
    <div class="m8-about-stat"><span class="stat-number">۵</span><span class="stat-label">نفر تیم حرفه‌ای</span></div>
    <div class="m8-about-stat"><span class="stat-number">+۲۴۰</span><span class="stat-label">محصول متنوع</span></div>
    <div class="m8-about-stat"><span class="stat-number">۳۱</span><span class="stat-label">استان تحت پوشش</span></div>
</div>

<div itemprop="description">
<h2>داستان فروشگاه موبایل ۸</h2>
<p style="line-height: 2.2; font-size: 15px;">
<strong>فروشگاه اینترنتی موبایل ۸</strong> (mobile8.ir) از سال ۱۳۹۸ فعالیت خود را در زمینه <strong>فروش آنلاین گوشی موبایل، تبلت، هندزفری، ساعت هوشمند و لوازم جانبی</strong> آغاز کرد. ما با شروع کار در استان‌های <strong>کردستان و همدان</strong>، به تدریج خدمات خود را به <strong>سراسر ایران</strong> گسترش دادیم.
</p>
<p style="line-height: 2.2; font-size: 15px;">
تیم ۵ نفره ما متعهد است بهترین محصولات دیجیتال و الکترونیکی را با <strong>کیفیت تضمین‌شده</strong> و <strong>قیمت مناسب</strong> به دست شما برساند. تمامی محصولات شامل <strong>گوشی سامسونگ، آیفون اپل، شیائومی، هندزفری بلوتوثی، ساعت هوشمند، شارژر، کابل، قاب و کاور، پاوربانک</strong> و سایر لوازم جانبی قبل از ارسال توسط کارشناسان ما تست و بررسی می‌شوند.
</p>
</div>

<h2>چرا خرید از فروشگاه موبایل ۸؟</h2>
<ul style="line-height: 2.5; font-size: 15px;">
    <li><strong>تست محصول قبل از ارسال</strong> — امکان ارسال ویدیوی تست برای اطمینان شما</li>
    <li><strong>ارسال سریع</strong> — تلاش می‌کنیم فردای روز سفارش، محصول را ارسال کنیم</li>
    <li><strong>ارسال به سراسر ایران</strong> — از طریق پست و تیپاکس به تمام ۳۱ استان</li>
    <li><strong>پرداخت درب منزل</strong> — برای خریدهای بالای ۱,۵۰۰,۰۰۰ تومان</li>
    <li><strong>مشاوره و پشتیبانی رایگان</strong> — قبل و بعد از خرید در کنار شماییم</li>
</ul>

<h2>محصولات فروشگاه موبایل ۸</h2>
<p style="line-height: 2.2; font-size: 15px;">
ما در <strong>موبایل ۸</strong> محصولات متنوعی از برندهای معتبر جهانی عرضه می‌کنیم:
</p>
<ul style="line-height: 2.2; font-size: 15px;">
    <li><strong>گوشی موبایل</strong>: سامسونگ (Samsung)، اپل (Apple)، شیائومی (Xiaomi)، هوآوی (Huawei)، آنر (Honor)</li>
    <li><strong>تبلت</strong>: سامسونگ گلکسی تب، اپل آیپد، لنوو</li>
    <li><strong>هندزفری و هدفون</strong>: ایرپاد اپل، سامسونگ گلکسی بادز، JBL، شیائومی</li>
    <li><strong>ساعت هوشمند</strong>: اپل واچ، سامسونگ گلکسی واچ، شیائومی</li>
    <li><strong>لوازم جانبی</strong>: شارژر، کابل، قاب و کاور، محافظ صفحه، پاوربانک</li>
    <li><strong>کوادکوپتر و پهپاد</strong>: مدل‌های مختلف با دوربین</li>
</ul>

<h2>راه‌های ارتباطی</h2>
<p style="line-height: 2.2; font-size: 15px;">
برای <strong>مشاوره خرید</strong> و سفارش محصول با ما در تماس باشید:<br>
تلفن: <a href="tel:09181717011">۰۹۱۸۱۷۱۷۰۱۱</a> |
ایمیل: <a href="mailto:Sfarnam73@gmail.com">Sfarnam73@gmail.com</a> |
واتساپ: <a href="https://wa.me/989188111504" target="_blank" rel="noopener">۰۹۱۸۸۱۱۱۵۰۴</a><br>
اینستاگرام: <a href="https://instagram.com/mobile_8" target="_blank" rel="noopener">mobile_8@</a> |
تلگرام: <a href="https://t.me/sinafarnam8" target="_blank" rel="noopener">sinafarnam8@</a>
</p>

<p style="line-height: 2; font-size: 14px; margin-top: 20px;">
همچنین می‌توانید <a href="/faq">سوالات متداول</a> را مطالعه کنید، <a href="/terms">شرایط و قوانین خرید</a> را بررسی کنید یا از صفحه <a href="/contact-us">تماس با ما</a> اقدام کنید.
</p>

<p style="text-align: center; margin-top: 40px; color: #999; font-size: 13px;">
طراحی سایت: <a href="https://sinafarnam.ir" target="_blank" rel="nofollow noopener">سینا فرنام</a>
</p>

</article>';
}

function mobile8_faq_content() {
    return '
<article itemscope itemtype="https://schema.org/FAQPage">

<div class="m8-faq-section">

<h1>سوالات متداول خرید از فروشگاه اینترنتی موبایل ۸</h1>
<p style="text-align: center; font-size: 15px; line-height: 2; margin-bottom: 30px;">
پاسخ سوالات رایج درباره <strong>خرید آنلاین گوشی موبایل</strong>، <strong>لوازم جانبی</strong>، روش‌های ارسال، مرجوعی و پرداخت در <strong>فروشگاه موبایل ۸</strong> (mobile8.ir) را در این صفحه بخوانید.
</p>

<h2>ارسال و حمل‌ونقل</h2>

<div class="m8-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
    <div class="m8-faq-question" itemprop="name">روش‌های ارسال سفارش در فروشگاه موبایل ۸ چیست؟</div>
    <div class="m8-faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
        <div itemprop="text">
        ارسال سفارشات <strong>گوشی موبایل، تبلت، هندزفری و لوازم جانبی</strong> از طریق <strong>شرکت پست</strong> و <strong>تیپاکس</strong> به <strong>سراسر ایران</strong> انجام می‌شود. همچنین امکان ارسال از طریق <strong>باربری</strong> یا <strong>اتوبوس</strong> نیز وجود دارد. روش ارسال بر اساس ترجیح مشتری انتخاب می‌شود.
        </div>
    </div>
</div>

<div class="m8-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
    <div class="m8-faq-question" itemprop="name">مدت زمان ارسال سفارش گوشی و لوازم جانبی چقدر است؟</div>
    <div class="m8-faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
        <div itemprop="text">
        تیم <strong>فروشگاه موبایل ۸</strong> تلاش می‌کند <strong>فردای همان روز</strong> محصول را ارسال کند. بسته به روش ارسال (پست یا تیپاکس) و مقصد، بین <strong>۱ تا ۵ روز کاری</strong> سفارش به دست شما می‌رسد.
        </div>
    </div>
</div>

<div class="m8-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
    <div class="m8-faq-question" itemprop="name">آیا فروشگاه موبایل ۸ ارسال رایگان دارد؟</div>
    <div class="m8-faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
        <div itemprop="text">
        هزینه ارسال بر اساس <strong>وزن بسته</strong> و <strong>شرکت حمل‌ونقل</strong> انتخابی مشتری محاسبه می‌شود. ارسال توسط پست، تیپاکس، باربری یا اتوبوس قابل انجام است.
        </div>
    </div>
</div>

<h2>کیفیت و تست محصولات</h2>

<div class="m8-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
    <div class="m8-faq-question" itemprop="name">آیا محصولات فروشگاه موبایل ۸ قبل از ارسال تست می‌شوند؟</div>
    <div class="m8-faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
        <div itemprop="text">
        بله، تمامی محصولات شامل <strong>گوشی سامسونگ، آیفون، شیائومی، هندزفری، ساعت هوشمند</strong> و لوازم جانبی قبل از ارسال توسط کارشناسان ما بررسی و تست می‌شوند. در صورت درخواست مشتری، <strong>ویدیوی تست محصول</strong> نیز تهیه و ارسال می‌شود.
        </div>
    </div>
</div>

<div class="m8-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
    <div class="m8-faq-question" itemprop="name">محصولات اوپن‌باکس در فروشگاه موبایل ۸ چیست؟</div>
    <div class="m8-faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
        <div itemprop="text">
        محصولات اوپن‌باکس کالاهایی هستند که جعبه آن‌ها باز شده اما استفاده نشده یا کم‌استفاده هستند. این محصولات با <strong>قیمت مناسب‌تر</strong> عرضه شده و قبل از ارسال توسط تیم ما <strong>تست کامل</strong> می‌شوند. خرید اوپن‌باکس گزینه مناسبی برای صرفه‌جویی در هزینه است.
        </div>
    </div>
</div>

<h2>مرجوعی و گارانتی</h2>

<div class="m8-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
    <div class="m8-faq-question" itemprop="name">شرایط مرجوعی محصول در فروشگاه موبایل ۸ چیست؟</div>
    <div class="m8-faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
        <div itemprop="text">
        مرجوعی <strong>فقط در صورت خرابی محصول</strong> امکان‌پذیر است. نکات مهم:<br>
        - محصولاتی که با <strong>قیمت عمده</strong> فروخته می‌شوند، قابل مرجوعی نیستند.<br>
        - محصولات <strong>اوپن‌باکس</strong> قبل از ارسال تست شده و با آگاهی مشتری ارسال می‌گردند.<br>
        - درخواست مرجوعی باید حداکثر تا <strong>۴۸ ساعت</strong> پس از دریافت ثبت شود.<br>
        برای اطلاعات بیشتر <a href="/terms">شرایط و قوانین خرید</a> را مطالعه کنید.
        </div>
    </div>
</div>

<h2>پرداخت و خرید</h2>

<div class="m8-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
    <div class="m8-faq-question" itemprop="name">آیا امکان پرداخت درب منزل (پس‌کرایه) وجود دارد؟</div>
    <div class="m8-faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
        <div itemprop="text">
        بله، برای خریدهای بالای <strong>۱,۵۰۰,۰۰۰ تومان</strong> امکان پرداخت درب منزل با پرداخت بیعانه وجود دارد. برای هماهنگی با شماره <a href="tel:09181717011">۰۹۱۸۱۷۱۷۰۱۱</a> تماس بگیرید یا از طریق <a href="https://wa.me/989188111504" target="_blank" rel="noopener">واتساپ</a> پیام دهید.
        </div>
    </div>
</div>

<h2>پیگیری سفارش</h2>

<div class="m8-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
    <div class="m8-faq-question" itemprop="name">چگونه سفارش خود را در فروشگاه موبایل ۸ پیگیری کنم؟</div>
    <div class="m8-faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
        <div itemprop="text">
        پس از ارسال سفارش، <strong>کد رهگیری پست یا تیپاکس</strong> برای شما ارسال می‌شود. از طریق صفحه <a href="/order-tracking">رهگیری سفارشات</a> وضعیت مرسوله را بررسی کنید. همچنین می‌توانید با شماره <a href="tel:09181717011">۰۹۱۸۱۷۱۷۰۱۱</a> تماس بگیرید.
        </div>
    </div>
</div>

</div>

<p style="text-align: center; margin-top: 30px; font-size: 14px; line-height: 2;">
سوال دیگری دارید؟ از صفحه <a href="/contact-us">تماس با ما</a> اقدام کنید یا <a href="/about-us">درباره فروشگاه موبایل ۸</a> بیشتر بخوانید.
</p>

</article>';
}

function mobile8_terms_content() {
    return '
<article itemscope itemtype="https://schema.org/WebPage">

<div class="m8-terms-section">

<h1>شرایط و قوانین خرید از فروشگاه اینترنتی موبایل ۸</h1>
<p style="font-size: 15px; line-height: 2;">
لطفاً قبل از <strong>خرید آنلاین گوشی موبایل و لوازم جانبی</strong> از <strong>فروشگاه موبایل ۸</strong> (mobile8.ir)، شرایط و قوانین زیر را به دقت مطالعه فرمایید. ثبت سفارش به منزله پذیرش تمامی شرایط زیر می‌باشد.
</p>

<h2>شرایط خرید آنلاین موبایل و لوازم جانبی</h2>
<ul style="line-height: 2.2; font-size: 15px;">
    <li>تمامی قیمت‌های <strong>گوشی موبایل، تبلت، هندزفری، ساعت هوشمند و لوازم جانبی</strong> به <strong>تومان</strong> و بر اساس نرخ روز بازار محاسبه می‌شوند.</li>
    <li>قیمت محصولات دیجیتال ممکن است بر اساس نوسانات بازار بدون اطلاع قبلی تغییر کند.</li>
    <li>موجودی محصولات محدود است و ثبت سفارش به منزله رزرو محصول نیست.</li>
    <li>در صورت ناموجود شدن محصول پس از ثبت سفارش، مبلغ پرداختی به طور کامل عودت داده می‌شود.</li>
</ul>

<h2>شرایط ارسال سفارش به سراسر ایران</h2>
<ul style="line-height: 2.2; font-size: 15px;">
    <li>ارسال سفارشات از طریق <strong>پست</strong> و <strong>تیپاکس</strong> به تمام <strong>۳۱ استان ایران</strong> انجام می‌شود.</li>
    <li>در صورت درخواست مشتری، ارسال از طریق <strong>باربری</strong> یا <strong>اتوبوس</strong> نیز امکان‌پذیر است.</li>
    <li>هزینه ارسال بر عهده خریدار است و بر اساس وزن و مقصد محاسبه می‌شود.</li>
    <li>تیم <strong>موبایل ۸</strong> تلاش می‌کند سفارشات <strong>فردای روز ثبت</strong> ارسال گردند.</li>
    <li>مسئولیت آسیب‌های ناشی از حمل‌ونقل بر عهده شرکت حمل‌کننده می‌باشد.</li>
    <li>پس از ارسال، <strong>کد رهگیری</strong> برای مشتری ارسال می‌شود. از صفحه <a href="/order-tracking">رهگیری سفارش</a> استفاده کنید.</li>
</ul>

<h2>شرایط پرداخت</h2>
<ul style="line-height: 2.2; font-size: 15px;">
    <li>پرداخت آنلاین از طریق <strong>درگاه بانکی امن</strong> انجام می‌شود.</li>
    <li>امکان <strong>پرداخت درب منزل</strong> برای خریدهای بالای ۱,۵۰۰,۰۰۰ تومان با پرداخت بیعانه وجود دارد.</li>
    <li>برای هماهنگی پرداخت درب منزل با شماره <a href="tel:09181717011">۰۹۱۸۱۷۱۷۰۱۱</a> تماس بگیرید.</li>
</ul>

<h2>شرایط مرجوعی و بازگشت کالا</h2>
<ul style="line-height: 2.2; font-size: 15px;">
    <li>مرجوعی <strong>فقط در صورت خرابی محصول</strong> امکان‌پذیر است.</li>
    <li>تمامی محصولات (<strong>گوشی سامسونگ، آیفون اپل، شیائومی، هندزفری</strong> و ...) قبل از ارسال تست و بررسی می‌شوند.</li>
    <li>محصولاتی که با <strong>قیمت عمده</strong> فروخته می‌شوند، تحت هیچ شرایطی قابل مرجوعی نیستند.</li>
    <li>محصولات <strong>اوپن‌باکس</strong> قبل از ارسال تست شده و با اطلاع و رضایت مشتری ارسال می‌گردند.</li>
    <li>در صورت درخواست، <strong>ویدیوی تست محصول</strong> قبل از ارسال تهیه می‌شود.</li>
    <li>درخواست مرجوعی باید حداکثر تا <strong>۴۸ ساعت</strong> پس از دریافت محصول ثبت شود.</li>
    <li>هزینه ارسال مرجوعی بر عهده خریدار است مگر آن‌که خرابی از جانب فروشگاه باشد.</li>
</ul>

<h2>گارانتی محصولات دیجیتال</h2>
<ul style="line-height: 2.2; font-size: 15px;">
    <li>گارانتی محصولات بر اساس نوع کالا و برند (<strong>Samsung، Apple، Xiaomi، Huawei، JBL</strong>) متفاوت است و در صفحه محصول ذکر شده است.</li>
    <li>گارانتی شامل خرابی‌های ناشی از استفاده نادرست، ضربه یا آب‌خوردگی نمی‌شود.</li>
</ul>

<h2>حفظ حریم خصوصی مشتریان</h2>
<ul style="line-height: 2.2; font-size: 15px;">
    <li>اطلاعات شخصی مشتریان نزد <strong>فروشگاه موبایل ۸</strong> محفوظ بوده و به هیچ شخص ثالثی ارائه نخواهد شد.</li>
    <li>اطلاعات تماس مشتری صرفاً برای هماهنگی ارسال و پشتیبانی استفاده می‌شود.</li>
</ul>

<p style="text-align: center; margin-top: 40px; padding: 20px; background: #FFF3E0; border-radius: 10px; border-right: 4px solid #F57C00;">
    سوالی درباره شرایط خرید دارید؟ <a href="/faq">سوالات متداول</a> را بخوانید یا با ما تماس بگیرید:<br>
    <a href="tel:09181717011" style="font-weight:700;">۰۹۱۸۱۷۱۷۰۱۱</a> &nbsp; | &nbsp;
    <a href="mailto:Sfarnam73@gmail.com">Sfarnam73@gmail.com</a> &nbsp; | &nbsp;
    <a href="https://wa.me/989188111504" target="_blank" rel="noopener" style="font-weight:700;">واتساپ</a>
</p>

</div>

</article>';
}

function mobile8_contact_content() {
    return '
<article itemscope itemtype="https://schema.org/ContactPage">

<div style="max-width: 900px; margin: 0 auto; padding: 30px 15px;">

<h1>تماس با فروشگاه اینترنتی موبایل ۸ — مشاوره و خرید آنلاین</h1>
<p style="text-align: center; font-size: 16px; line-height: 2; margin-bottom: 30px;">
برای <strong>مشاوره خرید گوشی موبایل</strong>، <strong>تبلت</strong>، <strong>هندزفری</strong>، <strong>ساعت هوشمند</strong> و <strong>لوازم جانبی</strong> از طریق راه‌های زیر با <strong>فروشگاه موبایل ۸</strong> در تماس باشید. تیم ما آماده پاسخگویی و راهنمایی شماست.
</p>

<h2>راه‌های ارتباط مستقیم</h2>

<div class="m8-contact-cards" itemprop="mainEntity" itemscope itemtype="https://schema.org/OnlineStore">
    <meta itemprop="name" content="فروشگاه موبایل ۸">
    <meta itemprop="url" content="https://mobile8.ir">

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">📞</div>
        <h3>تماس تلفنی</h3>
        <a href="tel:09181717011" itemprop="telephone" style="font-size: 20px; font-weight: 700; color: #F57C00 !important;">۰۹۱۸۱۷۱۷۰۱۱</a>
        <p style="color: #999; margin-top: 10px; font-size: 13px;">شنبه تا پنج‌شنبه ۹ صبح تا ۹ شب</p>
    </div>

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">💬</div>
        <h3>واتساپ — پاسخ سریع</h3>
        <a href="https://wa.me/989188111504" target="_blank" rel="noopener" style="font-size: 18px; font-weight: 700; color: #25D366 !important;">۰۹۱۸۸۱۱۱۵۰۴</a>
        <p style="color: #999; margin-top: 10px; font-size: 13px;">مشاوره خرید و پیگیری سفارش</p>
    </div>

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">📧</div>
        <h3>ایمیل</h3>
        <a href="mailto:Sfarnam73@gmail.com" itemprop="email" style="font-size: 16px; font-weight: 700;">Sfarnam73@gmail.com</a>
        <p style="color: #999; margin-top: 10px; font-size: 13px;">پاسخگویی در کمتر از ۲۴ ساعت</p>
    </div>

</div>

<h2>شبکه‌های اجتماعی فروشگاه موبایل ۸</h2>

<div style="margin-top: 10px;">
<div class="m8-contact-cards">

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">📸</div>
        <h3>اینستاگرام</h3>
        <a href="https://instagram.com/mobile_8" target="_blank" rel="noopener" itemprop="sameAs" style="font-weight: 700; color: #E4405F !important;">mobile_8@</a>
        <p style="color: #999; margin-top: 10px; font-size: 13px;">جدیدترین محصولات و تخفیف‌ها</p>
    </div>

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">📱</div>
        <h3>کانال تلگرام</h3>
        <a href="https://t.me/sinafarnam8" target="_blank" rel="noopener" itemprop="sameAs" style="font-weight: 700; color: #0088CC !important;">sinafarnam8@</a>
        <p style="color: #999; margin-top: 10px; font-size: 13px;">اطلاع‌رسانی محصولات جدید</p>
    </div>

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">🏪</div>
        <h3>فروشگاه آنلاین</h3>
        <p style="font-weight: 600;"><a href="/" style="color: #F57C00 !important;">mobile8.ir</a></p>
        <p style="color: #999; margin-top: 5px; font-size: 13px;">خرید آنلاین با ارسال به سراسر ایران<br>استان‌های کردستان و همدان</p>
    </div>

</div>
</div>

<h2>خدمات مشتریان فروشگاه موبایل ۸</h2>
<p style="line-height: 2.2; font-size: 15px;">
تیم پشتیبانی <strong>فروشگاه موبایل ۸</strong> آماده ارائه خدمات زیر به شماست:
</p>
<ul style="line-height: 2.2; font-size: 15px;">
    <li><strong>مشاوره خرید</strong> — راهنمایی در انتخاب بهترین گوشی سامسونگ، آیفون یا شیائومی</li>
    <li><strong>پیگیری سفارش</strong> — بررسی وضعیت ارسال از طریق <a href="/order-tracking">صفحه رهگیری</a></li>
    <li><strong>پشتیبانی پس از فروش</strong> — رسیدگی به مرجوعی و گارانتی</li>
    <li><strong>خرید عمده</strong> — قیمت ویژه برای خریدهای عمده موبایل و لوازم جانبی</li>
</ul>

<p style="text-align: center; margin-top: 30px; font-size: 14px; line-height: 2;">
<a href="/faq">سوالات متداول</a> &nbsp;|&nbsp; <a href="/terms">شرایط و قوانین خرید</a> &nbsp;|&nbsp; <a href="/about-us">درباره فروشگاه موبایل ۸</a>
</p>

<p style="text-align: center; margin-top: 20px; color: #999; font-size: 13px;">
طراحی سایت: <a href="https://sinafarnam.ir" target="_blank" rel="nofollow noopener">سینا فرنام</a>
</p>

</div>

</article>';
}

function mobile8_tracking_content() {
    return '
<article itemscope itemtype="https://schema.org/WebPage">

<div style="max-width: 700px; margin: 0 auto; padding: 40px 15px;">

<h1 style="text-align: center;">رهگیری سفارش فروشگاه موبایل ۸ — پیگیری مرسوله پستی و تیپاکس</h1>
<p style="text-align: center; line-height: 2; color: #666; margin-bottom: 30px; font-size: 15px;">
پس از <strong>خرید آنلاین گوشی موبایل، تبلت، هندزفری و لوازم جانبی</strong> از <strong>فروشگاه موبایل ۸</strong>، کد رهگیری برای شما ارسال می‌شود. با استفاده از لینک‌های زیر وضعیت مرسوله خود را پیگیری کنید.
</p>

<h2 style="text-align: center;">رهگیری آنلاین مرسوله</h2>

<div style="display: grid; gap: 15px; max-width: 400px; margin: 0 auto 30px; text-align: center;">
    <a href="https://tracking.post.ir" target="_blank" rel="noopener" style="display: block; padding: 15px 25px; background: #F57C00; color: #fff !important; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 16px;">
        📮 رهگیری مرسوله پستی
    </a>
    <a href="https://tipax.ir/tracking" target="_blank" rel="noopener" style="display: block; padding: 15px 25px; background: #1976D2; color: #fff !important; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 16px;">
        🚚 رهگیری سفارش تیپاکس
    </a>
</div>

<h2 style="text-align: center;">مراحل پیگیری سفارش</h2>
<ol style="line-height: 2.5; font-size: 15px; max-width: 500px; margin: 0 auto 30px;">
    <li><strong>ثبت سفارش</strong> — سفارش شما در سیستم ثبت می‌شود</li>
    <li><strong>بررسی و تست محصول</strong> — کارشناسان ما محصول را تست می‌کنند</li>
    <li><strong>بسته‌بندی و ارسال</strong> — تلاش می‌شود فردای روز سفارش ارسال شود</li>
    <li><strong>ارسال کد رهگیری</strong> — کد رهگیری پست یا تیپاکس برای شما ارسال می‌شود</li>
    <li><strong>تحویل مرسوله</strong> — بین ۱ تا ۵ روز کاری به دست شما می‌رسد</li>
</ol>

<div style="background: #FFF3E0; padding: 25px; border-radius: 12px; border-right: 4px solid #F57C00; text-align: center;">
    <h3 style="margin-top: 0;">کد رهگیری ندارید؟</h3>
    <p style="margin: 0; line-height: 2;">
        با شماره <a href="tel:09181717011" style="font-weight: 700; color: #F57C00 !important;">۰۹۱۸۱۷۱۷۰۱۱</a> تماس بگیرید<br>
        یا در <a href="https://wa.me/989188111504" target="_blank" rel="noopener" style="font-weight: 700; color: #25D366 !important;">واتساپ</a> پیام دهید
    </p>
</div>

[woocommerce_order_tracking]

<p style="text-align: center; margin-top: 30px; font-size: 14px; line-height: 2;">
<a href="/faq">سوالات متداول</a> &nbsp;|&nbsp; <a href="/terms">شرایط و قوانین خرید</a> &nbsp;|&nbsp; <a href="/contact-us">تماس با ما</a>
</p>

</div>

</article>';
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
