<?php
/**
* Initialisation script
*
* Run everytime the plugin is initialised
*
* @package  simple-embed-code
* @since    2.2
*/

function ce_initialisation() {

	// Add exerpt filter, if required

	$options = get_option( 'artiss_code_embed' );
	if ( isset( $options['excerpt'] ) && 1 === $options['excerpt'] ) {
		add_filter( 'the_excerpt', 'ce_filter', 1 );
	}

	// Check if plugin has upgraded and, if so, perform further actions

	$version = get_option( 'code_embed_version' );

	if ( CODE_EMBED_VERSION !== $version ) {

		// Set up default option values (if not already set)

		$options = get_option( 'artiss_code_embed' );

		// If options don't exist, create an empty array

		if ( ! is_array( $options ) ) {
			$options = array();
		}

		// Because of upgrading, check each option - if not set, apply default

		$default_array = array(
			'opening_ident' => '{{',
			'keyword_ident' => 'CODE',
			'closing_ident' => '}}',
			'excerpts'      => '',
		);

		// Merge existing and default options - any missing from existing will take the default settings

		$new_options = array_merge( $default_array, $options );

		// Update the options, if changed, and return the result

		if ( $options !== $new_options ) {
			update_option( 'artiss_code_embed', $new_options );
		}
	}
}

add_action( 'init', 'ce_initialisation' );
