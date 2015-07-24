<?php
/**
 * Helper functions for development
 *
 * package name: sphinX
 */

// Exit if file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

if( !function_exists( 'sphinX_print' ) ){

	function sphinX_print( $arr, $return = false ){

		$output = '<pre>';
		$output .= print_r( $arr, true );
		$output .= '</pre>';

		if( $return )
			return $output;

		echo $output;

	} # END function sphinX_print

} # END if( !function_exists( 'sphinX_print' ) )

if( !function_exists( 'sphinX_check_data' ) ){

	function sphinX_check_data( $key, $array = array(), $default = '' ){

		return ( isset( $array[ $key ] ) ? $array[ $key ]: $default );

	}

}

if( !function_exists( 'sphinX_format_size_units' ) ){

	function sphinX_format_size_units($bytes)
	    {
	        if ($bytes >= 1073741824)
	        {
	            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
	        }
	        elseif ($bytes >= 1048576)
	        {
	            $bytes = number_format($bytes / 1048576, 2) . ' MB';
	        }
	        elseif ($bytes >= 1024)
	        {
	            $bytes = number_format($bytes / 1024, 2) . ' KB';
	        }
	        elseif ($bytes > 1)
	        {
	            $bytes = $bytes . ' bytes';
	        }
	        elseif ($bytes == 1)
	        {
	            $bytes = $bytes . ' byte';
	        }
	        else
	        {
	            $bytes = '0 bytes';
	        }

	        return $bytes;
	}

}

if( !function_exists( 'sphinX_upload_file' ) ){

	function sphinX_upload_file( $file = array() ) {

		require_once( ABSPATH . 'wp-admin/includes/admin.php' );

	      $file_return = wp_handle_upload( $file, array('test_form' => false ) );

	      if( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
	          return false;
	      } else {

	          $filename = $file_return['file'];

	          $attachment = array(
	              'post_mime_type' => $file_return['type'],
	              'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
	              'post_content' => '',
	              'post_status' => 'inherit',
	              'guid' => $file_return['url']
	          );

	          $attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );

	          require_once(ABSPATH . 'wp-admin/includes/image.php');
	          $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
	          wp_update_attachment_metadata( $attachment_id, $attachment_data );

	          if( 0 < intval( $attachment_id ) ) {
	          	return $attachment_id;
	          }
	      }

	      return false;
	}

}
