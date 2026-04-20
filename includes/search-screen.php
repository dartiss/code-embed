<?php
/**
 * Code Embed Search
 *
 * Search for specific code embeds across site content.
 *
 * @package  simple-embed-code
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
<h1><?php echo esc_html( __( 'Code Embed search', 'simple-embed-code' ) ); ?></h1>

<?php
echo '<p>' . esc_html__( 'Enter the suffix to search for below and press the \'Search\' button to view the results. Further help can be found by clicking on the Help tab at the top right-hand of the screen.', 'simple-embed-code' ) . '</p>';
?>

<?php

// Get the suffix from the submitted field.

if ( ! empty( $_POST['ce_suffix'] || '0' === $_POST['ce_suffix'] ) && check_admin_referer( 'code-embed-search', 'code_embed_search_nonce' ) ) {
	$suffix = sanitize_text_field( wp_unslash( $_POST['ce_suffix'] ) );
} else {
	$suffix = '';
}

// Fetch options into an array.

$options = get_option( 'artiss_code_embed' );
?>

<form method="post" action="<?php echo esc_url( admin_url( 'tools.php?page=ce-search' ) ); ?>">

	<?php echo esc_html( $options['opening_ident'] ) . esc_html( $options['keyword_ident'] ); ?>

	<input type="text" size="6" name="ce_suffix" aria-label="<?php esc_attr_e( 'Code suffix', 'simple-embed-code' ); ?>" value="<?php echo esc_attr( $suffix ); ?>"/>

	<?php echo esc_html( $options['closing_ident'] ); ?>

	<?php wp_nonce_field( 'code-embed-search', 'code_embed_search_nonce', true, true ); ?>

	<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Search', 'simple-embed-code' ); ?>"/>

</form>

<?php
if ( '' !== $suffix ) {

	global $wpdb;
	$meta    = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value, post_title, ID FROM $wpdb->postmeta, $wpdb->posts WHERE meta_key = %s AND post_id = ID AND post_status NOT IN ('trash', 'auto-draft', 'inherit') AND post_type IN ('post', 'page') ORDER BY meta_value", $options['keyword_ident'] . $suffix ) ); // @codingStandardsIgnoreLine -- being used for a simple, ad-hoc search feature in admin. Unlikely to be used much and caching on this is overkill
	$records = $wpdb->num_rows;

	if ( 0 < $records ) {

		echo '<table class="widefat striped">';
		$prev_html = '';

		foreach ( $meta as $meta_data ) {
			$html       = $meta_data->meta_value;
			$post_title = $meta_data->post_title;
			$post_ident = $meta_data->ID;

			echo '<tr style="border-bottom-style: dotted; border-top-style: dotted;">' . "\n";
			echo '<td><a href="' . esc_url( get_edit_post_link( esc_attr( $post_ident ) ) ) . '" >' . esc_html( $post_title ) . "</a></td>\n";

			echo '<td><textarea aria-label="' . esc_attr__( 'Embed code', 'simple-embed-code' ) . '" readonly="readonly" rows="3" cols="80">' . esc_html( $html ) . "</textarea></td>\n";
			echo "</tr>\n";

			$prev_html = $html;
		}

		echo "</table>\n";

	} else {

		ce_report_error( esc_html__( 'No posts were found containing that embed code.', 'simple-embed-code' ), 'Code Embed', true );

	}
}
?>

</div>
