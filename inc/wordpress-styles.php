<?php
/**
 * Remove estilos globais do Wordpress das páginas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// Remove estilos globais do Wordpress
add_action( 'wp_enqueue_scripts', function () {
  if ( !is_admin() ) {
    wp_dequeue_style( 'global-styles' );
    remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
    remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
    remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-blocks-style' ); // Remove WooCommerce block CSS
  }
});