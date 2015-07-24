<?php
namespace sphinx\inc;

if( class_exists( 'sphinx\inc\Template' ) ) return;

class Template{

  private $template_base, $override, $plugin_folder, $plugin_base;

	function __construct( $config, $args = array() )
	{
		$this->template_base = $config['base_path']."/".$config['template_dir'];
		$this->override = ( isset( $args['override'] )?$args['override']:TRUE );
    $this->plugin_base = $config['base_path'];
    $this->plugin_folder =  $config['base_dir'] ;
	}

	function get( $file_name, $args ){

		extract( $args );

    $file_parts = pathinfo($file_name);

		if( isset( $file_parts['extension'] ) && 'php' == $file_parts['extension'] ){
			$_file_name = $file_name;
		}
		else
			$_file_name = $file_name.".php";

    if( TRUE === $this->override ){

      $locate_template = $this->plugin_folder."/".ltrim( $_file_name, '/');
  		if( locate_template( $locate_template ) ){
  			include sprintf( '%s/%s', get_stylesheet_directory(), $locate_template );
  			return;
  		}

    }

		$file_path = sprintf( '%s/%s', $this->template_base, $_file_name );

		if( !file_exists( $file_path ) ){
			$notice = new \sphinx\inc\notice( array( 'error' => array( $file_name.' Not found' ) ) );
      $notice->display();
      exit;
		}

		if( !file_exists( $file_path ) )
			return __( 'File not found!' );


		include  $file_path ;
	}

  function display( $file_name, $args = array() ){
    echo $this->get( $file_name, $args );
  }
}
