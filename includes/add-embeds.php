<?php
/**
* Add Embed to Posts
*
* Functions to add embed code to posts
*
* @package  simple-embed-code
*/

/**
* Add filter to add embed code
*
* Filter to add embed to any posts
*
* @since    1.5
*
* @uses     ce_get_embed_code       Get embed code from other posts
*
* @param    string  $content        Post content without embedded code
* @return   string                  Post content with embedded code
*/

function ce_filter( $content ) {

	global $post;

	// Set initial values

	$options    = get_option( 'artiss_code_embed' );
	$found_pos  = strpos( $content, $options['opening_ident'] . $options['keyword_ident'], 0 );
	$prefix_len = strlen( $options['opening_ident'] . $options['keyword_ident'] );

	// Loop around the post content looking for all requests for code embeds

	while ( false !== $found_pos ) {

		// Get the position of the closing identifier - ignore if one is not found

		$end_pos = strpos( $content, $options['closing_ident'], $found_pos + $prefix_len );
		if ( false !== $end_pos ) {

			// Extract the suffix

			$suffix_len = $end_pos - ( $found_pos + $prefix_len );
			if ( 0 === $suffix_len ) {
				$suffix = '';
			} else {
				$suffix = substr( $content, $found_pos + $prefix_len, $suffix_len );
			}
			$full_suffix = $suffix;

			// Check for responsive part of suffix

			$responsive = false;
			$max_width  = false;
			$res_pos    = false;
			if ( 1 < strlen( $suffix ) ) {
				$res_pos = strpos( $suffix, '_RES', 1 );
			}

			if ( false !== $res_pos ) {

				// If responsive section found, check it's at the end of has an underscore afterwards
				// Otherwise it may be part of something else

				if ( strlen( $suffix ) - 4 === $res_pos ) {
					$responsive = true;
				} else {
					if ( '_' === substr( $suffix, $res_pos + 4, 1 ) ) {
						$responsive = true;
						$max_width  = substr( $suffix, $res_pos + 5 );
						if ( ! is_numeric( $max_width ) ) {
							$max_width = '';
						}
					}
				}

				if ( $responsive ) {
					$suffix = substr( $suffix, 0, $res_pos );
				}
			}

			// Get the custom field data and replace in the post

			$search = $options['opening_ident'] . $options['keyword_ident'] . $suffix . $options['closing_ident'];

			// Get the meta for the current post

			$post_meta = get_post_meta( $post->ID, $options['keyword_ident'] . $suffix, false );
			if ( isset( $post_meta[0] ) ) {
				$html = $post_meta[0];
			} else {
				// No meta found, so look for it elsewhere
				$html = ce_get_embed_code( $options['keyword_ident'], $suffix );
			}

			// Build the string to search for

			$search = $options['opening_ident'] . $options['keyword_ident'] . $full_suffix . $options['closing_ident'];

			// Build the string of code to replace with

			$replace = ce_generate_code( $html, $responsive, $max_width );

			// Now modify all references

			$content = str_replace( $search, $replace, $content );

		}
		$found_pos = strpos( $content, $options['opening_ident'] . $options['keyword_ident'], $found_pos + 1 );
	}

	// Loop around the post content looking for HTTP addresses

	$content = ce_quick_replace( $content, $options, 'http://' );

	// Loop around the post content looking for HTTPS addresses

	$content = ce_quick_replace( $content, $options, 'https://' );

	return $content;
}

add_filter( 'the_content', 'ce_filter' );
add_filter( 'widget_content', 'ce_filter' );

/**
* Quick Replace
*
* Function to do a quick search/replace of the content
*
* @since    2.0
*
* @param    $content        string  The content
* @param    $options        string  The options array
* @param    $search         string  The string to search for
* @return                   string  The updated content
*/

function ce_quick_replace( $content = '', $options = '', $search = '' ) {

	$start_pos = strpos( $content, $options['opening_ident'] . $search, 0 );

	while ( false !== $start_pos ) {

		$end_pos = strpos( $content, $options['closing_ident'], $start_pos + 1 );

		if ( false !== $end_pos ) {
			$url  = substr( $content, $start_pos + 1, $end_pos - $start_pos - 1 );
			$file = ce_get_file( $url );
			if ( false !== $file ) {
				$content = str_replace( $options['opening_ident'] . $url . $options['closing_ident'], $file, $content );
			} else {
				ce_report_error( __( 'File could not be fetched', 'simple-embed-code' ), 'Code Embed', false );
			}
		}

		$start_pos = strpos( $content, $options['opening_ident'] . $search, 0 );

	}

	return $content;
}

