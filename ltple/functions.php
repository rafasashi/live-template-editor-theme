<?php

include_once trailingslashit( dirname(__FILE__) ) . 'inc/class-ltple-theme.php';
include_once trailingslashit( dirname(__FILE__) ) . 'inc/class-ltple-custom-controls.php';

function LTPLE_Theme( $args = array() ) {
	
	$instance = LTPLE_Theme::instance( __FILE__, $args );

	return $instance;
}

$theme = LTPLE_Theme();
