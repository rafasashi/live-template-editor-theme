<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 */

get_header();
wp_enqueue_script( 'wow-isotopejs', null, false );
?>

<div class="container">
	<div class="row">
	
		<div class="col-md-1"></div>
		
		<div class="col-md-10">

		<?php
		$args = array(
		'posts_per_page' => 1,
		'post__in'  => get_option( 'sticky_posts' ),
		'ignore_sticky_posts' => 1
		);
		query_posts( $args );

		$do_not_duplicate = array();

		$custom_query = new WP_Query( $args );
		if ( $custom_query->have_posts() ):
		while ( $custom_query->have_posts() ) :
		$custom_query->the_post();

		$do_not_duplicate[] = $post->ID;
		?>
			<div class="hero-unit box effect2" style="margin-top:45px;">
			
				<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				<p>
					<a class="entry-thumbnail pull-left paddingright top10" href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php global $post; echo get_the_post_thumbnail($post->ID, 'sticky-thumb'); ?></a>

					<?php echo wow_get_custom_excerpt(360); ?> <a href="<?php the_permalink(); ?>" style="color:#666;">[...]</a>
				</p>
				<p>
					<a href="<?php the_permalink(); ?>" class="btn btn-primary btn-large"><?php echo __( 'Read more', 'ltple-theme' );?></a>
				</p>
			</div>
		<?php
			endwhile;
		else:
			// Insert any content or load a template for no posts found.
		endif;
		wp_reset_query();
		?>


		<?php // IF STICKY FOUND ?>

		<?php $sticky = get_option('sticky_posts');	if (!empty($sticky)) { ?>


		<div class="row tiles blogindex content-area">
			<?php query_posts( array(
						'post_type' => array('post'),
						'paged' => $paged,
						'ignore_sticky_posts' => 1,  'post__not_in' => get_option( 'sticky_posts' )
					) );
			if ( have_posts() ) : ?>
					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post();
						
						global $post;
					
						echo'<div class="' . implode( ' ', get_post_class("col-xs-12 col-sm-6",$post->ID) ) . '" id="post-' . $post->ID . '">';
							
							echo'<div class="panel panel-default">';

								echo'<div class="thumb_wrapper" style="background:url(' . get_the_post_thumbnail_url($post->ID, 'recentprojects-thumb') . ');background-size:cover;background-repeat:no-repeat;background-position:center center;"></div>'; //thumb_wrapper					
								
								echo'<div class="panel-body">';
									
									echo '<h3 style="height:50px;overflow:hiden;">' . $post->post_title . '</h3>';
									
									echo wow_get_custom_excerpt(170);
									 
								echo'</div>';
								
								echo'<div style="background:#fff;border:none;" class="panel-footer text-right">';
									
									echo'<a class="btn btn-sm btn-primary" href="' . get_permalink() . '" target="_self">Read more</a>';
									
								echo'</div>';
							
							echo'</div>';
							
						echo'</div>';
						
					endwhile; ?>
				<?php else : ?>
					<?php get_template_part( 'no-results', 'index' ); ?>
				<?php endif; ?>
		</div><!-- #content -->

		<?php // IF STICKY NOT FOUND ?>
		<?php } else { ?>


		<div class="row tiles blogindex content-area">
			<?php
			query_posts( array(
						'post_type' => array('post'),
						'paged' => $paged,
						'post__not_in' => array_merge($do_not_duplicate, get_option( 'sticky_posts' )),
						'ignore_sticky_posts' => 1
					) );
			if ( have_posts() ) : ?>
					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post();  $do_not_duplicate[] = $post->ID;

						global $post;
					
						echo'<div class="' . implode( ' ', get_post_class("col-xs-12 col-sm-6",$post->ID) ) . '" id="post-' . $post->ID . '">';
							
							echo'<div class="panel panel-default">';

								echo'<div class="thumb_wrapper" style="background:url(' . get_the_post_thumbnail_url($post->ID, 'recentprojects-thumb') . ');background-size:cover;background-repeat:no-repeat;background-position:center center;"></div>'; //thumb_wrapper					
								
								echo'<div class="panel-body">';
									
									echo '<h3 style="height:50px;overflow:hiden;">' . $post->post_title . '</h3>';
									
									echo wow_get_custom_excerpt(150) . ' [...]';
									 
								echo'</div>';
								
								echo'<div style="background:#fff;border:none;" class="panel-footer text-right">';
									
									echo'<a class="btn btn-sm btn-primary" href="' . get_permalink() . '" target="_self">Read more</a>';
									
								echo'</div>';
							
							echo'</div>';
							
						echo'</div>';				
						
					endwhile; ?>
					
				<?php else : ?>
					<?php get_template_part( 'no-results', 'index' ); ?>
				<?php endif; ?>
		</div><!-- #content -->

		<?php } ?>

		<?php // END QUERIES ?>



			<div class="clearfix"></div>
			<?php the_posts_pagination();?>
		</div><!-- .col-md-8 -->
		
		<div class="col-md-1"></div>

		<?php //get_sidebar(); ?>

	</div><!-- .row -->
</div>

<?php get_footer(); ?>
