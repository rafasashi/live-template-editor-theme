<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head();?>
</head>
<body <?php body_class('boxedlayout'); ?>>
	<div class="boxedcontent" style="z-index:auto;">
		<div class="header">
			  <div class="container">
				<div class="row">
				<?php 
				if ( get_theme_mod( 'wow_logo' ) ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand"><img src="<?php echo esc_url( get_theme_mod( 'wow_logo' ) );?>"></a>
				<?php else: ?>
					<a class="text-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?>">
						<?php echo esc_attr(get_bloginfo('name')); ?>
					</a>
					<div class="navbar-text"><?php echo esc_attr( get_bloginfo( 'description') ); ?></div>
				<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="container">
		<nav class="navbar" role="navigation">
			<?php
				if ( has_nav_menu( 'header' ) ) {
				  wp_nav_menu( array(
					'theme_location'  => 'header',
					'container_class' => 'collapse navbar-collapse',
					'menu_class'      => 'nav navbar-nav',
					'menu_id'         => 'main-menu',
					'fallback_cb' 	  => false,
					'walker'          => new Cwd_wp_bootstrapwp_Walker_Nav_Menu()
				  ) );
				}
			?>
			<div class="headersearch">
			<form role="search" method="get" id="search" class="formheadersearch" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Enter search keywords here &hellip;', 'placeholder', 'ltple-theme' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php _ex( 'Search for:', 'label', 'ltple-theme' ); ?>">
				<input type="submit" class="search-submit" value="">
			</form>
			</div>
		</nav>
		<div class="menushadow"></div>
		</div>
