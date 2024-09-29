<?php
/**
 * Code Embed
 *
 * @package           Code-Embed
 * @author            David Artiss
 * @license           GPL-2.0-or-later
 *
 * Plugin Name:       Code Embed
 * Plugin URI:        https://wordpress.org/plugins/simple-embed-code/
 * Description:       Code Embed provides a very easy and efficient way to embed code (JavaScript and HTML) in your posts and pages.
 * Version:           2.5
 * Requires at least: 4.6
 * Requires PHP:      7.4
 * Author:            David Artiss
 * Author URI:        https://artiss.blog
 * Text Domain:       simple-embed-code
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

define( 'CODE_EMBED_VERSION', '2.5' );

// Define global to hold the plugin base file name.

if ( ! defined( 'CODE_EMBED_PLUGIN_BASE' ) ) {
	define( 'CODE_EMBED_PLUGIN_BASE', plugin_basename( __FILE__ ) );
}

// Include all the various functions.

$functions_dir = plugin_dir_path( __FILE__ ) . 'includes/';

require_once $functions_dir . 'initialise.php';    // Initialisation scripts.

require_once $functions_dir . 'add-scripts.php';   // Add scripts to the main theme.

require_once $functions_dir . 'add-embeds.php';    // Filter to apply code embeds.

require_once $functions_dir . 'shared.php';        // Functions shared across all my plugins.

require_once $functions_dir . 'screens.php';       // Add settings and tools screens.

require_once $functions_dir . 'secure.php';        // Security functionality.
