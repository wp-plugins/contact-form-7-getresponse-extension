<?php

// Exit if file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

class Cf7_Gs_Ext_Front_End {

    function __construct(){

        add_action( 'wpcf7_before_send_mail', array( $this, 'subscribe' ) );

    }

    function tag_replace( $pattern, $subject, $posted_data, $html = false ) {

    	if( preg_match($pattern,$subject,$matches) > 0)
    	{

    		if ( isset( $posted_data[$matches[1]] ) ) {
    			$submitted = $posted_data[$matches[1]];

    			if ( is_array( $submitted ) )
    				$replaced = join( ', ', $submitted );
    			else
    				$replaced = $submitted;

    			if ( $html ) {
    				$replaced = strip_tags( $replaced );
    				$replaced = wptexturize( $replaced );
    			}

    			$replaced = apply_filters( 'wpcf7_mail_tag_replaced', $replaced, $submitted );

    			return stripslashes( $replaced );
    		}

    		if ( $special = apply_filters( 'wpcf7_special_mail_tags', '', $matches[1] ) )
    			return $special;

    		return $matches[0];
    	}
    	return $subject;

    }

    function subscribe( $cf7 ) {

    	$cf7_gs = get_post_meta( $cf7->id(), 'cf7_gs_settings', true );
    	$submission = WPCF7_Submission::get_instance();

    	if( $cf7_gs )
    	{

    		$callback = array( &$cf7, 'cf7_mch_callback' );
    	  $regex = '/\[\s*([a-zA-Z_][0-9a-zA-Z:._-]*)\s*\]/';
    		$subscribe = false;

        if( isset($cf7_gs['accept']) && strlen($cf7_gs['accept']) != 0 )
        {
          $accept = $this->tag_replace( $regex, $cf7_gs['accept'], $submission->get_posted_data() );
          if($accept != $cf7_gs['accept'])
          {
            if(strlen($accept) > 0)
              $subscribe = true;
          }
        }
        else {
          $subscribe = true;
        }

        if( !$subscribe )
          return;


    		$email = $this->tag_replace( $regex, $cf7_gs['email'], $submission->get_posted_data() );
    		$name = $this->tag_replace( $regex, $cf7_gs['name'], $submission->get_posted_data() );

    		$lists = $this->tag_replace( $regex, $cf7_gs['list'], $submission->get_posted_data() );

        $cf7_gs_ext_basics_options = get_option( 'cf7_gs_ext_basics_options' );
        if( !isset( $cf7_gs_ext_basics_options['gs_con'] ) || true !== $cf7_gs_ext_basics_options['gs_con'] ){

          error_log( 'Get response connection failed.' );
          return;

        }
        $gs_api = new GetResponse( $cf7_gs_ext_basics_options['gs_key'] );

        // Test GS connection
        ob_start();
        $ping = $gs_api->ping();
        ob_end_clean();

        if( !is_string( $ping ) && 'pong' != $ping ){

          error_log( 'Get response connection failed.' );
          return;

        }

        // Campaigns
        $campaigns 	 = (array)$gs_api->getCampaigns();
        $campaignIDs = array_keys($campaigns);

        if( !in_array( $lists, $campaignIDs ) ){
          error_log( 'List not found.' );
          return;
        }

        $gs_custom_fields = array();

        // print_r( $cf7_gs );

        $custom_values = array_filter($cf7_gs['custom_value']);
        $custom_keys = array_filter($cf7_gs['custom_key']);

        // print_r( $custom_values );
        // print_r( $custom_keys );
        // if( isset( $cf7_gs['cf7-gs'] ) )

        if( !empty( $custom_values ) || !empty( $custom_keys ) ){

          foreach ($custom_values as $key => $value) {
            if( '' != $value && isset( $custom_keys[ $key ] ) && '' != $custom_keys[ $key ] ){

              $gs_custom_fields[ $custom_keys[ $key ] ] = $this->tag_replace( $regex, trim( $value ), $submission->get_posted_data() );
            }
          }

          $gs_custom_fields = array_filter( $gs_custom_fields );
          // print_r( $gs_custom_fields );
        }
        // die();

        ob_start();
        $addContact = $gs_api->addContact( $lists, $name, $email, 'standard', 0, $gs_custom_fields );
        ob_end_clean();

        if( !isset( $addContact->queued ) || 1 != $addContact->queued ){

          error_log( 'Contact could not be added' );

          return;

        }


        return;
    	}

    }

}

new Cf7_Gs_Ext_Front_End();
