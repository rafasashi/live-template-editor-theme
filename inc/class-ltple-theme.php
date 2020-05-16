<?php
/**
 * LTPLE Theme Class
 *
 */

class LTPLE_Theme {
	
	private static $_instance = null;
	
	var $_info 		= null;
	var $_version 	= null;
	var $_file 		= null;
	var $dir 		= null;
	var $slug 		= null;
	var $_token 	= 'ltple';
	
	var $theme 		= null;
	var $editor 	= null;
	var $form 		= null;
	
	var $defaults 		= null;
	var $panels 		= array();
	var $sections 		= array();
	var $customizers 	= array();
	var $fonts 			= array();
	
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
	
	var $templates = array( 
	
		//'templates/saas.php' 		=> 'LTPLE SaaS',
		//'templates/full-page.php' => 'LTPLE Full Page',
	); 

	public function __construct( $file = '', $args = array() ) {
		
		$this->_info 	= wp_get_theme();
		
		$this->_version = $this->_info->get('Version');		
		
		$this->_file 	= $file;
		
		$this->dir 		= get_template_directory();

		$this->assets_url 	= trailingslashit( get_template_directory_uri() );
				
		//Customizer
		
		add_action('customize_register', array($this,'customize_register'));

		//Styles & Scripts
	
		add_action( 'wp_enqueue_scripts', array($this,'enqueue_fonts'),999997);
		
		add_action( 'customize_controls_print_styles', array($this,'enqueue_fonts'));
		
		/*
		add_action( 'customize_preview_init', 	function(){
			
			wp_enqueue_script( 'ltple-customizer-preview', trailingslashit( get_template_directory_uri() ) . 'js/customizer-preview.js', array( 'customize-preview', 'jquery' ) );
			
		});
		*/
		
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
		});
		
		add_filter( 'init', array($this,'init') );		
	
		add_filter( 'wp_nav_menu_items', array($this,'get_searchbar_menu'), 8888, 2 );
		
		//DEFAULT PARENT THEME SETTINGS
		
		if( !is_child_theme() ){
			
			if( is_admin() ){
				
				add_action( 'admin_notices', function(){
					
					$theme_page_url = '#';

					if( !get_option( 'triggered_welcomet') ){
						$message = sprintf(__( 'Welcome to Live Template Editor Theme! Before diving in to your new theme, please visit the <a href="%1$s" target="_blank">theme\'s</a> page for access to dozens of tips and in-depth tutorials.', 'ltple-theme-theme' ),
							esc_url( $theme_page_url )
						);

						printf(
							'<div class="notice is-dismissible">
								<p>%1$s</p>
							</div>',
							$message
						);
						add_option( 'triggered_welcomet', '1', '', 'yes' );
					}
				});	
			}
			else{
				
				include_once $this->dir . '/inc/class-ltple-navwalker.php';

				add_action( 'wp_enqueue_scripts', function() {
					
					// load bootstrap css

					wp_enqueue_style( 'ltple-theme-bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css' );

					// load bootstrap css
					// load AItheme styles
					// load Live Template Editor Theme styles
					
					wp_enqueue_style( 'ltple-theme-style', get_stylesheet_uri(), array(), $this->_version );
					
					wp_enqueue_script('jquery');

					// Internet Explorer HTML5 support
					wp_enqueue_script( 'html5hiv',get_template_directory_uri().'/js/html5.js', array(), '3.7.0', false );
					wp_script_add_data( 'html5hiv', 'conditional', 'lt IE 9' );

					// load bootstrap js

					wp_enqueue_script('ltple-theme-popper', get_template_directory_uri() . '/js/popper.min.js', array(), '', true );
					wp_enqueue_script('ltple-theme-bootstrapjs', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '', true );
					
					wp_enqueue_script('ltple-theme-themejs', get_template_directory_uri() . '/js/theme-script.min.js', array(), $this->_version, true );
					wp_enqueue_script( 'ltple-theme-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.min.js', array(), '20151215', true );

					if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
						
						wp_enqueue_script( 'comment-reply' );
					}
				});
				
				add_filter('nav_menu_submenu_css_class',function($classes){
										
					$classes[] = 'dropdown-menu-right';
					
					return $classes;
				});

				add_filter( 'the_password_form', function() {
					
					global $post;
					
					$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
					
					$o = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
					<div class="d-block mb-3">' . __( "To view this protected post, enter the password below:", "ltple-theme" ) . '</div>
					<div class="form-group form-inline"><label for="' . $label . '" class="mr-2">' . __( "Password:", "ltple-theme" ) . ' </label><input name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" class="form-control mr-2" /> <input type="submit" name="Submit" value="' . esc_attr__( "Submit", "ltple-theme" ) . '" class="btn btn-primary"/></div>
					</form>';
					
					return $o;
				});
			}
			
			include_once $this->dir . '/inc/class-ltple-custom-widgets.php';

			add_action( 'widgets_init', function() {
	
				unregister_widget( 'WP_Widget_Recent_Posts' );
				register_widget( 'LTPLE_Widget_Recent_Posts' );
	
				register_sidebar( array(
					'name'          => esc_html__( 'Sidebar', 'ltple-theme' ),
					'id'            => 'sidebar-1',
					'description'   => esc_html__( 'Add widgets here.', 'ltple-theme' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h3 class="widget-title">',
					'after_title'   => '</h3>',
				) );
				register_sidebar( array(
					'name'          => esc_html__( 'Footer 1', 'ltple-theme' ),
					'id'            => 'footer-1',
					'description'   => esc_html__( 'Add widgets here.', 'ltple-theme' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h3 class="widget-title">',
					'after_title'   => '</h3>',
				) );
				register_sidebar( array(
					'name'          => esc_html__( 'Footer 2', 'ltple-theme' ),
					'id'            => 'footer-2',
					'description'   => esc_html__( 'Add widgets here.', 'ltple-theme' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h3 class="widget-title">',
					'after_title'   => '</h3>',
				) );
				register_sidebar( array(
					'name'          => esc_html__( 'Footer 3', 'ltple-theme' ),
					'id'            => 'footer-3',
					'description'   => esc_html__( 'Add widgets here.', 'ltple-theme' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h3 class="widget-title">',
					'after_title'   => '</h3>',
				) );
			} );			
		}
	}

	public function init(){	
		
		// get form
		
		$this->init_form();
		
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
			
			if( empty($locations['header']) ){
			
				$locations['header'] = $menu->term_id;
			
				set_theme_mod( 'nav_menu_locations', $locations );
			}
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
			
			if( empty($locations['footer']) ){
			
				$locations['footer'] = $menu->term_id;
			
				set_theme_mod( 'nav_menu_locations', $locations );
			}
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
	
	public function init_form(){
		
		include_once 'class-ltple-form.php';
		
		$this->form = new LTPLE_Theme_Contact_Form($this);
	}
	
	public function set_default_customizers(){
		
		if( $this->demo_samples === true ){
		
			include_once trailingslashit( dirname($this->_file) ) . '/sample-controls.php';
		}
		
		//set panels
		
		$this->set_panel('theme_style_panel', array(

			'title' 		=> 'Main Style',
			'description' 	=> 'Adjust the main theme style',
			'priority'		=> 100,
		));

		//set sections
		
		$this->set_section('main_background_section', array(

			'title' 		=> 'Page',
			'description' 	=> 'Change the body background of the theme',
			'panel' 		=> 'theme_style_panel',
		));	

		$this->set_section('titles_section', array(

			'title' 		=> 'Titles',
			'description' 	=> 'Change the style of titles and subtitles',
			'panel' 		=> 'theme_style_panel',
		));

		$this->set_section('texts_section', array(

			'title' 		=> 'Texts',
			'description' 	=> 'Change the style of the texts',
			'panel' 		=> 'theme_style_panel',
		));

		$this->set_section('links_section', array(

			'title' 		=> 'Links',
			'description' 	=> 'Change the style of links',
			'panel' 		=> 'theme_style_panel',
		));			

		//set customizers
		
		$this->set_customizer('header_logo', array(
			
			'setting' 	=> array(
				'transport'	=> 'refresh',
				'default'	=> '',
			),
			'class' 	=> 'WP_Customize_Image_Control',
			'control'	=> array(
			
				'label' 	=> 'Header Logo',
				'section' 	=> 'title_tagline',
			),
			'partial' 	=> array(
				'selector' 		 	=> '.navbar-brand',
				'render_callback' 	=> array($this,'render_logo'),			
			)
		));

		$this->set_customizer('title_color', array(
			
			'setting' => array(
								
				'default'		 	=> '#444444',
				'transport'		 	=> 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
			),
			'class' 	=> 'WP_Customize_Color_Control',
			'control' 	=> array(
			
				'label' 	=> 'Titles Color',
				'section' 	=> 'titles_section',
				'type' 		=> 'color',
			),
			'partial' 	=> array(
			
				'render_callback' => array($this,'render_color'),			
			),
		));

		$this->set_customizer('title_font', array(

			'setting' => array(
				
				'default' => json_encode(array(
	
					'font' 			=> 'Lato',
					'regularweight' => 'regular',
					'italicweight' 	=> 'regular',
					'boldweight' 	=> 'regular',
					'category' 		=> 'sans-serif'
				)),
				'transport'		 	=> 'refresh',
				'sanitize_callback' => 'ltple_theme_google_font_sanitization'
			),
			'class' 	=> 'LTPLE_Theme_Google_Font_Select_Custom_Control',
			'control' 	=> array(
			
				'label' 		=> 'Title Font',
				'section' 		=> 'titles_section',
				'description'	=> '',
				'input_attrs' 	=> array(
				
					'font_count' 	=> 'all',
					'orderby' 		=> 'alpha',
				),			
			),
			'partial' 	=> array(
			
				'render_callback' => array($this,'render_font'),			
			),
		));
		
		$this->set_customizer('text_color', array(
			
			'setting' => array(
								
				'default'		 	=> '#444444',
				'transport'		 	=> 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
			),
			'class' 	=> 'WP_Customize_Color_Control',
			'control' 	=> array(
			
				'label' 	=> 'Texts Color',
				'section' 	=> 'texts_section',
				'type' 		=> 'color',
			),
			'partial' 	=> array(
			
				'render_callback' => array($this,'render_color'),			
			),
		));

		$this->set_customizer('text_font', array(

			'setting' => array(
				
				'default' => json_encode(array(
	
					'font' 			=> 'Lato',
					'regularweight' => 'regular',
					'italicweight' 	=> 'regular',
					'boldweight' 	=> 'regular',
					'category' 		=> 'sans-serif'
				)),
				'transport'		 	=> 'refresh',
				'sanitize_callback' => 'ltple_theme_google_font_sanitization'
			),
			'class' 	=> 'LTPLE_Theme_Google_Font_Select_Custom_Control',
			'control' 	=> array(
			
				'label' 		=> 'Texts Font',
				'section' 		=> 'texts_section',
				'description'	=> '',
				'input_attrs' 	=> array(
				
					'font_count' 	=> 'all',
					'orderby' 		=> 'alpha',
				),			
			),
			'partial' 	=> array(
			
				'render_callback' => array($this,'render_font'),			
			),
		));
		
		$this->set_customizer('link_color', array(
			
			'setting' => array(
								
				'default'		 	=> '#329ef7',
				'transport'		 	=> 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
			),
			'class' 	=> 'WP_Customize_Color_Control',
			'control' 	=> array(
			
				'label' 	=> 'Link Color',
				'section' 	=> 'links_section',
				'type' 		=> 'color',
			),
			'partial' 	=> array(
			
				'render_callback' => array($this,'render_color'),			
			),
		));
		
		$this->set_customizer('link_hover_color', array(
			
			'setting' => array(
								
				'default'		 	=> '#329ef7',
				'transport'		 	=> 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
			),
			'class' 	=> 'WP_Customize_Color_Control',
			'control' 	=> array(
			
				'label' 	=> 'Hover Color',
				'section' 	=> 'links_section',
				'type' 		=> 'color',
			),
			'partial' 	=> array(
			
				'render_callback' => array($this,'render_color'),			
			),
		));
		
		do_action('ltple_theme_customizers',$this);
	}
	
	public static function get_default_mod($option_name){
		
		$default = '';
		
		if( $defaults = self::get_default_mods() ){
			
			if( isset($defaults[$option_name]) ){
				
				$default = $defaults[$option_name];
			}
		}
		
		return $default;
	}
	
	public static function get_default_mods(){
		
		if( is_null(self::$_instance->defaults) ){
			
			$defaults = array();
			
			if( !empty(self::$_instance->customizers) ){
			
				foreach(self::$_instance->customizers as $name => $customizer ){
					
					if( isset($customizer['setting']['default']) ){
						
						$defaults[$name] = $customizer['setting']['default'];
					}
				}
			}
			
			self::$_instance->defaults = apply_filters( 'ltple_theme_customizer_defaults', $defaults );		
		}
		
		return self::$_instance->defaults;
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
				
			// register fonts
			
			if( isset($settings['class']) && $settings['class'] == 'LTPLE_Theme_Google_Font_Select_Custom_Control' ){
				
				if( !isset($this->fonts[$option_name]) ){
					
					$this->fonts[$option_name] = json_decode(self::get_mod($option_name));
				}
			}
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
				
				$customizer['setting']['default'] = $this->get_default_mod($customizer_name);

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
						elseif( $customizer['control']['type'] == 'number' ){
							
							$sanitize_callback = 'ltple_theme_sanitize_integer';
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

				// add controle
				
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
	
	public function enqueue_fonts() {
					 
		$fonts = array();
		
		if( !empty($this->fonts) ){
			
			foreach( $this->fonts as $data ){

				if( !empty($data->font) ){ 
					
					$font = urlencode($data->font);
					
					if( !in_array($font,$fonts) ){
					
						$fonts[] = $font;
					}
				}
			}
		}
			
		if( !empty($fonts) ){
			
			$fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode($fonts,'&family=') . '&display=swap';
			
			// css import
			
			wp_register_style( 'theme-fonts', false, array());
			wp_enqueue_style( 'theme-fonts' );

			wp_add_inline_style( 'theme-fonts', "
				
				@import url('".$fonts_url."');
			");
		}	
		
		// webfonts
		
		wp_register_style( 'fontawesome-5', get_template_directory_uri() . '/css/fontawesome.min.css' );			
		wp_enqueue_style( 'fontawesome-5' );
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

	public static function get_mod($option_name){

		return get_theme_mod($option_name,self::get_default_mod($option_name));
	}

	public static function render($option_name){

		if( isset(self::$_instance->customizers[$option_name]['partial']['render_callback']) ){
			
			$args = ( ( isset(self::$_instance->customizers[$option_name]['partial']['callback_args']) && is_numeric(self::$_instance->customizers[$option_name]['partial']['callback_args']) ) ? self::$_instance->customizers[$option_name]['partial']['callback_args'] : $option_name );

			return self::$_instance->customizers[$option_name]['partial']['render_callback']($args);
		}
	}
	
	public function render_background_url($option_name){
					
		return !empty($option_name) ? 'url(' . LTPLE_Theme::get_mod($option_name) . ')' : 'none';
	}
	
	public function render_background_color($option_name){
					
		return !empty($option_name) ? LTPLE_Theme::get_mod($option_name) : 'transparent';
	}
	
	public function render_background_repeat($option_name){
		
		return !empty($option_name) ? LTPLE_Theme::get_mod($option_name) : 'no-repeat';
	}
	
	public function render_color($option_name){
					
		return !empty($option_name) ? LTPLE_Theme::get_mod($option_name) : '#444444';
	}
	
	public function render_font($option_name){
		
		if( $data = json_decode(LTPLE_Theme::get_mod($option_name)) ){
			
			if( empty($this->fonts[$option_name]) || $this->fonts[$option_name]->font != $data->font ){
				
				$fonts_url = 'https://fonts.googleapis.com/css2?family=' . urlencode($data->font) . '&display=swap';

				wp_register_style($option_name, false, array());
				wp_enqueue_style($option_name );

				wp_add_inline_style($option_name, "
					
					@import url('".$fonts_url."');
				");
			}
			
			$font = "'" . $data->font . "'";
			
			if( !empty($data->category) && $data->category == 'serif' || $data->category == 'sans-serif' ){
				
				$font .= ', ' . $data->category;
			}
			
			return $font;
		}

		return 'inherit';
	}
	
	public function render_logo($option_name){
		
		$logo = '';
		
		if( $url = LTPLE_Theme::get_mod($option_name) ){
			
			$logo = '<img src="' . esc_url($url) . '" alt="'.esc_attr( get_bloginfo( 'name' ) ) .'">';
		}

		return $logo;
	}
	
	public function render_social_icons($option_name){

		$output 		= array();

		$social_urls 	= explode( ',', LTPLE_Theme::get_mod($option_name) );

		if( !empty($social_urls) ){
			
			$social_icons 	= apply_filters( 'ltple_theme_social_icons', array(
			
				array( 'url' => 'behance.net', 'icon' => 'fab fa-behance', 'title' => esc_html__( 'Follow us on Behance', 'ltple-theme' ), 'class' => 'behance' ),
				array( 'url' => 'bitbucket.org', 'icon' => 'fab fa-bitbucket', 'title' => esc_html__( 'Fork us on Bitbucket', 'ltple-theme' ), 'class' => 'bitbucket' ),
				array( 'url' => 'codepen.io', 'icon' => 'fab fa-codepen', 'title' => esc_html__( 'Follow us on CodePen', 'ltple-theme' ), 'class' => 'codepen' ),
				array( 'url' => 'deviantart.com', 'icon' => 'fab fa-deviantart', 'title' => esc_html__( 'Watch us on DeviantArt', 'ltple-theme' ), 'class' => 'deviantart' ),
				array( 'url' => 'discord.gg', 'icon' => 'fab fa-discord', 'title' => esc_html__( 'Join us on Discord', 'ltple-theme' ), 'class' => 'discord' ),
				array( 'url' => 'dribbble.com', 'icon' => 'fab fa-dribbble', 'title' => esc_html__( 'Follow us on Dribbble', 'ltple-theme' ), 'class' => 'dribbble' ),
				array( 'url' => 'etsy.com', 'icon' => 'fab fa-etsy', 'title' => esc_html__( 'Favorite us on Etsy', 'ltple-theme' ), 'class' => 'etsy' ),
				array( 'url' => 'facebook.com', 'icon' => 'fab fa-facebook-f', 'title' => esc_html__( 'Contact us on Facebook', 'ltple-theme' ), 'class' => 'facebook' ),
				array( 'url' => 'flickr.com', 'icon' => 'fab fa-flickr', 'title' => esc_html__( 'Connect with us on Flickr', 'ltple-theme' ), 'class' => 'flickr' ),
				array( 'url' => 'foursquare.com', 'icon' => 'fab fa-foursquare', 'title' => esc_html__( 'Follow us on Foursquare', 'ltple-theme' ), 'class' => 'foursquare' ),
				array( 'url' => 'github.com', 'icon' => 'fab fa-github', 'title' => esc_html__( 'Fork us on GitHub', 'ltple-theme' ), 'class' => 'github' ),
				array( 'url' => 'instagram.com', 'icon' => 'fab fa-instagram', 'title' => esc_html__( 'Follow us on Instagram', 'ltple-theme' ), 'class' => 'instagram' ),
				array( 'url' => 'kickstarter.com', 'icon' => 'fab fa-kickstarter-k', 'title' => esc_html__( 'Back us on Kickstarter', 'ltple-theme' ), 'class' => 'kickstarter' ),
				array( 'url' => 'last.fm', 'icon' => 'fab fa-lastfm', 'title' => esc_html__( 'Follow us on Last.fm', 'ltple-theme' ), 'class' => 'lastfm' ),
				array( 'url' => 'linkedin.com', 'icon' => 'fab fa-linkedin-in', 'title' => esc_html__( 'Connect with us on LinkedIn', 'ltple-theme' ), 'class' => 'linkedin' ),
				array( 'url' => 'medium.com', 'icon' => 'fab fa-medium-m', 'title' => esc_html__( 'Follow us on Medium', 'ltple-theme' ), 'class' => 'medium' ),
				array( 'url' => 'patreon.com', 'icon' => 'fab fa-patreon', 'title' => esc_html__( 'Support us on Patreon', 'ltple-theme' ), 'class' => 'patreon' ),
				array( 'url' => 'pinterest.com', 'icon' => 'fab fa-pinterest-p', 'title' => esc_html__( 'Follow us on Pinterest', 'ltple-theme' ), 'class' => 'pinterest' ),
				array( 'url' => 'reddit.com', 'icon' => 'fab fa-reddit-alien', 'title' => esc_html__( 'Join us on Reddit', 'ltple-theme' ), 'class' => 'reddit' ),
				array( 'url' => 'slack.com', 'icon' => 'fab fa-slack-hash', 'title' => esc_html__( 'Join us on Slack', 'ltple-theme' ), 'class' => 'slack.' ),
				array( 'url' => 'slideshare.net', 'icon' => 'fab fa-slideshare', 'title' => esc_html__( 'Follow us on SlideShare', 'ltple-theme' ), 'class' => 'slideshare' ),
				array( 'url' => 'snapchat.com', 'icon' => 'fab fa-snapchat-ghost', 'title' => esc_html__( 'Add us on Snapchat', 'ltple-theme' ), 'class' => 'snapchat' ),
				array( 'url' => 'soundcloud.com', 'icon' => 'fab fa-soundcloud', 'title' => esc_html__( 'Follow us on SoundCloud', 'ltple-theme' ), 'class' => 'soundcloud' ),
				array( 'url' => 'spotify.com', 'icon' => 'fab fa-spotify', 'title' => esc_html__( 'Follow us on Spotify', 'ltple-theme' ), 'class' => 'spotify' ),
				array( 'url' => 'stackoverflow.com', 'icon' => 'fab fa-stack-overflow', 'title' => esc_html__( 'Join us on Stack Overflow', 'ltple-theme' ), 'class' => 'stackoverflow' ),
				array( 'url' => 'tumblr.com', 'icon' => 'fab fa-tumblr', 'title' => esc_html__( 'Follow us on Tumblr', 'ltple-theme' ), 'class' => 'tumblr' ),
				array( 'url' => 'twitch.tv', 'icon' => 'fab fa-twitch', 'title' => esc_html__( 'Follow us on Twitch', 'ltple-theme' ), 'class' => 'twitch' ),
				array( 'url' => 'twitter.com', 'icon' => 'fab fa-twitter', 'title' => esc_html__( 'Follow us on Twitter', 'ltple-theme' ), 'class' => 'twitter' ),
				array( 'url' => 'vimeo.com', 'icon' => 'fab fa-vimeo-v', 'title' => esc_html__( 'Follow us on Vimeo', 'ltple-theme' ), 'class' => 'vimeo' ),
				array( 'url' => 'weibo.com', 'icon' => 'fab fa-weibo', 'title' => esc_html__( 'Follow us on weibo', 'ltple-theme' ), 'class' => 'weibo' ),
				array( 'url' => 'youtube.com', 'icon' => 'fab fa-youtube', 'title' => esc_html__( 'Subscribe to us on YouTube', 'ltple-theme' ), 'class' => 'youtube' ),
				array( 'url' => 'wa.me', 'icon' => 'fab fa-whatsapp', 'title' => esc_html__( 'Contact us on Whatsapp', 'ltple-theme' ), 'class' => 'whatsapp' ),
			));
					
			foreach( $social_urls as $key => $value ) {
				
				if ( !empty( $value ) ) {
					
					$domain = str_ireplace( 'www.', '', parse_url( $value, PHP_URL_HOST ) );
					
					$index = array_search( strtolower( $domain ), array_column( $social_icons, 'url' ) );
					
					if( false !== $index ) {
						
						$output[] = sprintf( '<li class="%1$s nav-item"><a class="nav-link" href="%2$s" title="%3$s"%4$s><i class="%5$s"></i></a></li>',
							
							$social_icons[$index]['class'],
							esc_url( $value ),
							$social_icons[$index]['title'],
							' target="_blank"',
							$social_icons[$index]['icon']
						);
					}
					else {
						
						$output[] = sprintf( '<li class="nav-item"><a class="nav-link" href="%2$s"%3$s><i class="%4$s"></i></a></li>',
							$social_icons[$index]['class'],
							esc_url( $value ),
							' target="_blank"',
							'fas fa-globe'
						);
					}
				}
			}
		}

		return implode( '', $output );
	}
	
	public function render_attachment_url($option_name){
		
		$att = LTPLE_Theme::get_mod($option_name);
		
		if( is_numeric($att) ){
			
			if( $url = wp_get_attachment_url($att) ){
			
				return esc_url($url);
			}
		}
		
		return $att;
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
