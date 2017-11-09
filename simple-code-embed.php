<?php
/*
Plugin Name: Code Embed
Plugin URI: https://wordpress.org/plugins/simple-embed-code/
Description: Code Embed provides a very easy and efficient way to embed code (JavaScript and HTML) in your posts and pages.
Version: 2.2.2
Author: David Artiss
Author URI: https://artiss.blog
Text Domain: simple-embed-code
*/

/**
* Code Embed
*
* Embed code into a post
*
* @package	Code-Embed
* @since	1.6
*/

define( 'code_embed_version', '2.2.2' );

// Include all the various functions

$functions_dir = plugin_dir_path( __FILE__ ) . 'includes/';

include_once( $functions_dir . 'initialise.php' );		    			// Initialisation scripts

if ( is_admin() ) {

	include_once( $functions_dir . 'admin-config.php' );				// Various administration config. options

} else {

	include_once( $functions_dir . 'add-scripts.php' );		       		// Add scripts to the main theme

	include_once( $functions_dir . 'add-embeds.php' );		        	// Filter to apply code embeds

}
?>