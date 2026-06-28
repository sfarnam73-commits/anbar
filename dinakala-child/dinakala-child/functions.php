<?php
/**
 * Mobile 8 Store - Child Theme Functions
 * Website: mobile8.ir
 * Designer: Sina Farnam - https://sinafarnam.ir
 */

function dina_child_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'dina-style' ), DI_VER );
}
add_action( 'wp_enqueue_scripts', 'dina_child_enqueue_styles', 10010 );

function mobile8_footer_credit( $text ) {
    $text = 'تمامی حقوق مادی و معنوی برای <strong>موبایل ۸</strong> محفوظ است. | طراحی سایت: <a href="https://sinafarnam.ir" target="_blank" rel="nofollow">سینا فرنام</a>';
    return $text;
}
add_filter( 'dina_copyright_text', 'mobile8_footer_credit' );

function mobile8_replace_branding() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('body *:not(script):not(style)').forEach(function(el) {
            if (el.children.length === 0 && el.textContent) {
                if (el.textContent.includes('دیناکالا') || el.textContent.includes('DinaKala') || el.textContent.includes('دینا کالا')) {
                    el.textContent = el.textContent
                        .replace(/دیناکالا/g, 'موبایل ۸')
                        .replace(/دینا کالا/g, 'موبایل ۸')
                        .replace(/DinaKala/gi, 'Mobile 8');
                }
            }
        });
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'mobile8_replace_branding', 9999 );
