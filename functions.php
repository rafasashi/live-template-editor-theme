<?php

/*********************************************************************************************
Styles & Scripts
*********************************************************************************************/
function wow_bootstrap_scripts_styles() {
	
	wp_enqueue_script('ltple-bootstrap-js', get_template_directory_uri() . '/js/bootstrap.js', array('jquery'),'3.3.5', true );
	wp_enqueue_script('ltple-easing-js', get_template_directory_uri() . '/js/jquery.easing.1.3.js', array('jquery'),'', true );
	wp_enqueue_script('ltple-common-js', get_template_directory_uri() . '/js/common.js', array('jquery'),'', true );
	wp_register_script('ltple-isotope-js', get_template_directory_uri() .  '/js/isotope.js', array('jquery'), '', true );
	
	wp_enqueue_style('ltple-bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css', false ,'3.0.3');

	wp_enqueue_style('ltple-fontawesome-css', get_template_directory_uri() . '/css/font-awesome.css', false ,'4.0.3');
	wp_enqueue_style('ltple-animate-css', get_template_directory_uri() . '/css/animate.css', false ,'3.0.0');
	wp_enqueue_style('ltple-style-css', get_stylesheet_uri(), array('ltple-bootstrap-css'), '5.0.6' ); //style.css

}
add_action('wp_enqueue_scripts', 'wow_bootstrap_scripts_styles');

/*********************************************************************************************
SETUP, HEADER & FOOTER MENUS
*********************************************************************************************/
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'wow_setup' ) ) :

function wow_setup() {
	
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-background' );
	add_editor_style( 'custom-editor-style.css' );
	load_theme_textdomain( 'ltple-theme', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	register_nav_menus( array(
		'header' => __( 'Header Menu', 'ltple-theme' ),
		'footer' => __( 'Footer Menu', 'ltple-theme' ),
	) );
}
endif;
add_action( 'after_setup_theme', 'wow_setup' );

/*********************************************************************************************
SUPPORT THUMBNAILS
*********************************************************************************************/
add_theme_support( 'post-thumbnails' );
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( 9999, 9999 ); // default Post Thumbnail dimensions
}
if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'category-thumb', 300, 9999 ); //300 pixels wide (and unlimited height)
	add_image_size( 'blogindex-thumb', 600, 350, true ); //(cropped)
	add_image_size( 'sticky-thumb', 230, 150, true ); //(cropped)
}
/*********************************************************************************************
EXCERPTS
*********************************************************************************************/
function wow_get_custom_excerpt($count){
  global $post;
  $permalink = get_permalink($post->ID);
  $excerpt = get_the_content();
  $excerpt = strip_shortcodes($excerpt);
  $excerpt = strip_tags($excerpt);
  $excerpt = substr($excerpt, 0, $count);
  $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
  return $excerpt;
}
/*********************************************************************************************
PAGINATION
*********************************************************************************************/
function wow_pagination($pages = '', $range = 2)
{
     $showitems = ($range * 2)+1;
     global $paged;
     if(empty($paged)) $paged = 1;
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }
     if(1 != $pages)
     {
         echo "<div class='pagination'>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
             }
         }
         if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
         echo "</div>\n";
     }
}
/*********************************************************************************************
NAVIGATION LINKS
*********************************************************************************************/
if ( ! function_exists( 'wow_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function wow_content_nav( $nav_id ) {
	global $wp_query, $post;
	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
	}
	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;
	$nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';
	?>
	<nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo $nav_class; ?>">
	<?php if ( is_single() ) : // navigation links for single posts ?>
		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'ltple-theme' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div><div class="clearfix"></div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'ltple-theme' ) . '</span>' ); ?>
	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'ltple-theme' ) ); ?></div>
		<?php endif; ?>
		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'ltple-theme' ) ); ?></div>
		<?php endif; ?>
	<?php endif; ?>
	</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
	<?php
}
endif; // wow_content_nav
/*********************************************************************************************
CATEGORIZED BLOG
*********************************************************************************************/
/**
 * Returns true if a blog has more than 1 category
 */
