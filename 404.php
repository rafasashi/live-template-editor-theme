<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package LTPLE_Theme
 */

get_header(); ?>

	<section id="primary" class="content-area col-sm-12 col-lg-9">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h2 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'ltple-theme' ); ?></h2>
				</header><!-- .page-header -->

				<div class="page-content">
					
					<div class="row">
					
						<div class="col-12">
						
							<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'ltple-theme' ); ?></p>
						
						</div>
						
						<div class="col-12 col-md-6">

							<?php get_search_form(); ?>
						
						</div>
						
					</div>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_sidebar();
get_footer();
