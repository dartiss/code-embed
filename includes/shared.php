<?php
/**
 * Shared Functions
 *
 * A group of functions shared across my plugins, for consistency.
 *
 * @package simple-embed-code
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add meta to plugin details
 *
 * Add options to plugin meta line
 *
 * @version  1.1
 * @param    string $links  Current links.
 * @param    string $file   File in use.
 * @return   string         Links, now with settings added.
 */
function sec_plugin_meta( $links, $file ) {

	if ( false !== strpos( $file, 'simple-code-embed.php' ) ) {

		$links = array_merge(
			$links,
			array( '<a href="https://github.com/dartiss/code-embed">' . __( 'Github', 'simple-embed-code' ) . '</a>' ),
			array( '<a href="https://wordpress.org/support/plugin/simple-embed-code">' . __( 'Support', 'simple-embed-code' ) . '</a>' ),
			array( '<a href="https://artiss.blog/donate">' . __( 'Donate', 'simple-embed-code' ) . '</a>' ),
			array( '<a href="https://wordpress.org/support/plugin/simple-embed-code/reviews/?filter=5" title="' . __( 'Rate the plugin on WordPress.org', 'simple-embed-code' ) . '" style="color: #ffb900">' . str_repeat( '<span class="dashicons dashicons-star-filled" style="font-size: 16px; width:16px; height: 16px"></span>', 5 ) . '</a>' ),
		);
	}

	return $links;
}

add_filter( 'plugin_row_meta', 'sec_plugin_meta', 10, 2 );

/**
 * Modify actions links.
 *
 * Add or remove links for the actions listed against this plugin
 *
 * @version  1.1
 * @param    string $actions      Current actions.
 * @param    string $plugin_file  The plugin.
 * @return   string               Actions, now with deactivation removed!
 */
function sec_action_links( $actions, $plugin_file ) {

	// Make sure we only perform actions for this specific plugin!
	if ( strpos( $plugin_file, 'simple-code-embed.php' ) !== false ) {

		// Add link to the settings page.
		if ( current_user_can( 'manage_options' ) ) {
			array_unshift( $actions, '<a href="admin.php?page=ce-options">' . __( 'Settings', 'simple-embed-code' ) . '</a>' );
		}
	}

	return $actions;
}

add_filter( 'plugin_action_links', 'sec_action_links', 10, 2 );

/**
 * WordPress Requirements Check
 *
 * Deactivate the plugin if certain requirements are not met.
 *
 * @version 1.1
 */
function sec_requirements_check() {

	$reason = '';

	// Grab the plugin details.

	$plugins = get_plugins();
	$name    = $plugins[ CODE_EMBED_PLUGIN_BASE ]['Name'];

	// Check for a fork.

	if ( function_exists( 'calmpress_version' ) || function_exists( 'classicpress_version' ) ) {

		/* translators: 1: The plugin name. */
		$reason .= '<li>' . sprintf( __( 'A fork of WordPress was detected. %1$s has not been tested on this fork and, as a consequence, the author will not provide any support.', 'simple-embed-code' ), $name ) . '</li>';

	}

	// If a requirement is not met, output the message and stop the plugin.

	if ( '' !== $reason ) {

		// Deactivate this plugin.

		deactivate_plugins( PLUGIN_NAME_PLUGIN_BASE );

		// Set up a message and output it via wp_die.

		/* translators: 1: The plugin name. */
		$message = '<p><b>' . sprintf( __( '%1$s has been deactivated', 'simple-embed-code' ), $name ) . '</b></p><p>' . __( 'Reason:', 'simple-embed-code' ) . '</p><ul>' . $reason . '</ul>';

		$allowed = array(
			'p'  => array(),
			'b'  => array(),
			'ul' => array(),
			'li' => array(),
		);

		wp_die( wp_kses( $message, $allowed ), '', array( 'back_link' => true ) );
	}
}

add_action( 'admin_init', 'sec_requirements_check' );
