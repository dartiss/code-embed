<?php
/**
 * Meta boxes
 *
 * Functions related to meta-box management.
 *
 * @package simple-embed-code
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove Custom Fields
 *
 * Remove the custom field meta boxes if the user doesn't have the unfiltered HTML permissions.
 *
 * @param    string  $screen        The screen identifier.
 * @param    string  $context       The screen context for which to display meta boxes.
 * @param    boolean $data_object   Gets passed to the meta box callback function as the first parameter.
 */
function sec_remove_custom_fields( $screen, $context, $data_object ) {

	if ( ! current_user_can( 'unfiltered_html' ) ) {

		$options = get_option( 'artiss_code_embed' );

		if ( '1' !== $options['meta_box'] ) {
			remove_meta_box( 'postcustom', $screen, $context );
		}
	}
}

add_action( 'do_meta_boxes', 'sec_remove_custom_fields', 1, 3 );
