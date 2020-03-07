<?php
/**
 * LTPLE Theme Class
 *
 */

class LTPLE_Theme {
	
	private static $_instance = null;
	
	var $_file = null;
	
	var $form 	= null;
	
	var $defaults 		= array();
	var $panels 		= array();
	var $sections 		= array();
	var $customizers 	= array();
	
	var $demo_samples	= false; 
	var $slider_images 	= 10;
	var $featured_items = 3;
	var $gallery_images = array(

		array(450,600),
		array(600,450),
		array(450,600),
		array(600,450),
		array(450,600),
		array(600,450),
	);

	public function __construct( $file = '', $args = array() ) {
		
		$this->_file = $file;
		
		//Customizer
		
		add_action('customize_register', array($this,'customize_register'));

		//Styles & Scripts
	
		add_action( 'wp_enqueue_scripts', array($this,'enqueue_fontawesome_style') );
		
		add_action( 'customize_controls_print_styles',  array($this,'enqueue_fontawesome_style') );

		add_action( 'customize_preview_init', 	function(){
			
			wp_enqueue_script( 'ltple-customizer-preview', trailingslashit( get_template_directory_uri() ) . 'js/customizer-preview.js', array( 'customize-preview', 'jquery' ) );
			
		});
		
		//SETUP, HEADER & FOOTER MENUS
		
		add_action( 'after_setup_theme', function() {
			
			add_theme_support( 'post-thumbnails' ); 
			
			add_theme_support( 'html5', array( 'comment-list' ) );
			
			add_theme_support( 'customize-selective-refresh-widgets' );
			
			//add_theme_support( 'title-tag' );
			
			//add_theme_support( 'custom-background' );
			
			//add_theme_support( 'automatic-feed-links' );
			
			//add_editor_style( 'custom-editor-style.css' );
			
			load_theme_textdomain( 'ltple-theme', get_template_directory() . '/languages' );
			
			register_nav_menus( array(
			
				'header' => __( 'Header', 'ltple-theme' ),
				'footer' => __( 'Footer', 'ltple-theme' ),
			));
			
			require_once( get_template_directory() . '/inc/nav.php');
		
			require_once( get_template_directory() . '/inc/comments.php');

		});
		
		add_filter( 'init', array($this,'init_frontend') );		
	
		add_filter( 'wp_nav_menu_items', array($this,'get_searchbar_menu'), 8888, 2 );
	}
	
	public function init_frontend(){
		
		// get form
		
		$this->get_form();
		
		// add sibars & widgets
		
		register_sidebar( array(

			'name' 				=> __( 'Post Sidebar', 'ltple-theme' ),
			'id' 				=> 'post-sidebar',
			'description' 		=> __( 'The right sidebar of the posts', 'ltple-theme' ),
			'before_widget' 	=> '<div class="widget">',
			'after_widget' 		=> '</div>',
			'before_title' 		=> '<div class="widget-title"><h2>',
			'after_title' 		=> '</h2></div>',
		));
		
		register_sidebar( array(

			'name' 				=> __( 'Footer Column Left', 'ltple-theme' ),
			'id' 				=> 'footer-widget-left',
			'description' 		=> __( 'The left column of the footer', 'ltple-theme' ),
			'before_widget' 	=> '',
			'after_widget' 		=> '',
			'before_title' 		=> '<h3 class="widget-title-left">',
			'after_title' 		=> '</h3>',
		));
		
		register_sidebar( array(

			'name' 				=> __( 'Footer Column Center', 'ltple-theme' ),
			'id' 				=> 'footer-widget-center',
			'description' 		=> __( 'The center column of the footer', 'ltple-theme' ),
			'before_widget' 	=> '',
			'after_widget' 		=> '',
			'before_title' 		=> '<h3 class="widget-title-center">',
			'after_title' 		=> '</h3>',
		));
		
		register_sidebar( array(

			'name' 				=> __( 'Footer Column Right', 'ltple-theme' ),
			'id' 				=> 'footer-widget-right',
			'description' 		=> __( 'The right column of the footer', 'ltple-theme' ),
			'before_widget' 	=> '',
			'after_widget' 		=> '',
			'before_title' 		=> '<h3 class="widget-title-right">',
			'after_title' 		=> '</h3>',
		));

		// generate header menu
		
		$name = 'Header Menu';

		if( !wp_get_nav_menu_object($name) ){
		
			$menu_id = wp_create_nav_menu($name);
			
			$menu = get_term_by( 'name', $name, 'nav_menu' );

			wp_update_nav_menu_item($menu->term_id, 0, array(
				
				'menu-item-title' 	=>  __('Page'),
				'menu-item-url' 	=> '#',
				'menu-item-type' 	=> 'custom',
				'menu-item-status' 	=> 'publish'			
			));

			$parent_item = wp_update_nav_menu_item($menu->term_id, 0, array(
				
				'menu-item-title' 	=>  __('Menu'),
				'menu-item-url' 	=> '#',
				'menu-item-type' 	=> 'custom',
				'menu-item-status' 	=> 'publish'			
			));
			
			wp_update_nav_menu_item($menu->term_id, 0, array(
				
				'menu-item-title' 		=> __('Sub Menu'),
				'menu-item-parent-id' 	=> $parent_item,
				'menu-item-url' 		=> '#',
				'menu-item-status' 		=> 'publish',
			));	

			$locations = self::get_mod('nav_menu_locations');
			
			$locations['header'] = $menu->term_id;
			
			set_theme_mod( 'nav_menu_locations', $locations );
		}
		
		// generate footer menu
		
		$name = 'Footer Menu';

		if( !wp_get_nav_menu_object($name) ){
		
			$menu_id = wp_create_nav_menu($name);
			
			$menu = get_term_by( 'name', $name, 'nav_menu' );

			wp_update_nav_menu_item($menu->term_id, 0, array(
				
				'menu-item-title' 	=>  __('TOS'),
				'menu-item-url' 	=> '#',
				'menu-item-type' 	=> 'custom',
				'menu-item-status' 	=> 'publish'			
			));

			wp_update_nav_menu_item($menu->term_id, 0, array(
				
				'menu-item-title' 	=>  __('Privacy'),
				'menu-item-url' 	=> '#',
				'menu-item-type' 	=> 'custom',
				'menu-item-status' 	=> 'publish'			
			));
			
			wp_update_nav_menu_item($menu->term_id, 0, array(
				
				'menu-item-title' 		=> __('Contact'),
				'menu-item-url' 		=> '#',
				'menu-item-status' 		=> 'publish',
			));	

			$locations = self::get_mod('nav_menu_locations');
			
			$locations['footer'] = $menu->term_id;
			
			set_theme_mod( 'nav_menu_locations', $locations );
		}
		
		add_filter('nav_menu_css_class', function ($classes, $item, $args) {
			
			if( isset($args->add_li_class) && $args->add_li_class) {
				
				$classes[] = $args->add_li_class;
			}
			
			return $classes;
			
		}, 1, 3);

		$this->set_default_customizers();
		
		add_filter('customize_control_active',function($active,$control){

			if( in_array($control->section,array(
			
				'static_front_page',
				
			))){
				
				$active = false;
			}
			elseif( in_array($control->id,array(
			
				'show_on_front',
				
			))){
				
				$active = false;
			}
			
			return $active;
			
		},10,2);
	}
	
