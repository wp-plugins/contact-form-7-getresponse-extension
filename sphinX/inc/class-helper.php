<?php
namespace sphinx\inc;

if( class_exists( 'sphinx\inc\Helper' ) ) return;

class Helper{

	function __construct( $method, $args ){

    $this->{$method}( $args );

  }

}
