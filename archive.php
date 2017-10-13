<?php
/**
 * The template for displaying Archive pages.
 */
get_header();
wp_enqueue_script( 'wow-isotopejs', null, false );
?>

<div class="container">

	<div class="panel-header">
	
		<h1 style="padding: 30px 30px;font-weight: bold;background: rgba(158, 158, 158, 0.24);color: rgb(138, 206, 236);">
			
			<?php
				if ( is_category() ) :
					single_cat_title();

				elseif ( is_tag() ) :
					single_tag_title();

				elseif ( is_author() ) :
					/* Queue the first post, that way we know
					 * what author we're dealing with (if that is the case).
					*/
					the_post();
					printf( __( 'Author: %s', 'ltple-theme' ), '<span class="vcard">' . get_the_author() . '</span>' );
					/* Since we called the_post() above, we need to
					 * rewind the loop back to the beginning that way
					 * we can run the loop properly, in full.
					 */
					rewind_posts();

				elseif ( is_day() ) :
					printf( __( 'Day: %s', 'ltple-theme' ), '<span>' . get_the_date() . '</span>' );

				elseif ( is_month() ) :
					printf( __( 'Month: %s', 'ltple-theme' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

				elseif ( is_year() ) :
					printf( __( 'Year: %s', 'ltple-theme' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

				elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
					_e( 'Asides', 'ltple-theme' );

				elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
					_e( 'Images', 'ltple-theme');

				elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
					_e( 'Videos', 'ltple-theme' );

				elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
					_e( 'Quotes', 'ltple-theme' );

				elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
					_e( 'Links', 'ltple-theme' );

				else :
					
					//_e( 'Archives', 'ltple-theme' );
					the_archive_title();

				endif;
			?>
		</h1>
		
		<?php
		// Show an optional term description.
		$term_description = term_description();
		if ( ! empty( $term_description ) ) :
			printf( '<div class="taxonomy-description">%s</div>', $term_description );
		endif;
		?>
		
	</div>	

	<div class="col-md-10">
	
		<div class="row tiles blogindex content-area">
		
			<?php if ( have_posts() ) : ?>
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
				
					<?php get_template_part( 'content', 'search' ); ?>
						
				<?php endwhile; ?>
			<?php else : ?>
				<?php get_template_part( 'no-results', 'archive' ); ?>
			<?php endif; ?>
		</div><!-- #content -->
		
		<div class="clearfix"></div>
		
		<?php the_posts_pagination();?>
		
	</div>
	
	<?php get_sidebar(); ?>

</div>
<?php get_footer(); ?>