	public function get_searchbar_menu( $items, $args ){

		if( $args->menu->slug == 'header-menu' ) {

			if( $this->get_mod( 'search_menu_icon') ){
				
				$items .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-searchbar" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children dropdown menu-item-158 nav-item">';
				
					$items .= '<a title="Search" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="menu-searchbar-dropdown" class="nav-link"><span class="fa fa-search"></span></a>';
					
					$items .= '<ul class="dropdown-menu dropdown-menu-studio animated flipInX" aria-labelledby="menu-searchbar-dropdown" role="menu">';
						
						$items .= '<li id="menu-searchbar-content" itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom nav-item">';
							
							$items .= get_search_form(array(
							
								'echo' => false
							));
						
						$items .= '</li>';
					
					$items .= '</ul>';
				
				$items .= '</li>';
			}			
		}
		
		return $items;
	}
	
	public function get_form(){
		
		require_once( get_template_directory() . '/inc/form.php');
							
		$this->form = new LTPLE_Theme_Contact_Form($this);
	}
	
	public function set_default_customizers(){
		
		if( $this->demo_samples === true ){
		
			include_once trailingslashit( dirname($this->_file) ) . '/sample-controls.php';
		}
		
		//set panels

		$this->set_panel('header_naviation_panel', array(

			'title' 		=> 'Header & Footer',
			'description' 	=> 'Adjust your Header, Footer and Navigation sections.',
			'priority'		=> 100,
		));

		//set sections

		$this->set_section('social_icons_section', array(

			'title' 		=> 'Social Icons',
			'description' 	=> 'Add your social media links and we\'ll automatically match them with the appropriate icons. Drag and drop the URLs to rearrange their order.',
			'panel' 		=> 'header_naviation_panel',
		));		
		
		$this->set_section('contact_section', array(

			'title' 		=> 'Contact',
			'description' 	=> 'Add your phone number to the site header bar.',
			'panel' 		=> 'header_naviation_panel',
		));

		$this->set_section('search_section', array(

			'title' 		=> 'Search',
			'description' 	=> 'Add a search icon to your primary naigation menu.',
			'panel' 		=> 'header_naviation_panel',
		));		

		//set customizers
		
		$this->set_customizer('social_newtab', array(

			'setting' 	=> array(
			
				'transport'		 	=> 'postMessage',
				'sanitize_callback' => 'ltple_theme_switch_sanitization'
			),
			'class'		=> 'LTPLE_Theme_Toggle_Switch_Custom_control',
			'control' 	=> array(
			
				'label' 	=> 'Open in new browser tab',
				'section' 	=> 'social_icons_section'
			),
			'partial' 	=> array(
			
				'selector' 				=> '.social',
				'container_inclusive' 	=> false,
				'render_callback' 		=> function(){
					
					echo LTPLE_Theme::get_social_media();
				},			
			),
		));

		$this->set_customizer('social_alignment', array(

			'setting' 	=> array(
				'default' 			=> 'alignleft',
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'ltple_theme_radio_sanitization'
			),
			'class'		=> 'LTPLE_Theme_Text_Radio_Button_Custom_Control',
			'control' 	=> array(
			
				'label' 		=> 'Alignment',
				'description' 	=> 'Choose the alignment for your social icons',
				'section' 		=> 'social_icons_section',
				'choices' 		=> array(
				
					'alignleft' 	=> 'Left',
					'alignright' 	=> 'Right',
				)
			),
			'partial' 	=> array(
			
				'selector' 				=> '.social',
				'container_inclusive' 	=> false,
				'render_callback' 		=> function() {
					
					echo LTPLE_Theme::get_social_media();
				}
			),
		));
		
		$this->set_customizer('social_urls', array(

			'setting' 	=> array(
			
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'ltple_theme_url_sanitization'
			),
			'class'		=> 'LTPLE_Theme_Sortable_Repeater_Url_Custom_Control',
			'control' 	=> array(
			
				'label' 		=> 'Social URLs',
				'description' 	=> 'Add your social media links.',
				'section' 		=> 'social_icons_section',
				'button_labels' => array(
					
					'add' => 'Add Icon',
				)
			),
			'partial' 	=> array(
			
				'selector' 				=> '.social',
				'container_inclusive' 	=> false,
				'render_callback' 		=> function() {
					
					echo LTPLE_Theme::get_social_media();
				},
			),
		));
		
		$this->set_customizer('social_url_icons', array(

			'setting' 	=> array(
			
				'transport' 		=> 'refresh',
				'sanitize_callback' => 'ltple_theme_text_sanitization'
			),
			'class'		=> 'LTPLE_Theme_Single_Accordion_Custom_Control',
			'control' 	=> array(
			
			'label' => __( 'View list of available icons', 'ltple-theme' ),
				'description' => array(
					'Behance' => __( '<i class="fab fa-behance"></i>', 'ltple-theme' ),
					'Bitbucket' => __( '<i class="fab fa-bitbucket"></i>', 'ltple-theme' ),
					'CodePen' => __( '<i class="fab fa-codepen"></i>', 'ltple-theme' ),
					'DeviantArt' => __( '<i class="fab fa-deviantart"></i>', 'ltple-theme' ),
					'Discord' => __( '<i class="fab fa-discord"></i>', 'ltple-theme' ),
					'Dribbble' => __( '<i class="fab fa-dribbble"></i>', 'ltple-theme' ),
					'Etsy' => __( '<i class="fab fa-etsy"></i>', 'ltple-theme' ),
					'Facebook' => __( '<i class="fab fa-facebook-f"></i>', 'ltple-theme' ),
					'Flickr' => __( '<i class="fab fa-flickr"></i>', 'ltple-theme' ),
					'Foursquare' => __( '<i class="fab fa-foursquare"></i>', 'ltple-theme' ),
					'GitHub' => __( '<i class="fab fa-github"></i>', 'ltple-theme' ),
					'Google+' => __( '<i class="fab fa-google-plus-g"></i>', 'ltple-theme' ),
					'Instagram' => __( '<i class="fab fa-instagram"></i>', 'ltple-theme' ),
					'Kickstarter' => __( '<i class="fab fa-kickstarter-k"></i>', 'ltple-theme' ),
					'Last.fm' => __( '<i class="fab fa-lastfm"></i>', 'ltple-theme' ),
					'LinkedIn' => __( '<i class="fab fa-linkedin-in"></i>', 'ltple-theme' ),
					'Medium' => __( '<i class="fab fa-medium-m"></i>', 'ltple-theme' ),
					'Patreon' => __( '<i class="fab fa-patreon"></i>', 'ltple-theme' ),
					'Pinterest' => __( '<i class="fab fa-pinterest-p"></i>', 'ltple-theme' ),
					'Reddit' => __( '<i class="fab fa-reddit-alien"></i>', 'ltple-theme' ),
					'Slack' => __( '<i class="fab fa-slack-hash"></i>', 'ltple-theme' ),
					'SlideShare' => __( '<i class="fab fa-slideshare"></i>', 'ltple-theme' ),
					'Snapchat' => __( '<i class="fab fa-snapchat-ghost"></i>', 'ltple-theme' ),
					'SoundCloud' => __( '<i class="fab fa-soundcloud"></i>', 'ltple-theme' ),
					'Spotify' => __( '<i class="fab fa-spotify"></i>', 'ltple-theme' ),
					'Stack Overflow' => __( '<i class="fab fa-stack-overflow"></i>', 'ltple-theme' ),
					'Tumblr' => __( '<i class="fab fa-tumblr"></i>', 'ltple-theme' ),
					'Twitch' => __( '<i class="fab fa-twitch"></i>', 'ltple-theme' ),
					'Twitter' => __( '<i class="fab fa-twitter"></i>', 'ltple-theme' ),
					'Vimeo' => __( '<i class="fab fa-vimeo-v"></i>', 'ltple-theme' ),
					'Weibo' => __( '<i class="fab fa-weibo"></i>', 'ltple-theme' ),
					'YouTube' => __( '<i class="fab fa-youtube"></i>', 'ltple-theme' ),
				),
				'section' => 'social_icons_section'
			),
		));
		
		$this->set_customizer('social_rss', array(

			'setting' 	=> array(
			
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'ltple_theme_switch_sanitization'
			),
			'class' 	=> 'LTPLE_Theme_Toggle_Switch_Custom_control',
			'control' 	=> array(
			
				'label' 	=> 'Display RSS icon',
				'section' 	=> 'social_icons_section',
			),
			'partial' 	=> array(
			
				'selector' 				=> '.social',
				'container_inclusive' 	=> false,
				'render_callback' 		=> function() {
					
					echo LTPLE_Theme::get_social_media();
				}
			),
		));
	
		$this->set_customizer('contact_phone', array(

			'setting' 	=> array(
				'default' 			=> '+1 234 567',
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses'
			),
			'control' 	=> array(
				
				'label' 	=> 'Phone number',
				'type' 		=> 'text',
				'section' 	=> 'contact_section'
			),
			'partial' 	=> array(
			
				'selector' 				=> '.phone',
				'container_inclusive' 	=> true,
				'render_callback' 		=> function(){
		
					if( $contact_phone 	= LTPLE_Theme::get_mod('contact_phone') ) {
						
						echo '<span class="phone">';
						
							echo '<a href="tel:'.$contact_phone.'">';
							
								echo '<svg class="svg-icon-phone" viewBox="0 0 20 20">';
									
									echo '<path d="M13.372,1.781H6.628c-0.696,0-1.265,0.569-1.265,1.265v13.91c0,0.695,0.569,1.265,1.265,1.265h6.744c0.695,0,1.265-0.569,1.265-1.265V3.045C14.637,2.35,14.067,1.781,13.372,1.781 M13.794,16.955c0,0.228-0.194,0.421-0.422,0.421H6.628c-0.228,0-0.421-0.193-0.421-0.421v-0.843h7.587V16.955z M13.794,15.269H6.207V4.731h7.587V15.269z M13.794,3.888H6.207V3.045c0-0.228,0.194-0.421,0.421-0.421h6.744c0.228,0,0.422,0.194,0.422,0.421V3.888z"></path>';
								
								echo '</svg> ';
								
								echo $contact_phone;
							
							echo '</a>';
							
						echo '</span>';
					}	
				}
			),
		));
		
		$this->set_customizer('contact_email', array(

			'setting' 	=> array(
				'default' 			=> 'manager@website.com',
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'sanitize_email'
			),
			'control' 	=> array(
				
				'label' 	=> 'Email address',
				'type' 		=> 'email',
				'section' 	=> 'contact_section',
			),
			'partial' 	=> array(
			
				'selector' 				=> '.email',
				'container_inclusive' 	=> true,
				'render_callback' 		=> function(){
		
					if( $contact_email 	= LTPLE_Theme::get_mod('contact_email') ) {
						
						?>
						
						<span class="email">
							
							<a href="mailto:<?php echo $contact_email; ?>">
							
								<svg class="svg-icon-email" viewBox="0 0 20 20">
									<path d="M17.388,4.751H2.613c-0.213,0-0.389,0.175-0.389,0.389v9.72c0,0.216,0.175,0.389,0.389,0.389h14.775c0.214,0,0.389-0.173,0.389-0.389v-9.72C17.776,4.926,17.602,4.751,17.388,4.751 M16.448,5.53L10,11.984L3.552,5.53H16.448zM3.002,6.081l3.921,3.925l-3.921,3.925V6.081z M3.56,14.471l3.914-3.916l2.253,2.253c0.153,0.153,0.395,0.153,0.548,0l2.253-2.253l3.913,3.916H3.56z M16.999,13.931l-3.921-3.925l3.921-3.925V13.931z"></path>
								</svg>
								
								<?php echo $contact_email; ?>
							
							</a>
							
						</span>
						
						<?php
					}
				}
			),
		));
		
		$this->set_customizer('contact_schedule', array(

			'setting' 	=> array(
				'default' 			=> 'weekdays 9AM - 10PM',
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses'
			),
			'control' 	=> array(
				
				'label' 	=> 'Schedule',
				'type' 		=> 'text',
				'section' 	=> 'contact_section'
			),
			'partial' 	=> array(
			
				'selector' 				=> '.schedule',
				'container_inclusive' 	=> true,
				'render_callback' 		=> function(){
		
					if( $contact_schedule = LTPLE_Theme::get_mod('contact_schedule') ) {
						
						?>
						
						<span class="schedule">
						
							<svg class="svg-icon-schedule" viewBox="0 0 20 20">
								<path d="M15.396,2.292H4.604c-0.212,0-0.385,0.174-0.385,0.386v14.646c0,0.212,0.173,0.385,0.385,0.385h10.792c0.211,0,0.385-0.173,0.385-0.385V2.677C15.781,2.465,15.607,2.292,15.396,2.292 M15.01,16.938H4.99v-2.698h1.609c0.156,0.449,0.586,0.771,1.089,0.771c0.638,0,1.156-0.519,1.156-1.156s-0.519-1.156-1.156-1.156c-0.503,0-0.933,0.321-1.089,0.771H4.99v-3.083h1.609c0.156,0.449,0.586,0.771,1.089,0.771c0.638,0,1.156-0.518,1.156-1.156c0-0.638-0.519-1.156-1.156-1.156c-0.503,0-0.933,0.322-1.089,0.771H4.99V6.531h1.609C6.755,6.98,7.185,7.302,7.688,7.302c0.638,0,1.156-0.519,1.156-1.156c0-0.638-0.519-1.156-1.156-1.156c-0.503,0-0.933,0.322-1.089,0.771H4.99V3.062h10.02V16.938z M7.302,13.854c0-0.212,0.173-0.386,0.385-0.386s0.385,0.174,0.385,0.386s-0.173,0.385-0.385,0.385S7.302,14.066,7.302,13.854 M7.302,10c0-0.212,0.173-0.385,0.385-0.385S8.073,9.788,8.073,10s-0.173,0.385-0.385,0.385S7.302,10.212,7.302,10 M7.302,6.146c0-0.212,0.173-0.386,0.385-0.386s0.385,0.174,0.385,0.386S7.899,6.531,7.688,6.531S7.302,6.358,7.302,6.146"></path>
							</svg>
							
							<?php echo $contact_schedule; ?>
							
						</span>
						
						<?php
					}
				}
			),
		));
		
		$this->set_customizer('search_menu_icon', array(

			'setting' 	=> array(
				'default' 			=> 1,
				'transport' 		=> 'postMessage',
				'sanitize_callback' => 'ltple_theme_switch_sanitization'
			),
			'class'		=> 'LTPLE_Theme_Toggle_Switch_Custom_control',
			'control' 	=> array(
				
				'label' 	=> 'Display Search Icon',
				'section' 	=> 'search_section'
			),
			'partial' 	=> array(
			
				'selector' => '#menu-searchbar',
			),
		));

		// set defaults values
		
		$defaults = array();
		
		if( !empty($this->customizers) ){
		
			foreach( $this->customizers as $name => $customizer ){
				
				if( isset($customizer['setting']['default']) ){
					
					$defaults[$name] = $customizer['setting']['default'];
				}
			}
		}
		
		$this->defaults = apply_filters( 'ltple_theme_customizer_defaults', $defaults );
	}
	
