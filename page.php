<?php

get_header(); 

?>

	<main id="main" class="site-main" role="main">
		
		<?php 
			
			while ( have_posts() ) : the_post();
				
				get_template_part( 'content', 'page' ); 

				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template();
			
			endwhile;
		?>

	</main><!-- #main -->			

<?php 

get_footer(); 

?>