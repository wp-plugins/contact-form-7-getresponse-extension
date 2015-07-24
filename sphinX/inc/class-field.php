<?php
namespace sphinx\inc;


if( class_exists( 'sphinx\inc\Field' ) ) return;

class Field{

  function __construct(){

	}

	function get_field( $args ){

		if( !isset( $args['type'] ) )
			return 'Please set type';

		else if( 'select' == $args['type'] )
			return $this->select( $args );

		else if( 'text' == $args['type'] )
			return $this->text( $args );

		else if( 'submit' == $args['type'] )
			return $this->submit( $args );

		else if( 'textarea' == $args['type'] )
			return $this->textarea( $args );

		else if( 'radio' == $args['type'] )
			return $this->radio( $args );

		else if( 'checkbox' == $args['type'] )
			return $this->checkbox( $args );

		else if( 'file' == $args['type'] )
			return $this->file( $args );

		else if( 'hidden' == $args['type'] )
			return $this->hidden( $args );

		else
			return __( 'Field type not found!' );

	}


	function select( $args ){

		$defaults = array(
			'type' => "select",
			'name' => "",
			'id' => "",
			'value' => ""
		);

		$args = wp_parse_args( $args, $defaults );

		extract( $args );

		$output = sprintf( '<select name="%s" id="%s">', $name, $id );
		if( isset( $options ) && !empty( $options ) ){

			foreach ($options as $_value => $option) {
				$output .= sprintf( '<option value="%s" %s>%s</option>', $_value, selected( $value, $_value, false ) , $option ) ;
			}

		}
		$output .= '</select>';

		return $output;

	}

	function text( $args ){

		$defaults = array(
			'type' => 'text',
			'name' => "",
			'id' => "",
			'placeholder' => "",
			'value' => ""
		);

		$args = wp_parse_args( $args, $defaults );

		extract( $args );

		$output = sprintf( '<input type="%s" name="%s" id="%s" placeholder="%s" value="%s" />', $type, $name, $id, $placeholder, $value );

		return $output;

	}

	function hidden( $args ){

		$defaults = array(
			'type' => 'hidden',
			'name' => "",
			'id' => "",
			'placeholder' => "",
			'value' => ""
		);

		$args = wp_parse_args( $args, $defaults );

		extract( $args );

		$output = sprintf( '<input type="%s" name="%s" id="%s" placeholder="%s" value="%s" />', $type, $name, $id, $placeholder, $value );

		return $output;

	}

	function textarea( $args ){

		$defaults = array(
			'name' => "",
			'id' => "",
			'placeholder' => "",
			'value' => ""
		);

		$args = wp_parse_args( $args, $defaults );

		extract( $args );

		$output = sprintf( '<textarea name="%s" id="%s" placeholder="%s">%s</textarea>', $name, $id, $placeholder, $value );

		return $output;

	}

	function submit( $args ){

		$defaults = array(
			'type' => 'submit',
			'name' => "",
			'id' => "",
			'value' => "Submit"
		);

		$args = wp_parse_args( $args, $defaults );

		extract( $args );

		$output = sprintf( '<input type="%s" name="%s" id="%s" value="%s"/>', $type, $name, $id, $value );

		return $output;

	}

	function radio( $args ){
		$defaults = array(
			'name' => "",
			'id' => "",
		);

		$args = wp_parse_args( $args, $defaults );

		extract( $args );

		$output = sprintf( '<input type="radio" name="%s" id="%s" />', $name, $id);

		return $output;
	}

	function checkbox( $args ){

		$defaults = array(
			'name' => "",
			'id' => "",
			'value' => "",
			'checked' =>  false
		);

		$args = wp_parse_args( $args, $defaults );

		extract( $args );

		$_checked = "";
		if( $checked )
			$_checked = 'checked="checked"';
		$output = sprintf( '<input type="checkbox" name="%s" id="%s" value="%s" %s />', $name, $id, $value,  $_checked );

		return $output;
	}

	function file( $args ){

		$defaults = array(
			'name' => "",
			'id' => "",
		);

		$args = wp_parse_args( $args, $defaults );

		extract( $args );

		$output = sprintf( '<input type="file" name="%s" id="%s" />', $name, $id);

		return $output;

	}

}