	public function set_panel($option_name,$settings){
		
		if(!isset($this->panels[$option_name])){
			
			$this->panels[$option_name] = $settings;
		}
	}
	
	public function set_section($option_name,$settings){
		
		if(!isset($this->sections[$option_name])){
			
			$this->sections[$option_name] = $settings;
		}
	}
	
	public function set_customizer($option_name,$settings){
		
		if(!isset($this->customizers[$option_name])){
			
			$this->customizers[$option_name] = $settings;
		}
	}
	
	public function customize_register($wp_customize){
		
		// add panels

		if( !empty($this->panels) ){
			
			foreach( $this->panels as $panel_name => $panel ){
				
				$wp_customize->add_panel( $panel_name, $panel );				
			}
		}
			
		// add sections
		
		if( !empty($this->sections) ){
			
			foreach( $this->sections as $section_name => $section ){
				
				if( !empty($section['class']) ){
					
					$wp_customize->add_section( new $section['class']( $wp_customize, $section_name, $section ));
				}
				else{
				
					$wp_customize->add_section( $section_name, $section );				
				}
			}
		}		

		// add default customizer

		if( !empty($this->customizers) ){
			
			// add customizer
					
			foreach( $this->customizers as $customizer_name => $customizer ){
				
				// add setting
				
				if( empty($customizer['setting']['default']) && !empty($this->defaults[$customizer_name]) ){
					
					$customizer['setting']['default'] = $this->defaults[$customizer_name];
				}
				
				if( empty($customizer['setting']['sanitize_callback']) ){
					
					$sanitize_callback = 'ltple_theme_text_sanitization';
					
					if( isset($customizer['class']) ){
						
						if( $customizer['class'] == 'WP_Customize_Image_Control' ){
							
							$sanitize_callback = 'esc_url_raw';
						}
						elseif( $customizer['class'] == 'WP_Customize_Cropped_Image_Control' ){
							
							$sanitize_callback = 'absint';
						}
						elseif( $customizer['class'] == 'LTPLE_Theme_Sortable_Repeater_Text_Custom_Control' ){
							
							$sanitize_callback = 'ltple_theme_text_sanitization';
						}
						elseif( $customizer['class'] == 'LTPLE_Theme_Sortable_Repeater_Url_Custom_Control' ){
							
							$sanitize_callback = 'ltple_theme_url_sanitization';
						}
						elseif( $customizer['class'] == 'WP_Customize_Background_Image_Control' ){
							
							$sanitize_callback = '';
						}
						elseif( $customizer['class'] == 'WP_Customize_Background_Image_Setting' ){
							
							$sanitize_callback = '';
						}						
						else{
							
							var_dump('sanitize controler:  ' .$customizer['class']);exit;
						}
					}
					elseif( isset($customizer['control']['type']) ){

						if( $customizer['control']['type'] == 'textarea' ){
							
							$sanitize_callback = 'wp_filter_nohtml_kses';
						}
						elseif( $customizer['control']['type'] == 'text' ){
							
							$sanitize_callback = 'ltple_theme_text_sanitization';
						}
						elseif( $customizer['control']['type'] == 'url' ){
							
							$sanitize_callback = 'esc_url_raw';
						}
						else{
							
							var_dump('sanitize controler:  ' .$customizer['control']['type']);exit;
						}
					}
					
					$customizer['setting']['sanitize_callback'] = $sanitize_callback;
				}
				
				if( empty($customizer['setting']['transport']) && !empty($customizer['partial']) ){
					
					$customizer['setting']['transport'] = 'postMessage';
				}
				
				$wp_customize->add_setting($customizer_name,$customizer['setting']);
				
				// add control
				
				if( empty($customizer['control']['settings']) ){
					
					$customizer['control']['settings'] = $customizer_name;
				}
				
				if( !empty($customizer['control']['label']) ){
					
					$customizer['control']['label'] = __($customizer['control']['label'],'ltple-theme');
				}
				
				if( !empty($customizer['control']['description']) ){
					
					if( is_string($customizer['control']['description']) ){
						
						$customizer['control']['description'] = esc_html__($customizer['control']['description']);
					}
					elseif( is_array($customizer['control']['description']) ){
						
						foreach( $customizer['control']['description'] as $i => $description ){
							
							$customizer['control']['description'][$i] = __($description);
						}
					}
				}
				
				if( !isset($customizer['control']['input_attrs']['placeholder']) ){
					
					if( !empty($customizer['control']['type']) ){
						
						if( $customizer['control']['type'] == 'url' ){
							
							$customizer['control']['input_attrs']['placeholder'] = 'https://';
						}
					}
				}
				
				if( empty($customizer['class']) ){
					
					$customizer['class'] = 'WP_Customize_Control';
				}
				
				$wp_customize->add_control( new $customizer['class']( $wp_customize, $customizer_name, $customizer['control']));						
			
				if( !empty($customizer['partial']) ){
					
					if( !empty($customizer['partial']['render_callback']) ){
						
						if( empty($customizer['partial']['selector']) ){
							
							$customizer['partial']['selector'] = '.' . $customizer_name;
						}
						
						if( !isset($customizer['partial']['container_inclusive']) ){
							
							$customizer['partial']['container_inclusive'] = true;
						}
					}
					
					$wp_customize->selective_refresh->add_partial( $customizer_name,$customizer['partial']);					
					
					add_filter( 'customize_partial_render', array($this,'customize_partial_render'),10,3 );
				}
			}
		}
	}
	
