<?php

// Exit if file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

class Cf7_Gs_Ext_Admin_Settings {

    function __construct(){

      global $cf7_gs_ext;

      $this->settings_api = $cf7_gs_ext->settings_api();

      add_action( 'admin_init', array($this, 'admin_init') );

      $this->add_menu();

      add_action( 'wp_ajax_gs_update_camp', array( $this, 'update_campaigns' ) );
      add_action( 'wp_ajax_nopriv_gs_update_camp', array( $this, 'update_campaigns' ) );

    } # END function __construct

    function add_menu(){
      global $cf7_gs_ext;
      $admin_menu = $cf7_gs_ext->admin_menu( array( $this, 'menu_callback' ) );

    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function get_settings_sections() {
      $sections = array(
          array(
              'id' => 'cf7_gs_ext_basics_options',
              'title' => __( 'Basic Settings', 'cf7-gr-ext' )
          ),
      );
      return $sections;
  }

  function menu_callback(){
    $args['page_title'] = __( 'CF7 Get Response Settings', 'cf7-gr-ext' );
    $args['menu_title'] = __( 'CF7 GetResponse', 'cf7-gr-ext' );
    $args['capability'] = 'manage_options';
    $args['menu_slug'] = 'cf7-gs-ext-page';
    $args['function'] = array( $this, 'menu_page_callback' );
    $args['icon_url'] = 'dashicons-id';
    $args['position'] = 75;

    global $cf7_gs_ext;
    $cf7_gs_ext->admin_menu()->add( $args );

  }

  function get_settings_fields() {
        $settings_fields = array(
            'cf7_gs_ext_basics_options' => array(
                array(
                    'name'              => 'gs_key',
                    'label'             => __( 'Get Response API Key', 'cf7-gr-ext' ),
                    'desc'              => sprintf( __( 'Dont know how to find? <a href="%s" target="_blank">Check here</a>', 'cf7-gr-ext' ), 'http://support.getresponse.com/faq/where-i-find-api-key' ),
                    'type'              => 'text',
                ),

            )
        );

        $cf7_gs_ext_basics_options = get_option( 'cf7_gs_ext_basics_options' );

        if( isset( $_GET['settings-updated'] ) && isset( $cf7_gs_ext_basics_options['gs_key'] ) && '' !== $cf7_gs_ext_basics_options['gs_key'] ){

          $this->update_campaigns();

        } # END if( isset( $cf7_gs_ext_basics_options['gs_key'] ) && '' !== $cf7_gs_ext_basics_options['gs_key'] )
        else if( isset( $cf7_gs_ext_basics_options['gs_con'] ) && false == $cf7_gs_ext_basics_options['gs_con'] ){
          add_action( 'admin_notices', array( $this, 'api_key_invalid_message' ) );
        }
        return $settings_fields;
    }

  function menu_page_callback(){

      echo '<div class="wrap">';
      if( isset($_GET['settings-updated']) ) {
      echo '<div id="message" class="updated">
          <p><strong>'.__('Settings saved.', 'cf7-gr-ext' ).'</strong></p>
      </div>';
      }
      $this->settings_api->show_navigation();
      $this->settings_api->show_forms();

      $cf7_gs_ext_basics_options = get_option( 'cf7_gs_ext_basics_options' );
      if( isset( $cf7_gs_ext_basics_options['gs_con'] ) && false !== $cf7_gs_ext_basics_options['gs_con'] ){
        echo '<table class="form-table">
              <tbody>
                <tr>
                  <th scope="row">
                    Update Campaigns
                  </th>
                  <td><a href="javascript:void(0);" id="cf7-gs-ext-update-camp"><span class="dashicons dashicons-update"></span> Refresh</a></td>
                </tr>
              </tbody>
            </table>';
        echo '</div>';
    }

  } # END function menu_page_callback


  public function api_key_invalid_message(){

    echo '<div class="error"><p>'.__( 'Invalid API key!', 'cf7-gr-ext' ).'</p></div>';
  }

  function update_campaigns(){

    $cf7_gs_ext_basics_options = get_option( 'cf7_gs_ext_basics_options' );

    $getresponse = new GetResponse( $cf7_gs_ext_basics_options['gs_key'] );

    ob_start();
    $ping = $getresponse->ping();
    ob_end_clean();

    if( is_string( $ping ) && 'pong' === $ping){

      $campaigns 	 = (array)$getresponse->getCampaigns();

      if( !empty( $campaigns ) ){
        $newCf7GsExtBasicOptions = $cf7_gs_ext_basics_options;
        $newCf7GsExtBasicOptions['gs_camp'] = $campaigns;
        $newCf7GsExtBasicOptions['gs_con'] = true;

        update_option( 'cf7_gs_ext_basics_options', $newCf7GsExtBasicOptions );

        global $cf7_gs_ext;

        if( $cf7_gs_ext->is_request( 'ajax' ) ){

          echo json_encode( $newCf7GsExtBasicOptions );
          exit;

        }

      } # END if( !empty( $campaigns ) )
      else{
          $newCf7GsExtBasicOptions = $cf7_gs_ext_basics_options;
          unset( $newCf7GsExtBasicOptions['gs_camp'] );
          update_option( 'cf7_gs_ext_basics_options', $newCf7GsExtBasicOptions );
      }

    }
    else{
        $newCf7GsExtBasicOptions = $cf7_gs_ext_basics_options;
        $newCf7GsExtBasicOptions['gs_con'] = false;
        unset( $newCf7GsExtBasicOptions['gs_camp'] );
        update_option( 'cf7_gs_ext_basics_options', $newCf7GsExtBasicOptions );

        add_action( 'admin_notices', array( $this, 'api_key_invalid_message' ) );

    }
  }
}
