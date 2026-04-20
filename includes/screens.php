<?php
/**
 * Add screens
 *
 * Add settings and tools screens.
 *
 * @package  simple-embed-code
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Code Embed Menu
 *
 * Add a new option to the Admin menu and sub-menus.
 */
function ce_menu() {

	// Add search sub-menu.

	global $ce_search_hook;

	$ce_search_hook = add_submenu_page( 'tools.php', __( 'Code Embed Search', 'simple-embed-code' ), __( 'Code Search', 'simple-embed-code' ), 'edit_posts', 'ce-search', 'ce_search' );

	add_action( 'load-' . $ce_search_hook, 'ce_add_search_help' );

	// Add options sub-menu.

	global $ce_options_hook;

	$ce_options_hook = add_submenu_page( 'options-general.php', __( 'Code Embed Settings', 'simple-embed-code' ), __( 'Code Embed', 'simple-embed-code' ), 'manage_options', 'ce-options', 'ce_options' );

	add_action( 'load-' . $ce_options_hook, 'ce_add_options_help' );
}

add_action( 'admin_menu', 'ce_menu' );

/**
 * Add Options Help
 *
 * Add help tab to options screen.
 *
 * @uses ce_options_help()  Return help text.
 */
function ce_add_options_help() {

	global $ce_options_hook;
	$screen = get_current_screen();

	if ( $screen->id !== $ce_options_hook ) {
		return;
	}

	$screen->add_help_tab(
		array(
			'id'      => 'ce-options-help-tab',
			'title'   => __( 'Help', 'simple-embed-code' ),
			'content' => ce_options_help(),
		)
	);

	$screen->set_help_sidebar( ce_help_sidebar() );
}

/**
 * Add Search Help
 *
 * Add help tab to search screen.
 *
 * @uses ce_search_help()  Return help text.
 */
function ce_add_search_help() {

	global $ce_search_hook;
	$screen = get_current_screen();

	if ( $screen->id !== $ce_search_hook ) {
		return;
	}

	$screen->add_help_tab(
		array(
			'id'      => 'ce-search-help-tab',
			'title'   => __( 'Help', 'simple-embed-code' ),
			'content' => ce_search_help(),
		)
	);

	$screen->set_help_sidebar( ce_help_sidebar() );
}

/**
 * Code Embed Options
 *
 * Define an option screen.
 */
function ce_options() {

	include_once __DIR__ . '/options-screen.php';
}

/**
 * Code Embed Search
 *
 * Define the search screen.
 */
function ce_search() {

	include_once __DIR__ . '/search-screen.php';
}

/**
 * Code Embed Options Help
 *
 * Return help text for options screen.
 *
 * @return string  Help Text.
 */
function ce_options_help() {

	$help_text  = '<p>' . __( 'Use this screen to modify the various settings, including the identifiers and keyword used to specify your embedded code.', 'simple-embed-code' ) . '</p>';
	$help_text .= '<p>' . __( 'The first option specifies whether code embed requests should work in excerpts.', 'simple-embed-code' ) . '</p>';
	$help_text .= '<p>' . __( 'The keyword is the name used for your custom field. The custom field\'s value is the code that you wish to embed.', 'simple-embed-code' ) . '</p>';
	$help_text .= '<p>' . __( 'The keyword, sandwiched with the identifier before and after, is what you then need to add to your post or page to activate the embed code.', 'simple-embed-code' ) . '</p>';

	return $help_text;
}

/**
 * Code Embed Search Help
 *
 * Return help text for search screen.
 *
 * @return string  Help Text.
 */
function ce_search_help() {

	$help_text  = '<p>' . __( 'This screen allows you to search for posts and pages that a particular code embed has been used in.', 'simple-embed-code' ) . '</p>';
	$help_text .= '<p>' . __( 'Simply enter the code suffix that you wish to search for and press the \'Search\' button to display a list of all the posts using it. In addition, the code will be shown alongside it. Click on the post name to edit the post.', 'simple-embed-code' ) . '</p>';
	$help_text .= '<p>' . __( 'The search results are grouped together in matching code groups, so posts with the same code will be shown together with the same color background.', 'simple-embed-code' ) . '</p>';

	return $help_text;
}

/**
 * Code Embed Help Sidebar
 *
 * Return sidebar help text.
 *
 * @return string  Help Text.
 */
function ce_help_sidebar() {

	$help_text  = '<p><strong>' . __( 'For more information:', 'simple-embed-code' ) . '</strong></p>';
	$help_text .= '<p><a href="' . esc_url( 'https://wordpress.org/plugins/simple-embed-code/' ) . '">' . __( 'Instructions', 'simple-embed-code' ) . '</a></p>';
	$help_text .= '<p><a href="' . esc_url( 'https://github.com/dartiss/code-embed' ) . '">' . __( 'Github (code and issues)', 'simple-embed-code' ) . '</a></p>';
	$help_text .= '<p><a href="' . esc_url( 'https://wordpress.org/support/plugin/simple-embed-code' ) . '">' . __( 'Support Forum', 'simple-embed-code' ) . '</a></p>';

	return $help_text;
}
