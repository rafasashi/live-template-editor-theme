<?php
/**
 * The template used for displaying page content in page.php
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>	

	<div class="entry-content" style="display:inline-block;width:100%;">
	
		<?php 
		
		the_content();
		
		?>

		<?php 
		
		wp_link_pages( array(
			'before' => '<div class="page-links"><div class="col-md-1"></div><div class="col-md-10">' . __( 'Pages:', 'ltple-theme' ),
			'after'  => '</div><div class="col-md-1"></div></div>',
		) );
		
		?>
		
	</div><!-- .entry-content -->
</article><!-- #post-## -->