function wow_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );
		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );
		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}
	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so wow_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so wow_categorized_blog should return false
		return false;
	}
}
/*********************************************************************************************
REGISTER WIDGETIZED AREAS
*********************************************************************************************/
function wow_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'ltple-theme' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="blog-widget widget %2$s">',
		'before_title'  => '<h1 class="widget-title"><span class="htitle">',
		'after_title'   => '</span></h1>',
		'after_widget'  => '</aside>',
	) );
	register_sidebar( array(
		'name'          => __( 'Pages Sidebar', 'ltple-theme' ),
		'id'            => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="blog-widget widget %2$s">',
		'before_title'  => '<h1 class="widget-title"><span class="htitle">',
		'after_title'   => '</span></h1>',
		'after_widget'  => '</aside>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widgets', 'ltple-theme' ),
		'id'            => 'footerwidgets',
		'before_widget' => '<div class="col-md-4 footer-widget"><aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside></div>',
		'before_title'  => '<h1>',
		'after_title'   => '</h1><hr>',
	) );
}
add_action( 'widgets_init', 'wow_widgets_init' );

/*********************************************************************************************
COMMENTS
*********************************************************************************************/
if ( ! function_exists( 'wow_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function wow_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'ltple-theme' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'ltple-theme' ), '<span class="edit-link">', '</span>' ); ?>
		</div>
	<?php else : ?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
					<?php printf( __( '%s <span class="says"></span>', 'ltple-theme' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author -->
				<div class="comment-metadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'ltple-theme' ), get_comment_date(), get_comment_time() ); ?>
						</time>
					</a> |
				<div class="reply">
						<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</div><!-- .reply -->
					<?php edit_comment_link( __( 'Edit comment', 'ltple-theme' ), '<span class="clearfix edit-link">', '</span>' ); ?>
				</div><!-- .comment-metadata -->
				<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'ltple-theme' ); ?></p>
				<?php endif; ?>
			</footer><!-- .comment-meta -->
			<div class="clearfix comment-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->
		</article><!-- .comment-body -->
	</li>
	<?php
	endif;
}
endif; // ends check for wow_comment()

/*********************************************************************************************
ENQUEUE COMMENTS
*********************************************************************************************/
function wow_enqueue_comments_reply() {
	if( get_option( 'thread_comments' ) )  {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'comment_form_before', 'wow_enqueue_comments_reply' );

/*********************************************************************************************
CUSTOMIZER
*********************************************************************************************/
function wow_customize_register( $wp_customize ) {

	/*logo*/

	$wp_customize->add_section( 'wow_logo_section' ,
		array(
			'title'       => __( 'Logo', 'ltple-theme' ),
			'priority'    => 30,
			'description' => __( 'Upload a logo for this theme', 'ltple-theme'),
		));

	$wp_customize->add_setting( 'wow_logo' ,
		array (
			'sanitize_callback' => 'esc_url_raw',
		));

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'wow_logo',
		array(
			'label'    => __( 'Current logo', 'ltple-theme' ),
			'section'  => 'wow_logo_section',
			'settings' => 'wow_logo',
		)));

	/*footer*/

		$wp_customize->add_setting('wow_copyright',
      array(
          'sanitize_callback' => 'wow_sanitize_text',
	    ));

	$wp_customize->add_control('wow_copyright',
		array(
			'label'    => __( 'Copyright text', 'ltple-theme' ),
			'section' => 'wow_copyright_section',
			'type' => 'textarea',
		));

}
add_action('customize_register', 'wow_customize_register');

function wow_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}

/*********************************************************************************************
REQUIRES
*********************************************************************************************/
require_once( get_template_directory() . '/inc/nav.php');

/*********************************************************************************************
WOO COMMERCE SUPPORT
*********************************************************************************************/

add_action( 'after_setup_theme', function() {
   
   add_theme_support( 'woocommerce' );
} );