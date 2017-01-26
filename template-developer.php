<?php
/* Template Name: No title
*/
get_header(); ?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="content-area">	
				<main id="main" class="site-main" role="main">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'content', 'page' ); ?>
					<?php endwhile; // end of the loop. ?>
				</main><!-- #main -->
			</div><!-- #primary -->
		</div>
	</div>
</div>
<?php get_footer(); ?>