<?php


namespace sphinx\inc;

if( class_exists( 'sphinx\inc\Admin_Menu' ) ) return;

/**
 *
 */
class Admin_Menu{

  	function __construct( $config, $callback = array() ){

      if( !empty( $callback ) )
        add_action( 'admin_menu', $callback );

      return $this;
    }

    function add( $args = array() ){
      $defaults['page_title'] = "";
      $defaults['menu_title'] = "";
      $defaults['capability'] = "";
      $defaults['menu_slug'] = "";
      $defaults['function'] = "";
      $defaults['icon_url'] = "";
      $defaults['position'] = "";

  		$args = wp_parse_args( $args, $defaults );

      extract( $args );

      add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

    }

  }
