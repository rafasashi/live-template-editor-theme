<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>
<style>#content {margin-left:0;}</style>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="pgheadertitle animated fadeInLeft"><?php the_title(); ?></h1>
		<div class="headerdivider"></div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<main id="main" class="site-main" role="main">
			<?php do_action( 'woocommerce_before_main_content' );?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php wc_get_template_part( 'content', 'single-product' ); ?>
				<?php endwhile; // end of the loop. ?>
			<?php do_action( 'woocommerce_after_main_content' ); ?>
			</main><!-- #main -->
		</div>
	</div>
	
</div>

<?php get_footer( 'shop' ); ?>
