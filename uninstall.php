<?php
/**
* Uninstaller
*
* Uninstall the plugin by removing any options from the database
*
* @package  simple-embed-code
* @since    1.6
*/

// If the uninstall was not called by WordPress, exit

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// Delete any options

delete_site_option( 'artiss_code_embed' );
