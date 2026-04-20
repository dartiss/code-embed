<?php
/**
 * Uninstaller
 *
 * Uninstall the plugin by removing any options from the database.
 *
 * @package  simple-embed-code
 */

// If the uninstall was not called by WordPress, exit.

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete any options.

if ( is_multisite() ) {

	// Loop through every sub-site and remove its options.

	$sites = get_sites(
		array(
			'fields' => 'ids',
		),
	);

	foreach ( $sites as $site_id ) {
		switch_to_blog( $site_id );
		delete_option( 'artiss_code_embed' );
		delete_option( 'code_embed_version' );
		restore_current_blog();
	}
} else {

	// Delete options for a single site installation.

	delete_option( 'artiss_code_embed' );
	delete_option( 'code_embed_version' );
}
