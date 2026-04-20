<?php
/**
 * Code Embed Options
 *
 * Allow the user to change the default options.
 *
 * @package  simple-embed-code
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
<h1><?php echo esc_html__( 'Code Embed Options', 'simple-embed-code' ); ?></h1>
<?php

// If options have been updated on screen, update the database.

if ( ( ! empty( $_POST ) ) && ( check_admin_referer( 'code-embed-profile', 'code_embed_profile_nonce' ) ) ) {

	// Make sure the user has the correct permissions. This is a belt-and-braces check as this is already done by core at page access time.

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Insufficient permissions.', 'simple-embed-code' ) );
	}

	// Get the options. If they don't exist, create a new array.

	$options = get_option( 'artiss_code_embed' );
	if ( ! is_array( $options ) ) {
		$options = array();
	}

	// Update the options array from the form fields. Strip invalid tags.

	if ( ! empty( $_POST['code_embed_opening'] ) ) {
		$options['opening_ident'] = strtoupper( trim( sanitize_text_field( wp_unslash( $_POST['code_embed_opening'] ) ) ) );
	} else {
		$options['opening_ident'] = '{{';
	}

	if ( ! empty( $_POST['code_embed_keyword'] ) ) {
		$options['keyword_ident'] = strtoupper( trim( sanitize_text_field( wp_unslash( $_POST['code_embed_keyword'] ) ) ) );
	} else {
		$options['keyword_ident'] = 'CODE';
	}

	if ( ! empty( $_POST['code_embed_closing'] ) ) {
		$options['closing_ident'] = strtoupper( trim( sanitize_text_field( wp_unslash( $_POST['code_embed_closing'] ) ) ) );
	} else {
		$options['closing_ident'] = '}}';
	}

	if ( isset( $_POST['code_embed_excerpt'] ) ) {
		$options['excerpt'] = sanitize_text_field( wp_unslash( $_POST['code_embed_excerpt'] ) );
	} else {
		$options['excerpt'] = '';
	}

	update_option( 'artiss_code_embed', $options );

	echo '<div class="updated fade" role="status"><p><strong>' . esc_html__( 'Settings saved.', 'simple-embed-code' ) . "</strong></p></div>\n";
}

// Fetch options into an array.

$options = get_option( 'artiss_code_embed' );
?>

<form method="post" action="<?php echo esc_url( admin_url( 'options-general.php?page=ce-options' ) ); ?>">

<table class="form-table">

<tr>
<th scope="row"><label for="code_embed_excerpt"><?php echo esc_html__( 'Allow in excerpts', 'simple-embed-code' ); ?></label></th>
<td><input type="checkbox" id="code_embed_excerpt" name="code_embed_excerpt" value="1"
<?php checked( '1', $options['excerpt'] ); ?>
/><label for="code_embed_excerpt"><?php esc_html_e( 'Allow embedded code to be shown in excerpts', 'simple-embed-code' ); ?></label></td>
</tr>

</table>

<?php echo '<h2>' . esc_html__( 'Identifier Format', 'simple-embed-code' ) . '</h2><p>' . esc_html__( 'Specify the format that will be used to define the way the code is embedded in your post. The formats are case insensitive and characters &lt; &gt [ ] are invalid.', 'simple-embed-code' ) . '</p>'; ?>

<table class="form-table">

<tr>
<th scope="row"><label for="code_embed_keyword"><?php echo esc_html__( 'Keyword', 'simple-embed-code' ); ?></label></th>
<td><input type="text" size="12" maxlength="12" id="code_embed_keyword" name="code_embed_keyword" value="<?php echo esc_attr( $options['keyword_ident'] ); ?>"/><p class="description"><?php esc_html_e( 'The keyword that is used to name the custom field and then place in your post where the code should be embedded. A suffix of any type can then be placed on the end.', 'simple-embed-code' ); ?></p></td>
</tr>

<tr>
<th scope="row"><label for="code_embed_opening"><?php echo esc_html__( 'Opening Identifier', 'simple-embed-code' ); ?></label></th>
<td><input type="text" size="4" maxlength="4" id="code_embed_opening" name="code_embed_opening" value="<?php echo esc_attr( $options['opening_ident'] ); ?>"/><p class="description"><?php esc_html_e( 'The character(s) that must be placed in the post before the keyword to uniquely identify it.', 'simple-embed-code' ); ?></p></td>
</tr>

<tr>
<th scope="row"><label for="code_embed_closing"><?php echo esc_html__( 'Closing Identifier', 'simple-embed-code' ); ?></label></th>
<td><input type="text" size="4" maxlength="4" id="code_embed_closing" name="code_embed_closing" value="<?php echo esc_attr( $options['closing_ident'] ); ?>"/><p class="description"><?php esc_html_e( 'The character(s) that must be placed in the post after the keyword to uniquely identify it.', 'simple-embed-code' ); ?></p></td>
</tr>

</table>

<?php wp_nonce_field( 'code-embed-profile', 'code_embed_profile_nonce', true, true ); ?>

<p><input type="submit" name="Submit" class="button-primary" value="<?php echo esc_attr__( 'Save changes', 'simple-embed-code' ); ?>"/></p>

</form>

<?php

// How to embed.

echo '<h2>' . esc_html__( 'How To Embed', 'simple-embed-code' ) . "</h2>\n";

/* translators: %1$s: example of custom field key value to use based on current settings, %2$s: details of key used in example */
echo '<p>' . sprintf( esc_html__( 'Based upon your current settings to embed some code simply add a custom field named %1$s, where %2$s is any suffix you wish. The code to embed is then added as the field value.', 'simple-embed-code' ), esc_html( $options['keyword_ident'] ) . 'x', 'x' ) . "\n";

/* translators: %1$s: example of how to embed code in a post based on current settings, %2$s: details of key used in example */
echo ' ' . sprintf( esc_html__( 'Then, to add the code into your post simply add %1$s where you wish it to appear. %2$s is the suffix you used for the custom field name.', 'simple-embed-code' ), esc_html( $options['opening_ident'] ) . esc_html( $options['keyword_ident'] ) . 'x' . esc_html( $options['closing_ident'] ), 'x' ) . "</p>\n";

/* translators: %1$s: another example of adding a custom field, %2$s: another example of adding embed to a post */

/* translators: %1$s: example embedding for responsive layout , %2$s: details of key used in example */
echo '<p>' . sprintf( esc_html__( 'To embed the same code but to make it responsive you would use %1$s. To set a maximum width you would use %2$s, where %3$s is the maximum width in pixels.', 'simple-embed-code' ), esc_html( $options['opening_ident'] ) . esc_html( $options['keyword_ident'] ) . 'x_RES' . esc_html( $options['closing_ident'] ), esc_html( $options['opening_ident'] ) . esc_html( $options['keyword_ident'] ) . 'x_RES_y' . esc_html( $options['closing_ident'] ), 'y' ) . "</p>\n";

/* translators: %1$s: example of embedding a file, %2$s: details of key used in example */
echo '<p>' . sprintf( esc_html__( 'To embed an external URL you would type %1$s, where %2$s is the URL.', 'simple-embed-code' ), esc_html( $options['opening_ident'] ) . 'url' . esc_html( $options['closing_ident'] ), 'url' ) . "</p>\n";
?>

</div>
