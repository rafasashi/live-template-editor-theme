<?php
/**
* Template Name: Full Width
 */

get_header(); ?>


	<?php
	while ( have_posts() ) : the_post();

		the_content();

	endwhile; // End of the loop.
	?>

<?php
get_footer();
