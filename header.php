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
	<div class="boxedcontent" style="z-index:auto;border:none;">
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
		<nav class="navbar" role="navigation" style="background-color:#182f42;z-index:9999;">
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
			<div class="headersearch" style="background-color:transparent;">
				<form style="background-color:transparent;" role="search" method="get" id="search" class="formheadersearch" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					
					<input style="background-color:transparent;" type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Enter search keywords here &hellip;', 'placeholder', 'ltple-theme' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php _ex( 'Search for:', 'label', 'ltple-theme' ); ?>">
					<input style="background-color:transparent;" type="submit" class="search-submit" value="" style="background-color:transparent;">

					<?php
						
						$id = get_current_user_id();
						
						$picture = get_user_meta( $id , 'ltple_profile_picture', true );
						
						if( empty($picture) ){
							
							$picture = get_avatar_url( $id );
						}
						
						if( $id > 0 ){
							
							$profile_url = esc_url( home_url( '/editor/?my-profile' ) );
						}
						else{

							$profile_url = esc_url( home_url( '/login/' ) );
						}
						
						echo'<span style="background-color:#f86d18;width:50px;height:50px;display:inline-block;">';
							
							echo'<a href="'.$profile_url.'" title="Edit my profile"><img style="padding:8px;" class="img-circle" src="'.$picture.'" height="50" width="50" /></a>';
							
						echo'</span>';
					?>
					
				</form>
			</div>
		</nav>
		<div class="menushadow"></div>
		</div>
