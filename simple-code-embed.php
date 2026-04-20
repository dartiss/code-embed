<?php
/**
 * Code Embed
 *
 * @package           simple-embed-code
 * @author            David Artiss
 * @license           GPL-2.0-or-later
 * @since             1.0
 * @version           2.6
 *
 * Plugin Name:       Code Embed
 * Plugin URI:        https://wordpress.org/plugins/simple-embed-code/
 * Description:       Code Embed provides a very easy and efficient way to embed code (JavaScript, CSS and HTML) in your posts and pages.
 * Version:           2.6
 * Requires at least: 4.6
 * Requires PHP:      7.4
 * Author:            David Artiss
 * Author URI:        https://artiss.blog
 * Text Domain:       simple-embed-code
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define globals.

if ( ! defined( 'CODE_EMBED_VERSION' ) ) {
	define( 'CODE_EMBED_VERSION', '2.6' );
}

if ( ! defined( 'CODE_EMBED_PLUGIN_BASE' ) ) {
	define( 'CODE_EMBED_PLUGIN_BASE', plugin_basename( __FILE__ ) );
}

// Include all the various functions.

require_once plugin_dir_path( __FILE__ ) . 'includes/initialise.php';       // Initialization scripts.

require_once plugin_dir_path( __FILE__ ) . 'includes/add-css-scripts.php';  // Add scripts to the main theme.

require_once plugin_dir_path( __FILE__ ) . 'includes/add-embeds.php';       // Filter to apply code embeds.

require_once plugin_dir_path( __FILE__ ) . 'includes/shared.php';           // Functions shared across all my plugins.

require_once plugin_dir_path( __FILE__ ) . 'includes/screens.php';          // Add settings and tools screens.

require_once plugin_dir_path( __FILE__ ) . 'includes/secure.php';           // Security functionality.
