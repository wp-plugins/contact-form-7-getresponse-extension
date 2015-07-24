<?php


namespace sphinx\inc;

if( class_exists( 'sphinx\inc\Form' ) ) return;

/**
 *
 */
class Form{

  	var $error_list, $plain, $form_attr, $form_class;

  	function __construct(){
  	}

  	function init( $args ){

  		$defaults = array(
  			'form_class' => array(),
  			'form_class' => "",
  			'form_fields' => array(),
  			'plain' => false
  		);

  		$args = wp_parse_args( $args, $defaults );

  		$this->form_attr = $args['form_attr'];
  		$this->form_class = $args['form_class'];
  		$this->plain = $args['plain'];
  		$fields = $args['form_fields'];

  		$output = $this->open( $this->form_attr, true );

  		if( !empty( $fields ) ){

  			foreach ( $fields as $key => $args) {

  				$output .= $this->field( $args, true, $this->plain );

  			}

  		}

  		$output .= wp_nonce_field( $this->form_attr['name']."_nonce_action", $this->form_attr['name'].'_nonce_field', true, false );
  		$output .= $this->close( array(), true);
  		$this->_output = $output;

  		return $this;
  	}

  	function display(){

  		echo $this->get();

  	}

  	function get(){
  		wp_enqueue_style( 'sphinx-form-style' );
  		return $this->_output;
  	}



  	function is_valid(){

  		if( empty( $this->error_list ) )
  			return true;

  		return false;

  	}

  	function open( $args = array(), $return = false ){

  		$defaults = array(
  			'name' => "",
  			'id' => "",
  			'method' => 'post',
  			'class' => array(),
  			'enctype' => ''
  		);


  		$_class = 'sphinx-form';

  		if( !empty( $class ) )
  			$_class .= implode( ' ', $class );


  		$args = wp_parse_args( $args, $defaults );

  		extract( $args );

  		$output = sprintf( '<form name="%s" id="%s" method="%s" role="form" class="%s" enctype="%s">', $name, $id, $method, $_class, $enctype );

  		if( $return )
  			return $output;

  		echo $output;

  	}

  	function field( $args, $return = false ){

  		$defaults = array(
  			'name' => "",
  			'id' => "",
  			'label' => "",
  			'validation' => array(),
  			'note' => ''
  		);

  		$args = wp_parse_args( $args, $defaults );

  		extract( $args );

  		$sphinX_Fields = new \sphinx\inc\Field;

  		$field = $sphinX_Fields->get_field(  $args );

  		if( $this->plain )
  			$output = $field;
  		else if( 'hidden' == $type )
  			$output = $field;
  		else {
  			$required_text = ( isset( $validation['required'] ) )?'<span class="require">*</span>':'';
  			$output = '<fieldset class="form-group">';
  			$output .= sprintf( '<label for="%s">%s %s</label>', $id, $label, $required_text );
  			$output .= '<div class="field">';
  			$output .=  $field;
  			$output .= ( $note != '' )? sprintf( '<small class="description">%s</small>', $note ):"";
  			$output .= '</div>';
  			// $output .= $this->validation( $args );

  			$output .= '</fieldset>';
  		}

  		if( $return )
  			return $output;

  		echo $output;

  	}

  	function close( $args = array(), $return = false ){

  		$output = '</form>';

  		if( $return )
  			return $output;

  		echo $output;

  	}

  	function validation( $args ){

  		$defaults = array(
  			'type' => 'text',
  			'name' => "",
  			'id' => "",
  			'label' => "",
  			'validation' => array(),
  		);

  		$args = wp_parse_args( $args, $defaults );

  		extract( $args );

  		try {
  			$input = ( 'file'== $type )?$_FILES[$name]:$_POST[$name];
  			\sphinx\inc\Validation::init( $type )->input( $input )->rules( $validation )->validate();
  		} catch (\Exception $e) {
  			$this->error_list[] = true;
  			$GLOBALS['sphinx_notices']['error'][] = $e->getMessage();
  		}

  	}

  	function validate_form( $fields = array() ){
  		if( !empty( $fields ) ){
  			foreach ($fields as $key => $field) {
  				$this->validation( $field );
  			}
  		}

  		if( empty( $this->error_list ) )
  			return true;

  		return false;
  	}


  }
