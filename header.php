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
			<div class="headersearch" style="background-color:transparent;">
				
				<div class="formheadersearch" style="background-color:transparent;">
					
					<form id="search" style="float:left;" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						
						<input style="background-color:transparent;" type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Enter search keywords here &hellip;', 'placeholder', 'ltple-theme' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php _ex( 'Search for:', 'label', 'ltple-theme' ); ?>">
						<input style="background-color:transparent;" type="submit" class="search-submit" value="" style="background-color:transparent;">

					</form>
				
					<?php

						if( class_exists('LTPLE_Client') ){
							
							$ltple = LTPLE_Client::instance();
							
							$id = get_current_user_id();
							
							$picture = get_user_meta( $id , 'ltple_profile_picture', true );
							
							if( empty($picture) ){
								
								$picture = get_avatar_url( $id );
							}
							
							$picture = add_query_arg(time(),'',$picture);

							echo'<button style="margin-right:5px;float:right;background:transparent;border:none;width:49px;height:50px;display:inline-block;" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img style="padding:10px;" class="img-circle" src="'.$picture.'" height="50" width="50" /></button>';
						
							echo'<ul class="dropdown-menu dropdown-menu-right" style="width:250px;">';
								
								if( $id > 0 ){
									
									echo'<li style="position:relative;">';
										
										echo '<a target="_blank" href="'. $ltple->urls->editor .'?pr='.$ltple->user->ID . '"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> View Profile</a>';

									echo'</li>';					
									
									echo'<li style="position:relative;">';
										
										echo '<a href="'. $ltple->urls->editor .'?my-profile"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Edit Settings</a>';

									echo'</li>';
									
									echo'<li style="position:relative;">';
										
										echo '<a href="'. $ltple->urls->editor .'?my-profile=billing-info"><span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span> Billing Info</a>';

									echo'</li>';
									
									if( $ltple->settings->options->enable_subdomains == 'on' ){
									
										echo'<li style="position:relative;">';
											
											echo '<a href="'. $ltple->urls->editor . '?domain"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> Domains & URLs</a>';

										echo'</li>';
									}
									
									echo'<li style="position:relative;">';
										
										echo '<a href="'. $ltple->urls->editor .'?app"><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span> Connected Apps</a>';

									echo'</li>';
									
									do_action('ltple_view_my_profile');
									
									echo'<li style="position:relative;">';
										
										echo '<a href="'. wp_logout_url( $ltple->urls->editor ) .'"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a>';

									echo'</li>';
								}
								else{
									
									$login_url = home_url('/login/');

									echo'<li style="position:relative;">';
										
										echo '<a href="'. esc_url( $login_url ) .'"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Login</a>';

									echo'</li>';
									
									echo'<li style="position:relative;">';
										
										echo '<a href="'. esc_url( add_query_arg('action','register',$login_url) ) .'"><span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span> Register</a>';

									echo'</li>';
								}
								
							echo'</ul>';
						}
						else{

							$redirect = get_dashboard_url();
						
							$profile_url = wp_login_url( $redirect );
							 
							$picture = get_avatar_url( $id );
							
							echo'<span style="width:49px;height:50px;display:inline-block;">';
								
								echo'<a href="'.$profile_url.'" title="Edit my profile"><img style="padding:8px;" class="img-circle" src="'.$picture.'" height="50" width="50" /></a>';
								
							echo'</span>';
						}
					?>
					
				</div>

			</div>
		</nav>
		<div class="menushadow"></div>
		</div>
