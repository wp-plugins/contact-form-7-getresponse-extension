<?php
namespace sphinx\inc;

// Exit if file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

if( class_exists( 'sphinx\inc\Validation' ) ) return;

class Validation{

  private static $input = '';
  private static $type;
	private static $rules;
  private static $_instance = null;

	function __construct(){}
  public static function init( $type = '' )
  {
      self::$type = $type;

      if (self::$_instance === null) {
          self::$_instance = new self;
      }

      return self::$_instance;
  }
  function input( $input ){
    self::$input = $input;
    return self::$_instance;
  }
  function rules( $rules ){
    self::$rules = $rules;
    return self::$_instance;
  }
  function validate(){
    if( 'file' == self::$type ){

			if( !empty( self::$input ) && self::$input['size'] > 0 ){

				if( isset( self::$rules['max_size']['rule'] ) && ( self::$input['size']/1024 ) > self::$rules['max_size']['rule'] ){

          if( isset( self::$rules['max_size']['message'] ) && '' != self::$rules['max_size']['message'] )
            throw new \Exception( self::$rules['max_size']['message'] );
          else
            throw new \Exception('Exceeded file limit.');

				}

				if( isset( self::$rules['file_type']['rule'] ) ){

					$filename = self::$input['name'];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					if(!in_array($ext, self::$rules['file_type']['rule'] ) ) {

            if( isset( self::$rules['file_type']['message'] ) && '' != self::$rules['file_type']['message'] )
              throw new \Exception( self::$rules['file_type']['message'] );
            else
              throw new \Exception('Invalid file type.');

					}

				}

			}


			return;
		}
    else if( isset( self::$rules['required']['rule'] ) && true === self::$rules['required']['rule'] && !self::notEmpty() ){
      if( isset( self::$rules['required']['message'] ) && '' != self::$rules['required']['message'] )
        throw new \Exception( self::$rules['required']['message'] );
      else
        throw new \Exception( 'Field is required' );
    }

  }
	static function string(){
    is_string(self::$input);
    return $this;
  }

  static function notEmpty(){

    if ( is_string( self::$input ) ) {

        $input = trim( sanitize_text_field( self::$input ) );

    }

    return !empty( $input );
  }

}
