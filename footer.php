<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package LTPLE_Theme
 */

?>
<?php if(!is_page_template( 'templates/full-width.php' )): ?>
			</div><!-- .row -->
		</div><!-- .container -->
	</div><!-- #content -->
<?php endif; ?>

    <?php get_template_part( 'footer-widget' ); ?>
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="container pt-3 pb-3">
            
			<div class="site-info row">
				
				<div class="col-12 col-sm-6 text-center text-sm-left">
					
					<ul class="list-inline">
					
						<li class="list-inline-item">&copy; <?php echo date('Y'); ?> <?php echo '<a href="'.home_url().'">'.get_bloginfo('name').'</a>'; ?></li>
						<li class="list-inline-item"> | </li>
						<li class="list-inline-item"><?php echo esc_html__('Powered by ','ltple-theme'); ?> <a class="credits" href="https://code.recuweb.com/" target="_blank" title="WordPress Technical Support" alt="Code Market"><?php echo esc_html__('Recuweb','ltple-theme'); ?></a></li>
					
					</ul>
					
				</div><!-- close .site-info -->
				
				<?php
				
					if( has_nav_menu( 'footer' ) ) {
						
						if( class_exists('LTPLE_Theme_Navwalker') ){
						
							wp_nav_menu( array(
							
								'theme_location'  	=> 'footer',
								'depth'           	=> 1,
								'container'       	=> 'div',
								'container_class' 	=> 'col-12 col-sm-6 text-center text-sm-right',
								'menu_class'      	=> 'list-inline',
								'add_li_class'  	=> 'list-inline-item',
								'fallback_cb' 	  	=> 'LTPLE_Theme_Navwalker::fallback',
								'walker'          	=> new LTPLE_Theme_Navwalker(),
							));
						}
						else{
							
							wp_nav_menu( array(
							
								'theme_location'  	=> 'footer',
								'depth'           	=> 1,
								'container'       	=> 'div',
								'container_class' 	=> 'col-6 text-right',
								'menu_class'      	=> 'list-inline',
								'add_li_class'  	=> 'list-inline-item',
							));						
						}
					}		
				?>
				
			</div>
		
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>