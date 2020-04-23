<?php
/**
Plugin Name: Code Embed
Plugin URI: https://github.com/dartiss/code-embed
Description: Code Embed provides a very easy and efficient way to embed code (JavaScript and HTML) in your posts and pages.
Version: 2.3.3
Author: David Artiss
Author URI: https://artiss.blog
Text Domain: simple-embed-code

@package Code-Embed
 */

define( 'CODE_EMBED_VERSION', '2.3.3' );

// Include all the various functions.

$functions_dir = plugin_dir_path( __FILE__ ) . 'includes/';

require_once $functions_dir . 'initialise.php';        // Initialisation scripts.

if ( is_admin() ) {

	require_once $functions_dir . 'admin-config.php';  // Various administration config. options.

} else {

	require_once $functions_dir . 'add-scripts.php';   // Add scripts to the main theme.

	require_once $functions_dir . 'add-embeds.php';    // Filter to apply code embeds.

}
