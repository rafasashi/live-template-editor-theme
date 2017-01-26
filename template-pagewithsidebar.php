<?php
/*
Template Name: Page with Sidebar
*/
get_header(); ?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="pgheadertitle animated fadeInLeft"><?php the_title(); ?></h1>
		<div class="headerdivider"></div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-9">
			<div class="content-area">
				<main id="main" class="site-main" role="main">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'content', 'page' ); ?>
						<?php 
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number() )
							comments_template();
						?>
					<?php endwhile; // end of the loop. ?>
				</main><!-- #main -->
			</div><!-- .content-area -->
		</div><!-- .col-md-9 -->
		<?php get_sidebar('page'); ?>
	</div><!-- .row -->	
</div><!-- .container -->
<?php get_footer(); ?>