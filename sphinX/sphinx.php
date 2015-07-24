<?php
/**
 * sphinX
 * Framework to create WordPress Plugin
 * @package	sphinX
 * @author	[__AUTHOR__]
 * @copyright	Copyright (c) 2015 - 2014, [__AUTHOR__] ([__AUTHOR_URL__])
 * @link	[__AUTHOR_URL__]
 * @since	Version 1.0.0
 * @filesource
 */

// Exit if file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

// Check if class exists
if( class_exists( 'sphinX' ) )	return;

/**
 * sphinX main class
 *
 * @package		sphinX
 * @author		[__AUTHOR__]
 * @link		[__AUTHOR_URL__]
 */
class sphinX{

	/**
	 * sphinX configuration for plugin
	 * @var array
	 */
	public $_config;

	/**
	 * Initialize sphinX class
	 * @param array $config Custom configuration to be set by plugin
	 * @return void
	 */
	function __construct( $config = array() ){

		// Base path of the plugin
		$base_path = str_replace( '\sphinX', "", rtrim( __DIR__, '/' ) );

		// Base directory of plugin
		$base_dir = basename( $base_path );

		// Check if custom $base_path is set on $config to change $base_dir
		if( isset( $config['base_path'] ) and file_exists( $config['base_path'] ) )
			$base_dir = basename( $config['base_path'] );

		// Set default configuration for sphinX
		$default_configs = array(
			'base_path' => $base_path,
			'base_dir' => $base_dir,
			'template_dir' => 'templates',
			'plugin_class' => get_class($this) # Check alternative "get_called_class"
		);

		// Merge default configuration with custom configuration
		$this->_config = wp_parse_args( $config, $default_configs );

		// Read helper folder
		$helper_files =  glob( sprintf( '%s/helpers/*.php', dirname( __FILE__ ) ) );

		// Check if helper files exists and include
		if( !empty( $helper_files ) ){

			foreach ( $helper_files as $filename)
			{
			    require $filename;
			}

		}

		// Read Libraries folder
		$library_files = glob( sprintf( '%s/libraries/*.php', dirname( __FILE__ ) ) );

		// Check if Libraries files exists and include
		if( !empty( $library_files ) ){

			foreach ( $library_files as $filename)
			{
			    require $filename;
			}

		}

		// Read inc folder
		$library_files = glob( sprintf( '%s/inc/*.php', dirname( __FILE__ ) ) );

		// Check if Libraries files exists and include
		if( !empty( $library_files ) ){

			foreach ( $library_files as $filename)
			{
			    require $filename;
			}

		}

		// Enqueue the style and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );

	} # END function __construct

	/**
	 * Check the request
	 * @param  string  $type request type
	 * @return boolean       return true or false
	 */
	public function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	} # END public function is_request

	/**
	 * Set constants
	 * @param  string $name  Constant name
	 * @param  string $value Constant value
	 * @return void        do not return any value
	 */
	public function define( $name, $value ) {

		if ( ! defined( $name ) ) {

			define( $name, $value );

		}
		
	} # END public function define

	/**
	 * Assets for the sphinX
	 * @return void Do not return any value
	 */
	function assets(){

		wp_register_style( 'sphinx-style', plugins_url( $this->_config['base_dir'].'/sphinX/assets/css/sphinx-style.css' ) );

		wp_enqueue_style( 'sphinx-style' );

		wp_register_style( 'sphinx-form-style', plugins_url( $this->_config['base_dir'].'/sphinX/assets/css/sphinx-form.css' ) );

	} # END function assets

	private function get_class( $name, $arguments ){

		$args[] = $this->_config;

		if( !empty( $arguments ) ){

			foreach ($arguments as $key => $argument) {

				$args[] = $argument;

			}

		}

    $classname = $name;

  	if( class_exists('sphinx\\inc\\'. $classname ) ){

  		$class    = new \ReflectionClass('sphinx\\inc\\'.$classname);

			$instance = $class->newInstanceArgs($args);

			return $instance;

  	}

  	throw new Exception( "'".$classname."' Method not found");

  } # END private function get_class

	public function __call($name, $arguments){

      try{

      	return $this->get_class( $name, $arguments );

      }
      catch(Exception $e) {

		      	$notice = new sphinx\notice\sphinx_notice( $e->getMessage() );
		      	echo $notice->get();
		        exit;

			}

  } # END public function __call

} # END class sphinX
