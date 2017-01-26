<?php
/**
 * The Template for displaying all single posts.
 */
get_header(); ?>

<div class="container">
	<div class="row">
		<div class="col-md-8">
			<div id="primary" class="content-area">
				<main id="main" class="inneritempost site-main" role="main">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content', 'single' ); ?>
					<?php wow_content_nav( 'nav-below' ); ?>
					<div class="clearfix"></div>
					<?php
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number() )
							comments_template();
					?>
				<?php endwhile; // end of the loop. ?>
				</main><!-- #main -->
			</div><!-- #primary -->
		</div>
		<?php get_sidebar(); ?>
	</div><!-- /.row -->
</div><!-- /.container -->
<?php get_footer(); ?>