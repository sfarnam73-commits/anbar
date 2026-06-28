<?php
/**
 * Enqueue script and styles for child theme
 */
function dina_child_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'dina-style' ), DI_VER );
}
add_action( 'wp_enqueue_scripts', 'dina_child_enqueue_styles', 10010 );
