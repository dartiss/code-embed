<?php
/**
* Add Scripts
*
* Add CSS to the main theme
*
* @package  simple-embed-code
*/

/**
* Add scripts to theme
*
* Add styles to the main theme
*
* @since        2.0
*/

function ce_main_scripts() {

	wp_register_style( 'ce_responsive', plugins_url( 'css/video-container.min.css', dirname( __FILE__ ) ) );

	wp_enqueue_style( 'ce_responsive' );

}

add_action( 'wp_enqueue_scripts', 'ce_main_scripts' );
