<?php
/**
* Code Embed Search
*
* Allow the user to change the default options
*
* @package  simple-embed-code
* @since    1.6
*/
?>
<div class="wrap">
<h1><?php echo esc_html( ucwords( __( 'Code Embed search', 'simple-embed-code' ) ) ); ?></h1>

<?php
echo '<p>' . esc_html__( 'Enter the suffix to search for below and press the \'Search\' button to view the results. Further help can be found by clicking on the Help tab at the top right-hand of the screen.', 'simple-embed-code' ) . '</p>';
?>

<?php

// Get the suffix from the submitted field

if ( ! empty( $_POST['ce_suffix'] ) && check_admin_referer( 'code-embed-search', 'code_embed_search_nonce' ) ) { // Input var okay.
	$suffix = htmlspecialchars( sanitize_title( wp_unslash( $_POST['ce_suffix'] ) ) );  // Input var okay.
} else {
	$suffix = '';
}

// Fetch options into an array

$options = get_option( 'artiss_code_embed' );
?>

<form method="post" action="<?php echo esc_html( get_bloginfo( 'wpurl' ) ) . '/wp-admin/tools.php?page=ce-search'; ?>">

	<?php echo esc_html( $options['opening_ident'] ) . esc_html( $options['keyword_ident'] ); ?>

	<input type="text" size="6" name="ce_suffix" value="<?php echo esc_html( $suffix ); ?>"/>

	<?php echo esc_html( $options['closing_ident'] ); ?>

	<?php wp_nonce_field( 'code-embed-search', 'code_embed_search_nonce', true, true ); ?>

	<input type="submit" name="Submit" class="button-primary" value="<?php esc_html_e( 'Search', 'simple-embed-code' ); ?>"/>

</form>

<?php
if ( '' !== $suffix ) {

	global $wpdb;
	$meta    = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value, post_title, ID FROM $wpdb->postmeta, $wpdb->posts WHERE meta_key = %s AND post_id = ID ORDER BY meta_value", esc_html( $options['keyword_ident'] ) . esc_html( $suffix ) ) ); // @codingStandardsIgnoreLine -- being used for a simple, ad-hoc search feature in admin. Unlikely to be used much and caching on this is overkill
	$records = $wpdb->num_rows;

	if ( 0 < $records ) {

		echo '<table class="form-table">';
		$color1    = 'dfdfdf';
		$color2    = 'ececec';
		$color     = $color1;
		$prev_html = '';

		foreach ( $meta as $meta_data ) {
			$html       = $meta_data->meta_value;
			$post_title = $meta_data->post_title;
			$post_id    = $meta_data->ID;

			// Switch background colours as the code changes

			if ( $html !== $prev_html ) {
				if ( $color === $color1 ) {
					$color = $color2;
				} else {
					$color = $color1; }
			}

			echo '<tr style="background-color: #' . esc_html( $color ) . "\">\n";
			echo '<td><a href="' . esc_html( home_url() ) . '/wp-admin/post.php?post=' . esc_html( $post_id ) . '&action=edit" style="color: #f00;">' . esc_html( $post_title ) . "</td>\n";
			echo '<td><textarea readonly="readonly" rows="3" cols="80">' . esc_html( $html ) . "</textarea></td>\n";
			echo "</tr>\n";

			$prev_html = $html;
		}

		echo "</table>\n";

	} else {

		echo '<p style="color: #f00">' . esc_html__( 'No posts were found containing that embed code.', 'simple-embed-code' ) . "</p>\n";

	}
}
?>

</div>
