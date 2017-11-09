<?php
/**
* Add Embed to Posts
*
* Functions to add embed code to posts
*
* @package	simple-embed-code
*/

/**
* Add filter to add embed code
*
* Filter to add embed to any posts
*
* @since	1.5
*
* @uses		ce_get_embed_code		Get embed code from other posts
*
* @param	string  $content		Post content without embedded code
* @return	string					Post content with embedded code
*/

function ce_filter( $content ) {

	global $post;

	// Set initial values

	$options = get_option( 'artiss_code_embed' );
	$found_pos = strpos( $content, $options[ 'opening_ident' ] . $options[ 'keyword_ident' ], 0 );
	$prefix_len = strlen( $options[ 'opening_ident' ] . $options[ 'keyword_ident' ] );

	// Loop around the post content looking for all requests for code embeds

	while ( $found_pos !== false ) {

		// Get the position of the closing identifier - ignore if one is not found

		$end_pos = strpos( $content, $options[ 'closing_ident' ], $found_pos + $prefix_len );
		if ( $end_pos !== false ) {

			// Extract the suffix

			$suffix_len = $end_pos - ( $found_pos + $prefix_len );
			if ( $suffix_len == 0 ) {
				$suffix = '';
			} else {
				$suffix = substr( $content, $found_pos + $prefix_len, $suffix_len );
			}
            $full_suffix = $suffix;

            // Check for responsive part of suffix

            $responsive = false;
            $max_width = false;
            $res_pos = false;
            if ( strlen( $suffix ) > 1 ) { $res_pos = strpos( $suffix, '_RES', 1 ); }

            if ( $res_pos !== false ) {

                // If responsive section found, check it's at the end of has an underscore afterwards
                // Otherwise it may be part of something else

                if ( $res_pos == strlen( $suffix) - 4 ) {
                    $responsive = true;
                } else {
                    if ( substr( $suffix, $res_pos+4, 1 ) == '_' ) {
                        $responsive = true;
                        $max_width = substr( $suffix, $res_pos + 5 );
                        if ( !is_numeric( $max_width ) ) { $max_width = ''; }
                    }
                }

                if ( $responsive ) { $suffix = substr( $suffix, 0, $res_pos ); }
            }

			// Get the custom field data and replace in the post

            $search = $options[ 'opening_ident' ] . $options[ 'keyword_ident' ] . $suffix . $options[ 'closing_ident' ];

            // Get the meta for the current post
			
            $post_meta = get_post_meta( $post -> ID, $options[ 'keyword_ident' ].$suffix, false );
            if ( isset( $post_meta[ 0 ] ) ) {
                $html = $post_meta[ 0 ];
            } else {
                // No meta found, so look for it elsewhere
                $html = ce_get_embed_code( $options[ 'keyword_ident' ], $suffix );
            }

            // Build the string to search for

            $search = $options[ 'opening_ident' ] . $options[ 'keyword_ident' ] . $full_suffix . $options[ 'closing_ident' ];

            // Build the string of code to replace with

            $replace = ce_generate_code( $html, 'Code Embed', $responsive, $max_width, $options[ 'debug' ] );

            // Now modify all references

            $content = str_replace( $search , $replace, $content );

		}
		$found_pos = strpos( $content, $options[ 'opening_ident' ] . $options[ 'keyword_ident' ], $found_pos + 1 );
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
* @since	2.0
*
* @param    $content        string  The content
* @param	$options	    string	The options array
* @param	$search 		string	The string to search for
* @return					string	The updated content
*/

function ce_quick_replace( $content = '', $options = '', $search = '' ) {

    $start_pos = strpos( $content, $options[ 'opening_ident' ] . $search, 0 );
    $prefix_len = strlen( $options[ 'opening_ident' ] );

	while ( $start_pos !== false ) {

        $start_pos = $start_pos + strlen( $options[ 'opening_ident' ] );
        $end_pos = strpos( $content, $options[ 'closing_ident' ], $start_pos );
		if ( $end_pos !== false ) {
            $url = substr( $content, $start_pos, $end_pos - $start_pos );
            $file = ce_get_file( $url );
            $content = str_replace ( $options[ 'opening_ident' ] . $url . $options[ 'closing_ident' ], $file[ 'file' ], $content );
        }
		$start_pos = strpos( $content, $options[ 'opening_ident' ] . $search, $found_pos );

	}

    return $content;
}

/**
* Generate Embed Code
*
* Function to generate the embed code that will be output
*
* @since	2.0
*
* @param    $html           string  The embed code (required)
* @param	$plugin_name	string	The name of the plugin (required)
* @param	$responsive		string	Responsive output required? (optional)
* @param    $max_width      string  Maximum width of responsive output (optional)
* @param	$debug			boolean	Whether to suppress debug output (1) or not
* @return					string	The embed code
*/

function ce_generate_code( $html, $plugin_name, $responsive = '', $max_width = '', $debug = '' ) {

    $code = "\n";
	if ( $debug != 1 ) { $code .= '<!-- ' . $plugin_name . ' v' . code_embed_version . " -->\n"; }

    if ( $max_width !== false ) { $code .= '<div style="width: ' . $max_width . 'px; max-width: 100%">'; }

    if ( $responsive ) { $code .= '<div class="ce-video-container">'; }

    $code .= $html;

    if ( $responsive ) { $code .= '</div>'; }

    if ( $max_width !== false ) { $code .= '</div>'; }

    $code .= "\n";
	if ( $debug != 1 ) { $code .= '<!-- End of ' . $plugin_name . " code -->\n"; }

    return $code;
}

/**
* Get the Global Embed Code
*
* Function to look for and, if available, return the global embed code
*
* @since	1.6
*
* @uses		ce_report_error			Generate an error message
*
* @param	$ident			string	The embed code opening identifier
* @param	$suffix 		string	The embed code suffix
* @return					string	The embed code (or error)
*/

function ce_get_embed_code( $ident, $suffix ) {

	// Meta was not found in current post so look across meta table - find the number of distinct code results

	$meta_name = $ident . $suffix;
	global $wpdb;
	$unique_records = $wpdb -> get_results( "SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = '" . $meta_name . "'" );
	$records = $wpdb -> num_rows;

	if ( $records > 0 ) {

		// Results were found

		$meta = $wpdb -> get_results( "SELECT meta_value, post_title, ID FROM $wpdb->postmeta, $wpdb->posts WHERE meta_key = '" . $meta_name . "' AND post_id = ID" );
		$total_records = $wpdb -> num_rows;

		if ( $records == 1 ) {

			// Only one unique code result returned so assume this is the global embed

			foreach ( $meta as $meta_data ) {
				$html = $meta_data -> meta_value;
			}

		} else {

			// More than one unique code result returned, so output the list of posts

			$error = sprintf( __( 'Cannot use %s as a global code as it is being used to store %d unique pieces of code in %d posts - <a href="%s">click here</a> for more details', 'simple-embed-code' ), $meta_name, $records, $total_records, get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=ce-search&amp;suffix=' . $suffix );
			$html = ce_report_error( $error, 'Code Embed', false );
		}
	} else {

		// No meta code was found so write out an error

		$html = ce_report_error( sprintf( __( 'No embed code was found for %s', 'simple-embed-code' ), $meta_name ), 'Code Embed', false );

	}
	return $html;
}

/**
* Fetch a file (1.6)
*
* Use WordPress API to fetch a file and check results
* RC is 0 to indicate success, -1 a failure
*
* @since	2.0
*
* @param	string	$filein		File name to fetch
* @param	string	$header		Only get headers?
* @return	string				Array containing file contents and response
*/

function ce_get_file( $filein, $header = false ) {

	$rc = 0;
	$error = '';
	if ( $header ) {
		$fileout = wp_remote_head( $filein );
		if ( is_wp_error( $fileout ) ) {
			$error = 'Header: ' . $fileout -> get_error_message();
			$rc = -1;
		}
	} else {
		$fileout = wp_remote_get( $filein );
		if ( is_wp_error( $fileout ) ) {
			$error = 'Body: ' . $fileout -> get_error_message();
			$rc = -1;
		} else {
			if ( isset( $fileout[ 'body' ] ) ) {
				$file_return[ 'file' ] = $fileout[ 'body' ];
			}
		}
	}

	$file_return[ 'error' ] = $error;
	$file_return[ 'rc' ] = $rc;
	if ( !is_wp_error( $fileout ) ) {
		if ( isset( $fileout[ 'response' ][ 'code' ] ) ) {
			$file_return[ 'response' ] = $fileout[ 'response' ][ 'code' ];
		}
	}

	return $file_return;
}

/**
* Report an error (1.4)
*
* Function to report an error
*
* @since	1.6
*
* @param	$error			string	Error message
* @param	$plugin_name	string	The name of the plugin
* @param	$echo			string	True or false, depending on whether you wish to return or echo the results
* @return					string	True or the output text
*/

function ce_report_error( $error, $plugin_name, $echo = true ) {

	$output = '<p style="color: #f00; font-weight: bold;">' . $plugin_name . ': ' . $error . "</p>\n";

	if ( $echo ) {
		echo $output;
		return true;
	} else {
		return $output;
	}

}
?>