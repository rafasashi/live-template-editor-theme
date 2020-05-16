<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package LTPLE_Theme
 */

?>

<section class="no-results not-found">
	<header class="page-header">
		<h2 class="page-title"><?php esc_html_e( 'Nothing Found', 'ltple-theme' ); ?></h2>
	</header><!-- .page-header -->

	<div class="page-content">
	
		<div class="row">
		
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
						
			<div class="col-12">
			
				<p><?php printf( wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'ltple-theme' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
			
			</div>
			
		<?php elseif ( is_search() ) : ?>
			
			<div class="col-12">
			
				<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'ltple-theme' ); ?></p>
			
			</div>
			
			<div class="col-12 col-md-6">
			
				<?php get_search_form(); ?>
				
			</div>

		<?php else : ?>
			
			<div class="col-12">
			
				<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'ltple-theme' ); ?></p>
			
			</div>
			
			<div class="col-12 col-md-6">
			
				<?php get_search_form(); ?>
				
			</div>

		<?php endif; ?>
		
	</div><!-- .page-content -->
</section><!-- .no-results -->
