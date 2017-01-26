<?php
/**
 * The template used for displaying Search results.
 */
?>
<?php if ( is_search() ) : // Only display Excerpts for Search
wp_enqueue_script( 'wow-isotopejs' ); 

?>
<div <?php post_class("col-md-6"); ?> id="post-<?php the_ID(); ?>">
	<div class="inneritem">
		<div class="wrapinneritem">
		<header class="entry-header sectiontitlepost">
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			<div class="entry-meta">
				<div class="pull-left"><i class="fa fa-calendar"></i>&nbsp; <?php the_time( get_option( 'date_format' ) ); ?></div>
				<div class="text-right"><i class="fa fa-comment"></i>&nbsp;  <?php comments_popup_link( __( 'Add Comment', 'ltple-theme' ), __( '1 Comment', 'ltple-theme' ), __( '% Comments', 'ltple-theme' ), __( 'Comments off', 'ltple-theme' ) ); ?></div>
			</div><!-- .entry-meta -->
			<a class="entry-thumbnail" href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php global $post; echo get_the_post_thumbnail($post->ID, 'recentprojects-thumb'); ?></a>

		</header><!-- .entry-header -->
		<div class="entry-content">

			<?php echo wow_get_custom_excerpt(160); ?> <a href="<?php the_permalink(); ?>">[...]</a>
		</div><!-- .entry-content -->
		</div>
	</div>
</div><!-- #boxportfolio-## -->
<?php else : ?>
<?php endif; ?>
