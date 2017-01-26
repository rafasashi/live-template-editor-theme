<?php
/**
 * The template used for displaying Single posts.
 */
?>
<article <?php post_class("singlepost"); ?> id="post-<?php the_ID(); ?>">

	<h1 class="pgheadertitle animated fadeInLeft"><?php the_title(); ?></h1>
	<div class="headerdivider"></div>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'ltple-theme' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->


	<header class="wowmetaposts entry-meta">
		<span class="wowmetadate"><i class="fa fa-clock-o"></i> <?php the_time( get_option( 'date_format' ) ); ?></span>
		<span class="wowmetaauthor"><i class="fa fa-user"></i> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author_meta( 'display_name' ); ?></a></span>
		<span class="wowmetacats"><i class="fa fa-folder-open"></i>
		<?php
		$categories = get_the_category();
		$separator = ' , ';
		$output = '';
		if($categories){
			foreach($categories as $category) {
				$output .= '<a href="'.get_category_link( $category->term_id ).'">'.$category->cat_name.'</a>'.$separator;
			}
		echo trim($output, $separator);}
		?></span>
		<span class="wowmetacommentnumber"><i class="fa fa-comments"></i> <?php comments_popup_link( __( 'Leave a Comment', 'ltple-theme' ), __( '1 Comment', 'ltple-theme' ), __( '% Comments', 'ltple-theme' ), __( 'Comments off', 'ltple-theme' ) ); ?></span>
	</header><!-- .wowmetaposts -->


	<footer class="entry-meta">
		<div class="tagcloud"><?php echo get_the_tag_list(' ',' ','');?></div>
		<?php edit_post_link( __( 'Edit this post', 'ltple-theme' ), '<span class="clearfix edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
