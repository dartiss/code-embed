<?php
/**
 * Initialization script
 *
 * Run every time the plugin is initialized.
 *
 * @package  simple-embed-code
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialization
 *
 * All initial processes.
 */
function ce_initialisation() {

	// Add excerpt filter, if required.

	$options = get_option( 'artiss_code_embed' );
	if ( isset( $options['excerpt'] ) && '1' === $options['excerpt'] ) {
		add_filter( 'the_excerpt', 'ce_filter', 1 );
	}

	// Check if the plugin has upgraded and, if so, perform further actions.

	$version = get_option( 'code_embed_version' );

	if ( version_compare( CODE_EMBED_VERSION, $version, '!=' ) ) {

		// If options don't exist, create an empty array.

		if ( ! is_array( $options ) ) {
			$options = array();
		}

		// Because of upgrading, check each option - if not set, apply default.

		$default_array = array(
			'opening_ident' => '{{',
			'keyword_ident' => 'CODE',
			'closing_ident' => '}}',
			'excerpt'       => '',
		);

		// Merge existing and default options - any missing from existing will take the default settings.

		$new_options = array_merge( $default_array, $options );

		// Update the options, if changed.

		if ( $options !== $new_options ) {
			update_option( 'artiss_code_embed', $new_options );
		}

		// Finally, update the saved version.

		update_option( 'code_embed_version', CODE_EMBED_VERSION );
	}
}

add_action( 'init', 'ce_initialisation' );
