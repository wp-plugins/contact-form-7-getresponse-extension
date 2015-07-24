<?php
/*
Plugin Name: Contact Form 7 GetResponse Extension
Plugin URI: http://racase.com
Description: A very easy plugin to integrate GetResponse campaigns with Contact Form 7.
Author: WEN Solutions
Author URI: http://racase.com
Text Domain: cf7-gr-ext
Domain Path: /lang/
Version: 0.1
*/

/*  Copyright 2013-2015 WEN Solutions (email: wensolution at gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


// Exit if file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

// Include sphinX Framework
if( !class_exists( 'sphinx' ) )
	require_once sprintf( '%s/sphinX/sphinx.php', dirname( __FILE__ ) );

/**
 * Class of CRU Groups plugin extending sphinX Framework
 */
class cf7_gs_ext extends sphinX{

	public $version = '0.1';

	/**
	 * Constructor method
	 */
	function __construct(){

		$this->define_constants();

		// Call parent constructer
		parent::__construct( array(	'base_path' => CF7_GS_EXT_BASE, 'template_dir' => 'views' ) );

		// Init classes

		// Includes
		$this->includes();

		// init hooks
		$this->init_hooks();

		add_action( 'init', array( $this, 'admin_init' ) );

	} # END function __construct

	/**
	 * Function to init classes
	 * @return NULL Do not return any value
	 */
	public function admin_init(){

		if( $this->is_request( 'admin' ) ){

			new Cf7_Gs_Ext_Admin_Settings();

		}

		if( !function_exists('wpcf7_register_post_types') ){

					add_action( 'admin_notices', array( $this, 'no_cf7_notice' ) );

		}

	} # END function init

	private function define_constants() {

		$this->define( 'CF7_GS_EXT_BASE', dirname( __FILE__ ) );
		$this->define( 'CF7_GS_EXT_BASE_NAME', plugin_basename( __FILE__ ) );
		$this->define( 'CF7_GS_EXT_BASE_INCLUDE_PATH', sprintf( '%s/inc', CF7_GS_EXT_BASE ) );
		$this->define( 'CF7_GS_EXT_VERSION', $this->version );
		$this->define( 'CF7_GS_EXT_TEMPLATE_BASE', CF7_GS_EXT_BASE."/views" );
		$this->define( 'CF7_GS_EXT_BASE_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );
		$this->define( 'CF7_GS_EXT_SETTING_SLUG', 'cf7-gs-ext-page' );
		$this->define( 'CF7_GS_EXT_TEXT_DOMAIN', 'cf7-gr-ext' );

	}

	private function init_hooks(){}

	private function includes(){

		// Include GetResponse API lib
		include_once( sprintf( '%s/lib/GetResponseAPI.class.php', CF7_GS_EXT_BASE ) );

		// Include admin class
		if( $this->is_request( 'admin' ) ){
			include_once( sprintf( '%s/class-cf7-gs-ext-admin.php', CF7_GS_EXT_BASE_INCLUDE_PATH ) );
			include_once( sprintf( '%s/class-cf7-gs-ext-admin-settings.php', CF7_GS_EXT_BASE_INCLUDE_PATH ) );
		}

		// Include frontend class
		if( $this->is_request( 'frontend' ) ){
			include_once( sprintf( '%s/class-cf7-gs-ext-front-end.php', CF7_GS_EXT_BASE_INCLUDE_PATH ) );
		}

	}

	public function __call($name, $args) {
		//first try to call the parent call
		try {
			return parent::__call($name, $args);
		//this means something in the route went wrong
		} catch(Eden_Route_Exception $e) {
			//now try to call parent with the eden prefix
			return parent::__call( $name, $args);
		}
	}

	public function no_cf7_notice(){
		$class = "error";
		$message = __( "Contact Form 7 not installed/ Active. Please install/ Activate Contact Form 7 to get Contact Form 7 GetResponse Extension working.", 'cf7-gr-ext' );
		echo"<div class=\"$class\"> <p>$message</p></div>";
	}

} # END class CRU_Groups extends sphinX


// Init Contact form 7 getResponse extension class and set global
$GLOBALS['cf7_gs_ext'] = new cf7_gs_ext();