	public function enqueue_fontawesome_style() {

		wp_register_style( 'fontawesome', trailingslashit( get_template_directory_uri() ) . 'css/font-awesome.css' , array(), '4.7.0', 'all' );
		wp_enqueue_style( 'fontawesome' );
	}
	
	public function customize_partial_render($rendered, $partial, $container_context){
		
		if ( !empty( $partial->render_callback ) && is_callable( $partial->render_callback ) ) {
			
			ob_start();
			
			$return_render 	= call_user_func($partial->render_callback,$partial->id);
			
			$ob_render     	= ob_get_clean();

			$rendered 		= null !== $return_render ? $return_render : $ob_render;
		}

		return $rendered;
	}
	
	public static function render($option_name){

		if( isset(self::$_instance->customizers[$option_name]['partial']['render_callback']) ){
			
			$args = ( ( isset(self::$_instance->customizers[$option_name]['partial']['callback_args']) && is_numeric(self::$_instance->customizers[$option_name]['partial']['callback_args']) ) ? self::$_instance->customizers[$option_name]['partial']['callback_args'] : $option_name );

			return self::$_instance->customizers[$option_name]['partial']['render_callback']($args);
		}
	}
	
	public static function get_mod($option_name){

		$default = false;
		
		if( isset(self::$_instance->defaults[$option_name]) ){
			
			$default = self::$_instance->defaults[$option_name];
		}
		
		return get_theme_mod($option_name,$default);
	}