/**
* Generate Embed Code
*
* Function to generate the embed code that will be output
*
* @since    2.0
*
* @param    $html           string  The embed code (required)
* @param    $responsive     string  Responsive output required? (optional)
* @param    $max_width      string  Maximum width of responsive output (optional)
* @return                   string  The embed code
*/

function ce_generate_code( $html, $responsive = '', $max_width = '' ) {

	$code = "\n";

	if ( false !== $max_width ) {
		$code .= '<div style="width: ' . $max_width . 'px; max-width: 100%">';
	}

	if ( $responsive ) {
		$code .= '<div class="ce-video-container">';
	}

	$code .= $html;

	if ( $responsive ) {
		$code .= '</div>';
	}

	if ( false !== $max_width ) {
		$code .= '</div>';
	}

	$code .= "\n";

	return $code;
}

/**
* Get the Global Embed Code
*
* Function to look for and, if available, return the global embed code
*
* @since    1.6
*
* @uses     ce_report_error         Generate an error message
*
* @param    $ident          string  The embed code opening identifier
* @param    $suffix         string  The embed code suffix
* @return                   string  The embed code (or error)
*/

function ce_get_embed_code( $ident, $suffix ) {

	// Meta was not found in current post so look across meta table - find the number of distinct code results

	$meta_name = $ident . $suffix;
	global $wpdb;
	$unique_records = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = %s", $meta_name ) ); // @codingStandardsIgnoreLine -- requires the latest data when called, so caching is inappropriate
	$records        = $wpdb->num_rows;

	if ( 0 < $records ) {

		// Results were found

		$meta          = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value, post_title, ID FROM $wpdb->postmeta, $wpdb->posts WHERE meta_key = %s AND post_id = ID", $meta_name ) ); // @codingStandardsIgnoreLine -- requires the latest data when called, so caching is inappropriate
		$total_records = $wpdb->num_rows;

		if ( 1 === $records ) {

			// Only one unique code result returned so assume this is the global embed

			foreach ( $meta as $meta_data ) {
				$html = $meta_data->meta_value;
			}
		} else {

			// More than one unique code result returned, so output the list of posts

			/* translators: %1$s: embed name, %2$d: number of pieces of code being stored with that name, %3$d: number of posts using that embed name */
			$error = sprintf( __( 'Cannot use %1$s as a global code as it is being used to store %2$d unique pieces of code in %3$d posts', 'simple-embed-code' ), $meta_name, $records, $total_records );
			$html  = ce_report_error( $error, 'Code Embed', false );
		}
	} else {

		// No meta code was found so write out an error

		/* translators: %s: the embed name */
		$html = ce_report_error( sprintf( __( 'No embed code was found for %s', 'simple-embed-code' ), $meta_name ), 'Code Embed', false );

	}
	return $html;
}

/**
* Fetch a file
*
* Use WordPress API to fetch a file and check results
*
* @since    2.0
*
* @param    string  $filein     File name to fetch
* @param    string  $header     Only get headers?
* @return   string              Array containing file contents or false to indicate a failure
*/

function ce_get_file( $filein ) {

	if ( function_exists( 'vip_safe_wp_remote_get' ) ) {

		$response = vip_safe_wp_remote_get( $filein, '', 3, 3 );

	} else {

		$response = wp_remote_get( $filein, array( 'timeout' => 3 ) ); // @codingStandardsIgnoreLine -- for non-VIP environments

	}

	if ( is_array( $response ) ) {

		return $response['body'];

	} else {

		return false;

	}
}

/**
* Report an error (1.4)
*
* Function to report an error
*
* @since    1.6
*
* @param    $error          string  Error message
* @param    $plugin_name    string  The name of the plugin
* @param    $echo           string  True or false, depending on whether you wish to return or echo the results
* @return                   string  True or the output text
*/

function ce_report_error( $error, $plugin_name, $echo = true ) {

	$output = '<p style="color: #f00; font-weight: bold;">' . $plugin_name . ': ' . $error . "</p>\n";

	if ( $echo ) {
		echo esc_html( $output );
		return true;
	} else {
		return $output;
	}

}

