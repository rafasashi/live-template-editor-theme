<?php
/**
 * The template for displaying Search Results pages.
 */

get_header();
?>

<div class="container">
	<div class="row">
		<div class="col-md-8">
			<h1 class="pgheadertitle animated fadeInLeft"><?php printf( __( 'Search Results for: %s', 'ltple-theme' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			<div class="headerdivider"></div>
				<?php if ( have_posts() ) : ?>
                                                   <!--- delete the style values or change none to block--->
					<div class="row tiles blogindex">
						<?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) : the_post(); ?>
                                                                <?php get_template_part( 'content', 'search' ); ?>
								
						<?php endwhile; ?>
					</div>

					<div class="clearfix"></div>
						<?php the_posts_pagination();?>
					<div class="clearfix"></div>

				<?php else : ?>
					<?php get_template_part( 'no-results', 'search' ); ?>
				<?php endif; ?>
			<div class="clearfix"></div>
		</div><!-- .col-md-9 -->
		<?php get_sidebar(); ?>
	</div><!-- .row -->
</div>
<?php get_footer(); ?>
