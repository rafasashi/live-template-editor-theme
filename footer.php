<?php
/**
 * The template used for displaying Footer
 */

// Gets all the scripts included by wordpress, wordpress plugins or functions.php

?>
<!-- FOOTER BEGIN
	================================================== -->
	
	<?php 
	
		if ( is_active_sidebar( 'footerwidgets' ) ) {
			
			echo'<footer id="colophon" role="contentinfo">';
			
			echo'<div class="text-center wraptotop">';
				echo'<a class="totop" style="border:none;"><i class="fa fa-chevron-up"></i></a>';
			echo'</div>';				
			
			echo'<div class="footer">';
				echo'<div class="container">';
					//echo'<div class="row">';
				
						dynamic_sidebar( 'footerwidgets' );
						
					//echo'</div>';
				echo'</div>';
			echo'</div>';
		}
		else{
			
			echo'<footer role="contentinfo">';
		}
	?>

		<div class="footerbottom">
			<div class="container">
			
				<div class="row">
				
					<!-- left -->
					<div class="col-md-5">
					
						 <?php
						  if( get_theme_mod( 'wow_copyright' ) == '') { ?>
						  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
						  <?php }
						  else { echo wp_kses_post( get_theme_mod( 'wow_copyright' ) ); } ?>

					</div>
					
					<!-- right -->
					<div class="col-md-7 smallspacetop">
						<div class="pull-right smaller">
						<?php
						if(wp_nav_menu( array( 'theme_location' => 'footer',
												'container'  => false,
												'depth'		 => 0,
												'menu_class' => 'footermenu',
												'fallback_cb' => 'false') ))
						{
						echo wp_nav_menu( array( 'sort_column' => 'menu_order', 'container'  => false, 'theme_location' => 'footer' , 'echo' => '0' ) );
						}
						else
						{

						}
						?>
						</div>
						<div class="clearfix"></div>
					</div>
					<!-- end right -->
					
				</div>
				
			</div>
		</div>
	</footer>
<!-- FOOTER END
================================================== -->
</div>

<?php wp_footer(); ?>
</body>
</html>
