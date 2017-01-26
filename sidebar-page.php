<?php
/**
 * The Page Sidebar containing the main widget areas.
 */
?>
	<div class="col-md-4">
		<div id="secondary" class="widget-area" role="complementary">
			<?php do_action( 'before_sidebar' ); ?>
			<?php if ( ! dynamic_sidebar( 'sidebar-2' ) ) : ?>

				<aside id="archives" class="widget">
					<h1 class="widget-title"><span class="htitle"><?php _e( 'Archives', 'ltple-theme' ); ?></span></h1>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
				</aside>

				<aside id="meta" class="widget">
					<h1 class="widget-title"><span class="htitle"><?php _e( 'Meta', 'ltple-theme' ); ?></h1></span>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</aside>

			<?php endif; // end sidebar widget area ?>
		</div><!-- #secondary -->
	</div><!-- .col-md-4 -->