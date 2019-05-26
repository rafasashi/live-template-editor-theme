<!DOCTYPE html>
<?php

	// add menu
	
	add_filter( 'wp_nav_menu', array( $ltple, 'get_menu' ), 10, 2);			
	
	// enqueue plugin styles
	
	$ltple->enqueue_styles();
	$ltple->enqueue_scripts();
	
	// enqueue theme styles
	
	wow_bootstrap_scripts_styles();
	
?>	
<html <?php language_attributes(); ?>>
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	
	<?php wp_print_styles(); ?>
	
	<?php $ltple->get_header(); ?>

</head>

<body <?php body_class('boxedlayout'); ?>>
	
	<div id="ltple-wrapper">
		
		<?php include_once( $ltple->theme->dir . '/navbar.php' );	?>
			
		<div class="container">
			<div class="row">
			
				<div class="col-md-12">			
					
					<main id="main" class="site-main" role="main">
						
						<?php while ( have_posts() ) : the_post(); ?>

							<div class="saas-content">
							
								<?php 
								
								the_content();
								
								wp_link_pages( array(
									'before' => '<div class="page-links"><div class="col-md-1"></div><div class="col-md-10">' . __( 'Pages:', 'ltple-theme' ),
									'after'  => '</div><div class="col-md-1"></div></div>',
								) );
								
								?>
							</div><!-- .saas-content -->		
						
						<?php endwhile; // end of the loop. ?>

					</main><!-- #main -->			
				</div>
			</div>
		</div>
		
		<!-- FOOTER  -->
		
		<?php wp_footer(); ?>
		
	</div>
	
</body>
</html>