	public function get_social_media() {
		
		$output 		= array();

		$social_urls 		= explode( ',', LTPLE_Theme::get_mod('social_urls') );
		$social_newtab 		= LTPLE_Theme::get_mod('social_newtab');

		if( !empty($social_urls) ){
			
			$social_icons 	= apply_filters( 'ltple_theme_social_icons', array(
			
				array( 'url' => 'behance.net', 'icon' => 'fab fa-behance', 'title' => esc_html__( 'Follow me on Behance', 'ltple-theme' ), 'class' => 'behance' ),
				array( 'url' => 'bitbucket.org', 'icon' => 'fab fa-bitbucket', 'title' => esc_html__( 'Fork me on Bitbucket', 'ltple-theme' ), 'class' => 'bitbucket' ),
				array( 'url' => 'codepen.io', 'icon' => 'fab fa-codepen', 'title' => esc_html__( 'Follow me on CodePen', 'ltple-theme' ), 'class' => 'codepen' ),
				array( 'url' => 'deviantart.com', 'icon' => 'fab fa-deviantart', 'title' => esc_html__( 'Watch me on DeviantArt', 'ltple-theme' ), 'class' => 'deviantart' ),
				array( 'url' => 'discord.gg', 'icon' => 'fab fa-discord', 'title' => esc_html__( 'Join me on Discord', 'ltple-theme' ), 'class' => 'discord' ),
				array( 'url' => 'dribbble.com', 'icon' => 'fab fa-dribbble', 'title' => esc_html__( 'Follow me on Dribbble', 'ltple-theme' ), 'class' => 'dribbble' ),
				array( 'url' => 'etsy.com', 'icon' => 'fab fa-etsy', 'title' => esc_html__( 'favorite me on Etsy', 'ltple-theme' ), 'class' => 'etsy' ),
				array( 'url' => 'facebook.com', 'icon' => 'fab fa-facebook-f', 'title' => esc_html__( 'Like me on Facebook', 'ltple-theme' ), 'class' => 'facebook' ),
				array( 'url' => 'flickr.com', 'icon' => 'fab fa-flickr', 'title' => esc_html__( 'Connect with me on Flickr', 'ltple-theme' ), 'class' => 'flickr' ),
				array( 'url' => 'foursquare.com', 'icon' => 'fab fa-foursquare', 'title' => esc_html__( 'Follow me on Foursquare', 'ltple-theme' ), 'class' => 'foursquare' ),
				array( 'url' => 'github.com', 'icon' => 'fab fa-github', 'title' => esc_html__( 'Fork me on GitHub', 'ltple-theme' ), 'class' => 'github' ),
				array( 'url' => 'instagram.com', 'icon' => 'fab fa-instagram', 'title' => esc_html__( 'Follow me on Instagram', 'ltple-theme' ), 'class' => 'instagram' ),
				array( 'url' => 'kickstarter.com', 'icon' => 'fab fa-kickstarter-k', 'title' => esc_html__( 'Back me on Kickstarter', 'ltple-theme' ), 'class' => 'kickstarter' ),
				array( 'url' => 'last.fm', 'icon' => 'fab fa-lastfm', 'title' => esc_html__( 'Follow me on Last.fm', 'ltple-theme' ), 'class' => 'lastfm' ),
				array( 'url' => 'linkedin.com', 'icon' => 'fab fa-linkedin-in', 'title' => esc_html__( 'Connect with me on LinkedIn', 'ltple-theme' ), 'class' => 'linkedin' ),
				array( 'url' => 'medium.com', 'icon' => 'fab fa-medium-m', 'title' => esc_html__( 'Follow me on Medium', 'ltple-theme' ), 'class' => 'medium' ),
				array( 'url' => 'patreon.com', 'icon' => 'fab fa-patreon', 'title' => esc_html__( 'Support me on Patreon', 'ltple-theme' ), 'class' => 'patreon' ),
				array( 'url' => 'pinterest.com', 'icon' => 'fab fa-pinterest-p', 'title' => esc_html__( 'Follow me on Pinterest', 'ltple-theme' ), 'class' => 'pinterest' ),
				array( 'url' => 'plus.google.com', 'icon' => 'fab fa-google-plus-g', 'title' => esc_html__( 'Connect with me on Google+', 'ltple-theme' ), 'class' => 'googleplus' ),
				array( 'url' => 'reddit.com', 'icon' => 'fab fa-reddit-alien', 'title' => esc_html__( 'Join me on Reddit', 'ltple-theme' ), 'class' => 'reddit' ),
				array( 'url' => 'slack.com', 'icon' => 'fab fa-slack-hash', 'title' => esc_html__( 'Join me on Slack', 'ltple-theme' ), 'class' => 'slack.' ),
				array( 'url' => 'slideshare.net', 'icon' => 'fab fa-slideshare', 'title' => esc_html__( 'Follow me on SlideShare', 'ltple-theme' ), 'class' => 'slideshare' ),
				array( 'url' => 'snapchat.com', 'icon' => 'fab fa-snapchat-ghost', 'title' => esc_html__( 'Add me on Snapchat', 'ltple-theme' ), 'class' => 'snapchat' ),
				array( 'url' => 'soundcloud.com', 'icon' => 'fab fa-soundcloud', 'title' => esc_html__( 'Follow me on SoundCloud', 'ltple-theme' ), 'class' => 'soundcloud' ),
				array( 'url' => 'spotify.com', 'icon' => 'fab fa-spotify', 'title' => esc_html__( 'Follow me on Spotify', 'ltple-theme' ), 'class' => 'spotify' ),
				array( 'url' => 'stackoverflow.com', 'icon' => 'fab fa-stack-overflow', 'title' => esc_html__( 'Join me on Stack Overflow', 'ltple-theme' ), 'class' => 'stackoverflow' ),
				array( 'url' => 'tumblr.com', 'icon' => 'fab fa-tumblr', 'title' => esc_html__( 'Follow me on Tumblr', 'ltple-theme' ), 'class' => 'tumblr' ),
				array( 'url' => 'twitch.tv', 'icon' => 'fab fa-twitch', 'title' => esc_html__( 'Follow me on Twitch', 'ltple-theme' ), 'class' => 'twitch' ),
				array( 'url' => 'twitter.com', 'icon' => 'fab fa-twitter', 'title' => esc_html__( 'Follow me on Twitter', 'ltple-theme' ), 'class' => 'twitter' ),
				array( 'url' => 'vimeo.com', 'icon' => 'fab fa-vimeo-v', 'title' => esc_html__( 'Follow me on Vimeo', 'ltple-theme' ), 'class' => 'vimeo' ),
				array( 'url' => 'weibo.com', 'icon' => 'fab fa-weibo', 'title' => esc_html__( 'Follow me on weibo', 'ltple-theme' ), 'class' => 'weibo' ),
				array( 'url' => 'youtube.com', 'icon' => 'fab fa-youtube', 'title' => esc_html__( 'Subscribe to me on YouTube', 'ltple-theme' ), 'class' => 'youtube' ),
			));
					

			foreach( $social_urls as $key => $value ) {
				
				if ( !empty( $value ) ) {
					
					$domain = str_ireplace( 'www.', '', parse_url( $value, PHP_URL_HOST ) );
					
					$index = array_search( strtolower( $domain ), array_column( $social_icons, 'url' ) );
					
					if( false !== $index ) {
						
						$output[] = sprintf( '<li class="%1$s list-inline-item"><a href="%2$s" title="%3$s"%4$s><i class="%5$s"></i></a></li>',
							
							$social_icons[$index]['class'],
							esc_url( $value ),
							$social_icons[$index]['title'],
							( !$social_newtab ? '' : ' target="_blank"' ),
							$social_icons[$index]['icon']
						);
					}
					else {
						
						$output[] = sprintf( '<li class="nosocial list-inline-item"><a href="%2$s"%3$s><i class="%4$s"></i></a></li>',
							$social_icons[$index]['class'],
							esc_url( $value ),
							( !$social_newtab ? '' : ' target="_blank"' ),
							'fas fa-globe'
						);
					}
				}
			}
		}

		if( LTPLE_Theme::get_mod('social_rss') ) {
			
			$output[] = sprintf( '<li class="%1$s list-inline-item"><a href="%2$s" title="%3$s"%4$s><i class="%5$s"></i></a></li>',
				'rss',
				home_url( '/feed' ),
				'Subscribe to my RSS feed',
				( !$social_newtab ? '' : ' target="_blank"' ),
				'fas fa-rss'
			);
		}

		if ( !empty( $output ) ) {
			
			$social_alignment 	= LTPLE_Theme::get_mod( 'social_alignment');
			
			$output = apply_filters( 'ltple_theme_social_icons_list', $output );
			
			array_unshift( $output, '<div class="social"><ul class="social-icons list-inline studio-social ' . $social_alignment . '">' );
			
			$output[] = '</ul></div>';
		}

		return implode( '', $output );
	}

	public static function instance( $file = '', $settings = array() ) {
		
		if( is_null( self::$_instance ) ) {
			
			self::$_instance = new self( $file, $settings );
		}
		
		return self::$_instance;
	}

	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	}

	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	}
}

function LTPLE_Theme( $args = array() ) {
	
	$instance = LTPLE_Theme::instance( __FILE__, $args );

	return $instance;
}