<?php
/**
 * The template used for displaying Footer
 */

// Gets all the scripts included by wordpress, wordpress plugins or functions.php

?>
<!-- FOOTER BEGIN
	================================================== -->
	<footer id="colophon" role="contentinfo">
	<div class="text-center wraptotop">
		<a class="totop" style="border:none;"><i class="fa fa-chevron-up"></i></a>
	</div>
		<div class="footer">
			<div class="container">
				<div class="row">
					<?php if ( is_active_sidebar( 'footerwidgets' ) ) : ?>
					<?php dynamic_sidebar( 'footerwidgets' ); ?>
					<?php else : ?>
					<!-- This content shows up if there are no widgets defined in the backend. -->
					<div class="col-md-4">
					<!-- This content shows up if there are no widgets defined in the backend. -->
					<div class="help">
						<p>
							<?php _e("Hello, activate some Widgets!", "ltple-theme"); ?>
							<?php if(current_user_can('edit_theme_options')) : ?>
							<a href="<?php echo admin_url('widgets.php')?>" class="add-widget"><?php _e("Add Widget", "ltple-theme"); ?></a>
							<?php endif ?>
						</p>
					</div>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>



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
						<div class="clearfix">
						</div>
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
