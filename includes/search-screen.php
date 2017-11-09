<?php
/**
* Code Embed Search
*
* Allow the user to change the default options
*
* @package	simple-embed-code
* @since	1.6
*/
?>
<div class="wrap">
<h1><?php _e( 'Code Embed Search', 'simple-embed-code' ); ?></h1>

<?php
echo '<p>' . __( 'Enter the suffix to search for below and press the \'Search\' button to view the results. Further help can be found by clicking on the Help tab at the top right-hand of the screen.', 'simple-embed-code' ) . '</p>';
?>

<?php

// Get the suffix - either from the submitted field or via the URL line

if ( isset ( $_GET[ 'suffix' ] ) ) {
	$suffix = htmlspecialchars( $_GET[ 'suffix' ] );
} else {
	if ( ( !empty( $_POST ) ) && ( check_admin_referer( 'code-embed-search' , 'code_embed_search_nonce' ) ) ) {
		$suffix = htmlspecialchars( $_POST[ 'ce_suffix' ] );
	} else {
		$suffix = '';
	}
}

// Fetch options into an array

$options = get_option( 'artiss_code_embed' );
?>

<form method="post" action="<?php echo get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=ce-search&amp;updated=true'; ?>">

	<?php echo $options[ 'opening_ident' ] . $options[ 'keyword_ident' ]; ?>

	<input type="text" size="6" name="ce_suffix" value="<?php echo esc_html( $suffix ); ?>"/>

	<?php echo $options[ 'closing_ident' ]; ?>

	<?php wp_nonce_field( 'code-embed-search', 'code_embed_search_nonce', true, true ); ?>

	<input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Search', 'simple-embed-code' ); ?>"/>

</form>

<?php
if ( $suffix != '' ) {

	global $wpdb;
	$meta = $wpdb -> get_results( "SELECT meta_value, post_title, ID FROM $wpdb->postmeta, $wpdb->posts WHERE meta_key = '" . $options[ 'keyword_ident' ] . $suffix . "' AND post_id = ID ORDER BY meta_value" );
	$records = $wpdb -> num_rows;

	if ( $records > 0 ) {

		echo '<table class="form-table">';
		$color1 = 'dfdfdf';
		$color2 = 'ececec';
		$color = $color1;
		$prev_html = '';

		foreach ( $meta as $meta_data ) {
			$html = $meta_data -> meta_value;
			$post_title = $meta_data -> post_title;
			$post_id = $meta_data -> ID;

			// Switch background colours as the code changes

			if ( $html != $prev_html ) { if ( $color == $color1 ) { $color = $color2; } else { $color = $color1; } }

			echo "<tr style=\"background-color: #" . $color . "\">\n";
			echo '<td><a href="' . home_url() . '/wp-admin/post.php?post=' . $post_id . '&action=edit" style="color: #f00;">'.$post_title."</td>\n";
			echo '<td><textarea readonly="readonly" rows="3" cols="80">' . htmlspecialchars( $html ) . "</textarea></td>\n";
			echo "</tr>\n";

			$prev_html = $html;
		}

		echo "</table>\n";

	} else {

		echo "<p style=\"color: #f00\">" . __( 'No posts were found containing that embed code.', 'simple-embed-code' ) . "</p>\n";

	}
}
?>

</div>