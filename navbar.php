<div class="header">
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

<nav class="navbar" role="navigation" style="margin:0;border-radius:0;">
	<?php
		if ( has_nav_menu( 'header' ) ) {
		  wp_nav_menu( array(
			'theme_location'  => 'header',
			'container_class' => 'navbar-collapse',
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
				
				<input style="background-color:#fff;margin:0px;box-shadow:inset 0px 0px 1px #182f42;height:30px;border-radius:15px;margin-top:10px;" type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Enter search keywords here &hellip;', 'placeholder', 'ltple-theme' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php _ex( 'Search for:', 'label', 'ltple-theme' ); ?>">
				<input style="background-color:transparent;" type="submit" class="search-submit" value="">

			</form>
		
			<?php

				if( class_exists('LTPLE_Client') ){
					
					$ltple = LTPLE_Client::instance();
					
					echo'<div id="navbar-features" class="pull-left" style="padding:12px 0;">';	
					
						do_action('ltple_menu_buttons');	
					
					echo'</div>';
					
					// avatar
					
					do_action('ltple_avatar_menu');

					$picture = $ltple->image->get_avatar_url( $ltple->user->ID );
					
					$picture = add_query_arg('_',time(),$picture);
					
					echo'<button style="margin-right:5px;float:right;background:transparent;border:none;width:49px;height:50px;display:inline-block;" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img style="padding:10px;" class="img-circle" src="'.$picture.'" height="50" width="50" /></button>';
					
					// account settings
					
					echo'<ul class="dropdown-menu dropdown-menu-right" style="width:250px;margin-top:-3px;">';
						
						if( $ltple->user->ID > 0 ){

							echo'<li style="position:relative;display:table;width:100%;background:#112331;box-shadow: inset 0 0 10px #060c10;">';
								
								echo'<div style="
									float: left;
									width: 30%;
									padding: 12px;
								">';
								
									echo'<img style="border: 2px solid #3b4954;" class="img-circle" src="'.$picture.'">';
								
								echo'</div>';
								
								echo'<div style="
									width: 70%;
									float: left;
									padding: 8px 6px 2px 6px;
								">';
									
									echo'<a href="'. $ltple->urls->profile . $ltple->user->ID . '/" style="
										display: block;
										width: 100%;
									">';
										
										echo'<span class="glyphicon glyphicon-user" aria-hidden="true"></span> ';
										echo'View Profile';
									
									echo'</a>';
									
									echo'<div data-html="true" data-toggle="popover" data-placement="bottom" data-trigger="hover" data-title="Account credits" data-content="They can only be used to purchase services on the platform. They are non-refundable & non-exchangeable." data-original-title="" style="
										color: #eee;
										font-weight: bold;
									">';
									
										echo'Credits: <span id="accountCreditsValue" style="background:#3b4954;border:0;padding:3px 7px;font-size: 12px;border-radius:10px;margin-bottom:3px;" class="badge">...</span>';

									echo'</div>';
									
									do_action('ltple_my_profile_counters');
									
								echo'</div>';
								
							echo'</li>';

							do_action('ltple_view_my_profile_settings');														
							
							do_action('ltple_view_my_profile');
							
							echo'<li style="position:relative;background:#182f42;">';
								
								echo '<a href="'. wp_logout_url( $ltple->urls->editor ) .'"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a>';

							echo'</li>';
						}
						else{
							
							$login_url = home_url('/login/');

							echo'<li style="position:relative;background:#182f42;">';
								
								echo '<a href="'. esc_url( $login_url ) .'"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Login</a>';

							echo'</li>';
							
							echo'<li style="position:relative;background:#182f42;">';
								
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