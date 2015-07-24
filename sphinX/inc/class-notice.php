<?php
namespace sphinx\inc;

if( class_exists( 'sphinx\inc\Notice' ) ) return;

class Notice{

	private $notices;

	function __construct( $config, $args = array() ){

		if( isset( $args ) && is_array( $args ) && isset( $GLOBALS['sphinx_notices'] ) and !empty( $GLOBALS['sphinx_notices'] ) )
			$GLOBALS['sphinx_notices'] = array_merge_recursive( $args, $GLOBALS['sphinx_notices'] );
		else if( !empty( $args ) )
			$GLOBALS['sphinx_notices'] = $args;

    if( isset( $GLOBALS['sphinx_notices'] ) and !empty( $GLOBALS['sphinx_notices'] ) )
		  $this->set($GLOBALS['sphinx_notices']);

	}

	function set( $notices ){
		$this->notices = $notices;
	}

	function get(){
		return $this->notices;
	}

	function display(){

		$this->styled();

	}

	private function styled(){

		$notices = $this->get();

		if( !empty( $notices ) ){

			foreach ($notices as $type => $notice) {
				if( !empty( $notice ) ){

					printf( '<ul class="%s">', "sphinx-notice-list sphinx-".$type );

					foreach ($notice as $key => $note) {
						printf( '<li>%s</li>', $note );
					}

					echo "</ul>";

				}
			}

		}

	}
}
