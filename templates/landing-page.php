<?php
/*
Template Name: Landing Page
*/

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo apply_filters('ltple_document_classes','ltple-theme'); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head();?>
</head> 
<body <?php body_class('landing-page'); ?>>
	
	<?php while ( have_posts() ) : the_post(); ?>
		
		<?php get_template_part( 'content', 'page' ); ?>
	
	<?php endwhile;?>	

</body>

<?php wp_footer(); ?>
</body>
</html>
