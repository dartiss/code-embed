<?php
/**
 * Security
 *
 * Functions related to sanitizing Code Embed meta values.
 *
 * @package simple-embed-code
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize Code Embed meta on every write
 *
 * Filter that fires on every call to update_metadata / add_metadata — including the
 * wp_ajax_add_meta AJAX handler and the REST API, not just save_post.
 *
 * @param mixed  $check      Null to allow the operation, non-null to short-circuit.
 * @param int    $object_id  Post ID.
 * @param string $meta_key   Meta key being written.
 * @param mixed  $meta_value Meta value being written.
 * @return mixed             Null (to proceed with the write).
 */
function sec_sanitize_meta_on_write( $check, $object_id, $meta_key, $meta_value ) {

	// Allow admins / editors with unfiltered_html to write without restriction.
	if ( current_user_can( 'unfiltered_html' ) ) {
		return $check;
	}

	$options = get_option( 'artiss_code_embed' );

	if ( ! is_array( $options ) || empty( $options['keyword_ident'] ) ) {
		return $check;
	}

	$prefix = $options['keyword_ident'];

	// Only act on meta keys that belong to this plugin.
	if ( substr( $meta_key, 0, strlen( $prefix ) ) !== $prefix ) {
		return $check;
	}

	// Strip dangerous markup while preserving safe HTML.
	$clean = wp_kses_post( $meta_value );

	if ( $clean === $meta_value ) {
		// Value is already clean — let the normal write proceed.
		return $check;
	}

	// The value was dirty. Remove this filter temporarily to avoid infinite recursion, write the sanitized value ourselves, then
	// re-add the filter and short-circuit the original write.
	remove_filter( 'update_post_metadata', 'sec_sanitize_meta_on_write', 10 );
	remove_filter( 'add_post_metadata', 'sec_sanitize_meta_on_write', 10 );

	update_post_meta( $object_id, $meta_key, $clean );

	add_filter( 'update_post_metadata', 'sec_sanitize_meta_on_write', 10, 4 );
	add_filter( 'add_post_metadata', 'sec_sanitize_meta_on_write', 10, 4 );

	// Return a non-null value to short-circuit the original (unsanitized) write.
	return true;
}

add_filter( 'update_post_metadata', 'sec_sanitize_meta_on_write', 10, 4 );
add_filter( 'add_post_metadata', 'sec_sanitize_meta_on_write', 10, 4 );
