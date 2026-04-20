<?php
/**
 * Add CSS scripts
 *
 * Add CSS to the main theme.
 *
 * @package  simple-embed-code
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add styles to theme
 *
 * Registers and enqueues the responsive video container stylesheet.
 */
function code_embed_css_scripts() {

	wp_register_style( 'code_embed_responsive', plugins_url( 'css/video-container.min.css', __DIR__ ), array(), CODE_EMBED_VERSION );

	wp_enqueue_style( 'code_embed_responsive' );
}

add_action( 'wp_enqueue_scripts', 'code_embed_css_scripts' );
