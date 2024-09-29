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
 * @param    string  $post_id   Post ID.
 * @param    string  $post      Post object.
 * @param    boolean $update    Whether this is an existing post being updated.
 */
function sec_check_post_fields( $post_id, $post, $update ) {

	$options = get_option( 'artiss_code_embed' );

	// Check if it's an autosave or if the current user has the 'unfiltered_html' capability.
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( current_user_can( 'unfiltered_html' ) ) ) {
		return;
	}

	// Fetch all post meta (custom fields) associated with the post.
	$custom_fields = get_post_meta( $post_id );

	// If there are custom fields, read through them.
	if ( ! empty( $custom_fields ) ) {

		foreach ( $custom_fields as $key => $value ) {

			// Check to see if any begining with this plugin's prefix.
			if ( substr( $key, 0, strlen( $options['keyword_ident'] ) ) === $options['keyword_ident'] ) {

				// Filter the meta value.
				$new_value = wp_kses_post( $value[0] );

				// Now write out the new value.
				update_post_meta( $post_id, $key, $new_value );
			}
		}
	}
}

add_action( 'save_post', 'sec_check_post_fields', 10, 3 );
