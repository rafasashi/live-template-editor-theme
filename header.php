<?php

	$is_profile_page = false;
		
	if( class_exists('LTPLE_Client') ){
		
		$ltple = LTPLE_Client::instance();
		
		if( !empty($ltple->profile->id) ){
			
			$is_profile_page = true;
		}
		
		// add head
		
		remove_action( 'wp_head', '_wp_render_title_tag', 1 );
		
		add_action( 'wp_head', array( $ltple, 'get_header') );
		
		// add menu
		
		add_filter( 'wp_nav_menu', array( $ltple, 'get_menu' ), 10, 2);			
		
	}
	
	define('LTPLE_IS_PROFILE_PAGE',$is_profile_page);
?>
<!DOCTYPE html>	
<html <?php language_attributes(); ?> class="<?php echo apply_filters('ltple_document_classes','ltple-theme'); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head();?>
</head> 
<body <?php body_class('boxedlayout'); ?>>

	<div id="ltple-wrapper" class="boxedcontent" style="position:absolute;z-index:auto;border:none;">
		
	<?php 
		
		if( LTPLE_IS_PROFILE_PAGE ){
			
			include_once('navbar-profile.php'); 
		}
		elseif( empty($_REQUEST['output']) || $_REQUEST['output'] != 'widget' ){
			
			include_once('navbar.php'); 
		}
		
		echo '<div class="floating-buttons" style="position:fixed;z-index:1050;right:0;bottom:50px;margin:15px 3%;">';
		
			echo apply_filters('ltple_floating_buttons','');
			
		echo '</div>';