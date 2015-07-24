<?php

// Exit if file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

class Cf7_Gs_Ext_Admin {

    function __construct(){

        add_filter( 'wpcf7_editor_panels', array( $this, 'cf7_add_tab' ) );

        add_action( 'wpcf7_after_save', array( $this, 'save_options' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );

        add_action('plugins_loaded', array( $this, 'load_textdomain' ) );

    }

    function cf7_add_tab( $panels ) {

    	$new_page = array(
    		'cf7-gs-ext' => array(
    			'title' => __( 'GetResponse Settings', 'cf7-gr-ext' ),
    			'callback' => array( $this,'cf7_tab_callback' )
    		)
    	);

    	$panels = array_merge($panels, $new_page);

    	return $panels;

    }

    function cf7_tab_callback($args) {

    	$cf7_gs_defaults = array();
    	$cf7_gs = get_post_meta( $args->id(), 'cf7_gs_settings', true );
      global $cf7_gs_ext;
      $cf7_gs_ext->template()->display( 'admin/tab-content', array( 'cf7_gs' => $cf7_gs ) );

    }

    function save_options($args) {
      $cf7_gs = $_POST['cf7-gs'];
    	update_post_meta( $args->id, 'cf7_gs_settings', $cf7_gs );

    }

    function enqueue_scripts(){

      wp_enqueue_style( 'cf7-gs-ext-admin-style', CF7_GS_EXT_BASE_URL. '/assets/css/admin/cf7-gs-ext-admin.css', array(), CF7_GS_EXT_VERSION );

      wp_enqueue_script( 'cf7-gs-ext-admin', CF7_GS_EXT_BASE_URL. '/assets/js/admin/cf7-gs-ext-admin.js', array( 'jquery' ), CF7_GS_EXT_VERSION );
      // Localize the script with new data
      $translation_array = array(
      	'base_url' => home_url( '/' ),
      	'ajax_url' => admin_url( 'admin-ajax.php' )
      );
      wp_localize_script( 'cf7-gs-ext-admin', 'cf7_options', $translation_array );

    }

    function plugin_action_links( $links, $file ) {

    	if ( $file != CF7_GS_EXT_BASE_NAME )
    		return $links;

    	$settings_link = '<a href="' . menu_page_url( CF7_GS_EXT_SETTING_SLUG, false ) . '">'
    		. esc_html( __( 'Settings', 'cf7-gs-ext' ) ) . '</a>';

    	array_unshift( $links, $settings_link );

    	return $links;
    }

    function load_textdomain() {
    	load_plugin_textdomain( CF7_GS_EXT_TEXT_DOMAIN, false, CF7_GS_EXT_BASE_NAME . '/lang/' );
    }
}

new Cf7_Gs_Ext_Admin();
