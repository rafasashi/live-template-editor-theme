<?php
/**
 * The template for displaying Comments.
 */

get_header(); ?>

<?php
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area">

	<div class="col-md-1"></div>
	
	<div class="col-md-10">

		<?php if ( have_comments() ) : ?>
			<div class="comments-title titleborder">
				<div class="uppercasef">
				<i class="fa fa-comments"></i> <?php
					printf( _nx( 'One comment', '%1$s comments', get_comments_number(), 'comments title', 'ltple-theme' ),
						number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
				?>
				</div>
			</div>
			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<?php endif; ?>
				<ol class="comment-list">
					<?php wp_list_comments( array( 'callback' => 'wow_comment' ) );	?>
				</ol><!-- .comment-list -->
			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
				<div class="clearfix"></div>
				<nav id="comment-nav-below" class="comment-navigation" role="navigation">
					<?php paginate_comments_links( array('prev_text' => '&laquo;', 'next_text' => '&raquo;')); ?>
				</nav>
			<?php endif;?>
		<?php endif;?>

		<?php
			if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
			<p class="no-comments"><?php _e( 'Comments are closed.', 'ltple-theme' ); ?></p>
		<?php endif; ?>

		<?php
			$commenter = wp_get_current_commenter();
			$req = get_option( 'require_name_email' );
			$aria_req = ( $req ? " aria-required='true'" : '' );

			$fields =  array(
				// redefine author field
			'author' => '<div class="row"><div class="col-lg-4 comment-form-author">' . '<label for="author">' . __( 'Name', 'ltple-theme' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label>' .
			'<div class="input-prepend"><span class="add-on"><i class="fa fa-user"></i></span><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . 'aria-required="true"' . ' required /></div></div>',
			'email'  => '<div class="col-lg-4 comment-form-email"><label for="email">' . __( 'E-mail Address', 'ltple-theme' ) . ( $req ? ' <span class="required">*</span><br/>' : '' ) . '</label>' .
			'<div class="input-prepend"><span class="add-on"><i class="fa fa-envelope"></i></span><input required id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . 'aria-required="true"' . ' required /></div></div>',
			'url'  => '<div class="col-lg-4 comment-form-url"><label for="url">' . __( 'Website', 'ltple-theme' ) . '</label>' .
			'<div class="input-prepend"><span class="add-on"><i class="fa fa-globe"></i></span><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div></div></div>'
			);
		$comments_args = array(
			'fields' => $fields,
			'title_reply' => __( 'Add Comment', 'ltple-theme' ),
			'label_submit' => __( 'Post a Question', 'ltple-theme' ),
			'title_reply_to' => __( 'Leave a Reply to %s', 'ltple-theme' ),
			'cancel_reply_link' => __( 'Cancel Reply', 'ltple-theme' ),
			'comment_field' => '<div class="comment-form-comment"><label for="comment">' . __( 'Comment', 'ltple-theme' ) . '</label><textarea id="comment" name="comment" rows="5" aria-required="true"></textarea></div>',
			'comment_notes_before' => '<p class="comment-notes">' . __( 'Required fields are marked *. Your email address will not be published.', 'ltple-theme') .'</p>',
			'must_log_in' => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'ltple-theme' ), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p>',
			'logged_in_as' => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'ltple-theme' ),admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
			);
		comment_form( $comments_args );
		?>
	
	</div>
	<div class="col-md-1"></div>
	
</div><!-- #comments -->
