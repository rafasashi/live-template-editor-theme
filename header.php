<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package LTPLE_Theme
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="http://gmpg.org/xfn/11">
	<title>
		<?php echo ucfirst( wp_title( '|', false, 'right' ) ); ?>
	</title>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">

	<header id="masthead" class="site-header navbar-static-top" role="banner">
        
		<div class="container">

            <nav class="navbar navbar-expand-md">
                
				<div class="navbar-brand">
                    
					<a class="site-title" href="<?php echo esc_url( home_url( '/' )); ?>"><?php esc_url(bloginfo('name')); ?></a>

                </div>
				
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-nav" aria-controls="" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <?php
				
				if( class_exists('LTPLE_Theme_Navwalker') ){
					
					wp_nav_menu(array(
					'theme_location'    => 'header',
					'container'       => 'div',
					'container_id'    => 'main-nav',
					'container_class' => 'collapse navbar-collapse justify-content-end',
					'menu_id'         => false,
					'menu_class'      => 'navbar-nav',
					'depth'           => 3,
					'fallback_cb'     => 'LTPLE_Theme_Navwalker::fallback',
					'walker'          => new LTPLE_Theme_Navwalker()
					));
				}
				else{
				
					wp_nav_menu(array(
					'theme_location'  => 'header',
					'container'       => 'div',
					'container_id'    => 'main-nav',
					'container_class' => 'collapse navbar-collapse justify-content-end',
					'menu_id'         => false,
					'menu_class'      => 'navbar-nav',
					'depth'           => 3,
					));
				}
				
                ?>

            </nav>
        </div>
	</header><!-- #masthead -->
	
	<?php if(!is_page_template( 'templates/full-width.php' )): 
		
		get_template_part('title');
	?>
	<div id="content" class="site-content">
		<div class="container">
			<div class="row">
    <?php endif; ?>