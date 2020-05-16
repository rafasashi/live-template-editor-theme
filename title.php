<?php

// breadcrump

if (!is_front_page()) {

	echo '<div id="title-header" class="header-callout">';
		
		echo '<section class="pt-5 pb-5 bg-light">';
			
			echo '<div class="container">';
			echo '<div class="row">';
				
				echo '<div class="col-12 col-lg-6">';
					
					echo '<header class="page-header">';
					
						echo'<h1 class="page-title">';
							
							if( is_archive() ){
								
								the_archive_title();
							}
							elseif( is_home() ){
								
								echo single_post_title();
							}
							elseif( is_404() ){
								
								echo '404';
							}
							else{
								
								the_title();
							}

						echo'</h1>';
						
					echo'</header>';
					
				echo '</div>';
				
				echo '<div class="col-12 col-lg-6 text-right mt-3 d-none d-lg-block">';
					
					echo '<nav aria-label="breadcrumb">';
					echo '<ol class="breadcrumb float-right">';
					
						// Start the breadcrumb with a link to your homepage
						
						echo '<li class="breadcrumb-item">';
						
							echo '<a href="'. get_option('home') . '">';
								
								echo __('Home');
							
							echo '</a>';
						
						echo'</li>';
					
						// Check if the current page is a category, an archive or a single page. If so show the category or archive name.
						
						echo '<li class="breadcrumb-item">';
								
						if( is_category() ){

							echo single_cat_title( '', false );
						}
						elseif( is_tag() ){
							
							echo single_tag_title( '', false );						
						}
						elseif ( is_author() ) {    
							
							echo get_the_author();    
						} 
						elseif ( is_tax() ) { //for custom post types
							
							echo single_term_title( '', false );
						} 
						elseif( is_archive() || is_date() ){

							if ( is_day() ) {
								
								printf( __( '%s', 'ltple-theme' ), get_the_date() );
							} 
							elseif ( is_month() ) {
								
								printf( __( '%s', 'ltple-theme' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'ltple-theme' ) ) );
							} 
							elseif ( is_year() ) {
								
								printf( __( '%s', 'ltple-theme' ), get_the_date( _x( 'Y', 'yearly archives date format', 'ltple-theme' ) ) );
							} 
							else {
								
								_e('Archives');
							}
						}
						elseif( is_home() ){
							
							echo single_post_title();
						}
						elseif ( is_404() ){

							echo'404';						
						}
						else{
							
							the_title();
						}
						
						echo'</li>';
					
					echo '</ol>';
					echo '</nav>';
				
				echo '</div>';
				
			echo '</div>';
			echo '</div>';
			
		echo '</section>';
		
	echo '</div>';
}