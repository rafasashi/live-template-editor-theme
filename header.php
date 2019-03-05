<?php

	if( class_exists('LTPLE_Client') ){
		
		$ltple = LTPLE_Client::instance();			
		
		// add head
		
		remove_action( 'wp_head', '_wp_render_title_tag', 1 );
		
		add_action( 'wp_head', array( $ltple, 'get_header') );
		
		// add menu
		
		add_filter( 'wp_nav_menu', array( $ltple, 'get_menu' ), 10, 2);			
		
	}
?>		
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head();?>
</head> 
<body <?php body_class('boxedlayout'); ?>>
	
	<div id="ltple-wrapper" class="boxedcontent" style="z-index:auto;border:none;">
		
		<?php include_once('navbar.php'); ?>