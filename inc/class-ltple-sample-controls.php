<?php

	// add panels

	$this->set_panel('sample_custom_controls_panel', array(

		'title' 		=> 'Sample Custom Controls',
		'description' 	=> 'These are an example of Customizer Custom Controls.',
	));

	// add sections
	
	$this->set_section('upsell_section', array(
		
		'class'				=> 'LTPLE_Theme_Upsell_Section',
		'title' 			=> __( 'Premium Addons Available', 'ltple-theme' ),
		'url' 				=> 'https://code.recuweb.com',
		'backgroundcolor' 	=> '#344860',
		'textcolor' 		=> '#fff',
		'priority' 			=> 0,
		
	));

	$this->set_section('sample_text_controls_section', array(

		'title' 		=> 'Text Controls',
		'description' 	=> 'These are an example of Customizer Text Controls.',
		'panel' 		=> 'sample_custom_controls_panel'
	));
	
	$this->set_section('sample_media_controls_section', array(

		'title' 		=> 'Media Controls',
		'description' 	=> 'These are an example of Customizer Media Controls.',
		'panel' 		=> 'sample_custom_controls_panel'
	));
	
	$this->set_section('sample_date_controls_section', array(

		'title' 		=> 'Date Controls',
		'description' 	=> 'These are an example of Customizer Date Controls.',
		'panel' 		=> 'sample_custom_controls_panel'
	));
	
	$this->set_section('sample_color_controls_section', array(

		'title' 		=> 'Color Controls',
		'description' 	=> 'These are an example of Customizer Color Controls.',
		'panel' 		=> 'sample_custom_controls_panel'
	));
	
	// add customizers
	
	$this->set_customizer('sample_default_text', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_text_sanitization'
		),
		'control' 	=> array(
		
			'label' 		=> 'Text Control',
			'description' 	=> 'Text controls Type can be either text, email, url, number, hidden, or date',
			'section' 		=> 'sample_text_controls_section',
			'type' 			=> 'text',
			'input_attrs' 	=> array(
			
				'class' 		=> 'my-custom-class',
				'style' 		=> 'border: 1px solid rebeccapurple',
				'placeholder' 	=> 'Enter name...',
			),
		),
	));
		
	// Test of Email Control
	
	$this->set_customizer('sample_email_text', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'sanitize_email'
		),
		'control' => array(
			
			'label' 		=> 'Email Control',
			'description' 	=> 'Text controls Type can be either text, email, url, number, hidden, or date',
			'section' 		=> 'sample_text_controls_section',
			'type' 			=> 'email',
		),
	));
	
	$this->set_customizer('sample_url_text', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'esc_url_raw'
		),
		'control' => array(
		
			'label' 		=> 'URL Control',
			'description' 	=> 'Text controls Type can be either text, email, url, number, hidden, or date',
			'section' 		=> 'sample_text_controls_section',
			'type' 			=> 'url',
			'input_attrs' 	=> array(
			
				'placeholder' 	=> 'https://',
			),
		),
	));
	
	$this->set_customizer('sample_number_text', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_sanitize_integer'
		),
		'control' => array(
		
			'label' => 'Number Control',
			'description' => 'Text controls Type can be either text, email, url, number, hidden, or date',
			'section' => 'sample_text_controls_section',
			'type' => 'number'
		),
	));
	
	$this->set_customizer('sample_hidden_text', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_text_sanitization'
		),
		'control' => array(
		
			'label' => 'Hidden Control',
			'description' => 'Text controls Type can be either text, email, url, number, hidden, or date',
			'section' => 'sample_text_controls_section',
			'type' => 'hidden'
		),
	));
	
	$this->set_customizer('sample_default_checkbox', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_switch_sanitization'
		),
		'control' => array(
		
			'label' => 'Checkbox Control',
			'description' => 'Sample Checkbox description',
			'section' => 'sample_text_controls_section',
			'type' => 'checkbox'
		),
	));
	
	$this->set_customizer('sample_default_select', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_radio_sanitization'
		),
		'control' => array(
		
			'label' 	=> 'Standard Select Control',
			'section' 	=> 'sample_text_controls_section',
			'type' 		=> 'select',
			'choices' 	=> array(
			
				'wordpress' 		=> 'WordPress',
				'hamsters' 			=> 'Hamsters',
				'jet-fuel' 			=> 'Jet Fuel',
				'nuclear-energy' 	=> 'Nuclear Energy',
			)
		),
	));
	
	$this->set_customizer('sample_default_radio', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_radio_sanitization'
		),
		'control' => array(
		
			'label' 	=> 'Standard Radio Control',
			'section' 	=> 'sample_text_controls_section',
			'type' 		=> 'radio',
			'choices' 	=> array(
			
				'captain-america' 	=> 'Captain America',
				'iron-man' 			=> 'Iron Man',
				'spider-man' 		=> 'Spider-Man',
				'thor' 				=> 'Thor',
			)
		),
	));
	
	$this->set_customizer('sample_default_dropdownpages', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'absint'
		),
		'control' => array(
		
			'label' 	=> 'Dropdown Pages Control',
			'section' 	=> 'sample_text_controls_section',
			'type' 		=> 'dropdown-pages'
		),
	));
	
	$this->set_customizer('sample_default_textarea', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		),
		'control' => array(
		
			'label' 		=> 'Textarea Control',
			'section' 		=> 'sample_text_controls_section',
			'type' 			=> 'textarea',
			'input_attrs' 	=> array(
				
				'class' 		=> 'my-custom-class',
				'style' 		=> 'border: 1px solid #999',
				'placeholder' 	=> 'Enter message...',
			),
		),
	));
	
	$this->set_customizer('sample_default_color', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'sanitize_hex_color'
		),
		'class' => 'WP_Customize_Color_Control',
		'control' => array(
		
			'label' 	=> 'Color Control',
			'section' 	=> 'sample_color_controls_section',
			'type' 		=> 'color'
		),
	));
	
	$this->set_customizer('sample_default_media', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'absint'
		),
		'class' 	=> 'WP_Customize_Media_Control',
		'control' 	=> array(
		
			'label' 		=> 'Media Control',
			'description' 	=> 'This is the description for the Media Control',
			'section' 		=> 'sample_media_controls_section',
			'mime_type' 	=> 'image',
			'button_labels' => array(
			
				'select' 		=> 'Select File',
				'change' 		=> 'Change File',
				'default' 		=> 'Default',
				'remove' 		=> 'Remove',
				'placeholder' 	=> 'No file selected',
				'frame_title' 	=> 'Select File',
				'frame_button' 	=> 'Choose File',
			)
		),
	));
	
	$this->set_customizer('sample_default_image', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'esc_url_raw'
		),
		'class' 	=> 'WP_Customize_Image_Control',
		'control' 	=> array(
		
			'label' 		=> 'Image Control',
			'description' 	=> 'This is the description for the Image Control',
			'section' 		=> 'sample_media_controls_section',
			'button_labels' => array(
				
				'select' 		=> 'Select Image',
				'change' 		=> 'Change Image',
				'remove' 		=> 'Remove',
				'default' 		=> 'Default',
				'placeholder' 	=> 'No image selected',
				'frame_title' 	=> 'Select Image',
				'frame_button' 	=> 'Choose Image',
			)
		),
	));
	
	$this->set_customizer('sample_default_cropped_image', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'absint'
		),
		'class' 	=> 'WP_Customize_Cropped_Image_Control',
		'control' 	=> array(
		
			'label' 		=> 'Cropped Image Control',
			'description' 	=> 'This is the description for the Cropped Image Control',
			'section' 		=> 'sample_media_controls_section',
			'flex_width' 	=> false,
			'flex_height' 	=> false,
			'width' 		=> 800,
			'height' 		=> 400
		),
	));
	
	$this->set_customizer('sample_date_text', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_text_sanitization'
		),
		'class' 	=> 'WP_Customize_Date_Time_Control',
		'control' 	=> array(
		
			'label' 		=> 'Date Picker',
			'description' 	=> 'Text controls Type can be either text, email, url, number, hidden, or date',
			'section' 		=> 'sample_date_controls_section',
			'type' 			=> 'date'
		),
	));
	
	$this->set_customizer('sample_date_only', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_date_time_sanitization'
		),
		'class' 	=> 'WP_Customize_Date_Time_Control',
		'control' 	=> array(
		
			'label' 			=> 'Date Control',
			'description' 		=> 'This is the Date Time Control but is only displaying a date field. It also has Max and Min years set.',
			'section' 			=> 'sample_date_controls_section',
			'include_time' 		=> false,
			'allow_past_date' 	=> true,
			'twelve_hour_format'=> true,
			'min_year' 			=> '2016',
			'max_year' 			=> '2025',
		),
	));
	
	$this->set_customizer('sample_date_time', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_date_time_sanitization'
		),
		'class' 	=> 'WP_Customize_Date_Time_Control',
		'control' 	=> array(
		
			'label' 			=> 'Date + Time Control',
			'description' 		=> 'This is the Date Time Control. It also has Max and Min years set.',
			'section' 			=> 'sample_date_controls_section',
			'include_time' 		=> true,
			'allow_past_date' 	=> true,
			'twelve_hour_format'=> true,
			'min_year' 			=> '2010',
			'max_year' 			=> '2020',
		),
	));
	
	$this->set_customizer('sample_date_time_no_past_date', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_date_time_sanitization'
		),
		'class' 	=> 'WP_Customize_Date_Time_Control',
		'control' 	=> array(
		
			'label' 			=> 'Date Control',
			'description' 		=> "This is the Date Time Control but is only displaying a date field. Past dates are not allowed.",
			'section' 			=> 'sample_date_controls_section',
			'include_time' 		=> false,
			'allow_past_date' 	=> false,
			'twelve_hour_format'=> true,
			'min_year' 			=> '2016',
			'max_year' 			=> '2099',
		),
	));
	
	$this->set_customizer('sample_toggle_switch', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_switch_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Toggle_Switch_Custom_control',
		'control' 	=> array(
		
			'label' => 'Toggle switch',
			'section' => 'sample_text_controls_section'
		),
	));
	
	$this->set_customizer('sample_slider_control', array(

		'setting' => array(
		
			'transport'		 	=> 'postMessage',
			'sanitize_callback' => 'absint'
		),
		'class' 	=> 'LTPLE_Theme_Slider_Custom_Control',
		'control' 	=> array(
		
			'label' 		=> 'Slider Control (px)',
			'section' 		=> 'sample_text_controls_section',
			'input_attrs' 	=> array(
			
				'min' 	=> 10,
				'max' 	=> 90,
				'step' 	=> 1,
			),
		),
	));
	
	$this->set_customizer('sample_slider_control_small_step', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_range_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Slider_Custom_Control',
		'control' 	=> array(
		
			'label' 		=> 'Slider Control With a Small Step',
			'section' 		=> 'sample_text_controls_section',
			'input_attrs' 	=> array(
				
				'min' => 0,
				'max' => 4,
				'step' => .5,
			),
		),
	));
	
	$this->set_customizer('sample_sortable_repeater_control', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_url_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Sortable_Repeater_Url_Custom_Control',
		'control' 	=> array(
		
			'label' 		=> 'Sortable Repeater',
			'description' 	=> 'This is the control description.',
			'section' 		=> 'sample_text_controls_section',
			'button_labels' => array(
				
				'add' => 'Add Row',
			)
		),
	));
	
	$this->set_customizer('sample_image_radio_button', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_radio_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Image_Radio_Button_Custom_Control',
		'control' 	=> array(
		
			'label' 		=> 'Image Radio Button Control',
			'description' 	=> 'Sample custom control description',
			'section'		=> 'sample_text_controls_section',
			'choices' 		=> array(
			
				'sidebarleft' => array(
				
					'image' => trailingslashit( get_template_directory_uri() ) . 'images/sidebar-left.png',
					'name' 	=> 'Left Sidebar',
				),
				'sidebarnone' => array(
				
					'image' => trailingslashit( get_template_directory_uri() ) . 'images/sidebar-none.png',
					'name' 	=> 'No Sidebar',
				),
				'sidebarright' => array(
				
					'image' => trailingslashit( get_template_directory_uri() ) . 'images/sidebar-right.png',
					'name' 	=> 'Right Sidebar',
				)
			)
		),
	));
	
	$this->set_customizer('sample_text_radio_button', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_radio_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Text_Radio_Button_Custom_Control',
		'control' 	=> array(
		
			'label' 		=> 'Text Radio Button Control',
			'description' 	=> 'Sample custom control description',
			'section' 		=> 'sample_text_controls_section',
			'choices' 		=> array(
			
				'left' 		=> 'Left',
				'centered' 	=> 'Centered',
				'right' 	=> 'Right',
			)
		),
	));
	
	$this->set_customizer('sample_image_checkbox', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_text_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Image_checkbox_Custom_Control',
		'control' 	=> array(
		
			'label' 		=> 'Image Checkbox Control',
			'description' 	=> 'Sample custom control description',
			'section' 		=> 'sample_text_controls_section',
			'choices' 		=> array(
			
				'stylebold' => array(
				
					'image' => trailingslashit( get_template_directory_uri() ) . 'images/Bold.png',
					'name' 	=> 'Bold',
				),
				'styleitalic' => array(
				
					'image' => trailingslashit( get_template_directory_uri() ) . 'images/Italic.png',
					'name' 	=> 'Italic',
				),
				'styleallcaps' => array(
				
					'image' => trailingslashit( get_template_directory_uri() ) . 'images/AllCaps.png',
					'name' 	=> 'All Caps',
				),
				'styleunderline' => array(
				
					'image' => trailingslashit( get_template_directory_uri() ) . 'images/Underline.png',
					'name' 	=> 'Underline',
				)
			)
		),
	));
	
	$this->set_customizer('sample_single_accordion', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_text_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Single_Accordion_Custom_Control',
		'control' 	=> array(
		
			'label' 		=> 'Single Accordion Control',
			'description' 	=> array(
			
				'Behance' 		=> '<i class="fab fa-behance"></i>',
				'Bitbucket' 	=> '<i class="fab fa-bitbucket"></i>',
				'CodePen' 		=> '<i class="fab fa-codepen"></i>',
				'DeviantArt'	=> '<i class="fab fa-deviantart"></i>',
				'Discord' 		=> '<i class="fab fa-discord"></i>',
				'Dribbble' 		=> '<i class="fab fa-dribbble"></i>',
				'Etsy' 			=> '<i class="fab fa-etsy"></i>',
				'Facebook' 		=> '<i class="fab fa-facebook-f"></i>',
				'Flickr' 		=> '<i class="fab fa-flickr"></i>',
				'Foursquare' 	=> '<i class="fab fa-foursquare"></i>',
				'GitHub' 		=> '<i class="fab fa-github"></i>',
				'Google+' 		=> '<i class="fab fa-google-plus-g"></i>',
				'Instagram' 	=> '<i class="fab fa-instagram"></i>',
				'Kickstarter' 	=> '<i class="fab fa-kickstarter-k"></i>',
				'Last.fm' 		=> '<i class="fab fa-lastfm"></i>',
				'LinkedIn' 		=> '<i class="fab fa-linkedin-in"></i>',
				'Medium' 		=> '<i class="fab fa-medium-m"></i>',
				'Patreon' 		=> '<i class="fab fa-patreon"></i>',
				'Pinterest' 	=> '<i class="fab fa-pinterest-p"></i>',
				'Reddit' 		=> '<i class="fab fa-reddit-alien"></i>',
				'Slack' 		=> '<i class="fab fa-slack-hash"></i>',
				'SlideShare' 	=> '<i class="fab fa-slideshare"></i>',
				'Snapchat' 		=> '<i class="fab fa-snapchat-ghost"></i>',
				'SoundCloud' 	=> '<i class="fab fa-soundcloud"></i>',
				'Spotify' 		=> '<i class="fab fa-spotify"></i>',
				'StackOverflow'	=> '<i class="fab fa-stack-overflow"></i>',
				'Tumblr' 		=> '<i class="fab fa-tumblr"></i>',
				'Twitch' 		=> '<i class="fab fa-twitch"></i>',
				'Twitter' 		=> '<i class="fab fa-twitter"></i>',
				'Vimeo' 		=> '<i class="fab fa-vimeo-v"></i>',
				'Weibo' 		=> '<i class="fab fa-weibo"></i>',
				'YouTube' 		=> '<i class="fab fa-youtube"></i>',
			),
			'section' => 'sample_text_controls_section'
		),
	));
	
	$this->set_customizer('sample_alpha_color', array(

		'setting' => array(
		
			'transport'		 	=> 'postMessage',
			'sanitize_callback' => 'ltple_theme_hex_rgba_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Customize_Alpha_Color_Control',
		'control' 	=> array(
		
			'label' 		=> 'Alpha Color Picker Control',
			'description' 	=> 'Sample custom control description',
			'section' 		=> 'sample_color_controls_section',
			'show_opacity' 	=> true,
			'palette' 		=> array(
			
				'#000',
				'#fff',
				'#df312c',
				'#df9a23',
				'#eef000',
				'#7ed934',
				'#1571c1',
				'#8309e7'
			)
		),
	));
	
	$this->set_customizer('sample_wpcolorpicker_alpha_color', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_hex_rgba_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Alpha_Color_Control',
		'control' 	=> array(
		
			'label' 		=> 'WP ColorPicker Alpha Color Picker',
			'description' 	=> 'Sample color control with Alpha channel',
			'section' 		=> 'sample_color_controls_section',
			'input_attrs' 	=> array(
				'palette' 	=> array(
				
					'#000000',
					'#ffffff',
					'#dd3333',
					'#dd9933',
					'#eeee22',
					'#81d742',
					'#1e73be',
					'#8224e3',
				)
			),
		),
	));
	
	$this->set_customizer('sample_wpcolorpicker_alpha_color2', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_hex_rgba_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Alpha_Color_Control',
		'control' 	=> array(
		
			'label' 		=> 'WP ColorPicker Alpha Color Picker',
			'description' 	=> 'Sample color control with Alpha channel',
			'section' 		=> 'sample_color_controls_section',
			'input_attrs' 	=> array(
			
				'resetalpha'=> false,
				'palette' 	=> array(
				
					'rgba(99,78,150,1)',
					'rgba(67,78,150,1)',
					'rgba(34,78,150,.7)',
					'rgba(3,78,150,1)',
					'rgba(7,110,230,.9)',
					'rgba(234,78,150,1)',
					'rgba(99,78,150,.5)',
					'rgba(190,120,120,.5)',
				),
			),
		),
	));
	
	$this->set_customizer('sample_pill_checkbox', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_text_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Pill_Checkbox_Custom_Control',
		'control' 	=> array(
		
			'label' 		=> 'Pill Checkbox Control',
			'description' 	=> 'This is a sample Pill Checkbox Control',
			'section' 		=> 'sample_text_controls_section',
			'input_attrs' 	=> array(
				
				'sortable' 	=> false,
				'fullwidth' => false,
			),
			'choices' => array(
				
				'tiger' 	=> 'Tiger',
				'lion' 		=> 'Lion',
				'giraffe' 	=> 'Giraffe',
				'elephant' 	=> 'Elephant',
				'hippo' 	=> 'Hippo',
				'rhino' 	=> 'Rhino',
			)
		),
	));

	$this->set_customizer('sample_pill_checkbox2', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_text_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Pill_Checkbox_Custom_Control',
		'control' 	=> array(
		
			'label' 		=> 'Pill Checkbox Control',
			'description' 	=> 'This is a sample Sortable Pill Checkbox Control',
			'section' 		=> 'sample_text_controls_section',
			'input_attrs' 	=> array(
			
				'sortable' 	=> true,
				'fullwidth' => false,
			),
			'choices' => array(
			
				'captainamerica' 	=> 'Captain America',
				'ironman' 			=> 'Iron Man',
				'captainmarvel' 	=> 'Captain Marvel',
				'msmarvel' 			=> 'Ms. Marvel',
				'Jessicajones' 		=> 'Jessica Jones',
				'squirrelgirl'		=> 'Squirrel Girl',
				'blackwidow' 		=> 'Black Widow',
				'hulk' 				=> 'Hulk',
			)
		),
	));
	
	$this->set_customizer('sample_pill_checkbox3', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_text_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Pill_Checkbox_Custom_Control',
		'control' 	=> array(
		
			'label' 		=> 'Pill Checkbox Control',
			'description' 	=> 'This is a sample Sortable Fullwidth Pill Checkbox Control',
			'section' 		=> 'sample_text_controls_section',
			'input_attrs' 	=> array(
			
				'sortable' 	=> true,
				'fullwidth' => true,
			),
			'choices' => array(
			
				'date' 			=> 'Date',
				'author' 		=> 'Author',
				'categories' 	=> 'Categories',
				'tags'			=> 'Tags',
				'comments' 		=> 'Comments',
			)
		),
	));
	
	$this->set_customizer('sample_simple_notice', array(

		'setting' => array(
		
			'transport'		 	=> 'postMessage',
			'sanitize_callback' => 'ltple_theme_text_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Simple_Notice_Custom_control',
		'control' 	=> array(
		
			'label' 		=> 'Simple Notice Control',
			'description' 	=> 'This Custom Control allows you to display a simple title and description to your users. You can even include <a href="http://google.com" target="_blank">basic html</a>.',
			'section' 		=> 'sample_text_controls_section'
		),
	));
	
	$this->set_customizer('sample_dropdown_select2_control_single', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_text_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Dropdown_Select2_Custom_Control',
		'control' 	=> array(
		
			'label' 		=> 'Dropdown Select2 Control',
			'description' 	=> 'Sample Dropdown Select2 custom control (Single Select)',
			'section' 		=> 'sample_text_controls_section',
			'input_attrs' 	=> array(
			
				'placeholder' => 'Please select a state...',
				'multiselect' => false,
			),
			'choices' => array(
			
				'nsw' 	=> 'New South Wales',
				'vic' 	=> 'Victoria',
				'qld' 	=> 'Queensland',
				'wa' 	=> 'Western Australia',
				'sa' 	=> 'South Australia',
				'tas' 	=> 'Tasmania',
				'act' 	=> 'Australian Capital Territory',
				'nt' 	=> 'Northern Territory',
			)
		),
	));
	
	$this->set_customizer('sample_dropdown_select2_control_multi', array(

		'setting' => array(
		
			'transport'		 	=> 'refresh',
			'sanitize_callback' => 'ltple_theme_text_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Dropdown_Select2_Custom_Control',
		'control' 	=> array(
		
			'label' 		=> 'Dropdown Select2 Control',
			'description' 	=> 'Sample Dropdown Select2 custom control  (Multi-Select)',
			'section' 		=> 'sample_text_controls_section',
			'input_attrs' 	=> array(
			
				'multiselect' => true,
			),
			'choices' => array(
			
				'Antarctica' => array(
				
					'Antarctica/Casey' 			=> 'Casey',
					'Antarctica/Davis' 			=> 'Davis',
					'Antarctica/DumontDurville' => 'DumontDUrville',
					'Antarctica/Macquarie' 		=> 'Macquarie',
					'Antarctica/Mawson' 		=> 'Mawson',
					'Antarctica/McMurdo' 		=> 'McMurdo',
					'Antarctica/Palmer' 		=> 'Palmer',
					'Antarctica/Rothera' 		=> 'Rothera',
					'Antarctica/Syowa' 			=> 'Syowa',
					'Antarctica/Troll' 			=> 'Troll',
					'Antarctica/Vostok' 		=> 'Vostok',
				),
				'Atlantic' => array(
				
					'Atlantic/Azores' 			=> 'Azores',
					'Atlantic/Bermuda' 			=> 'Bermuda',
					'Atlantic/Canary' 			=> 'Canary',
					'Atlantic/Cape_Verde' 		=> 'Cape Verde',
					'Atlantic/Faroe' 			=> 'Faroe',
					'Atlantic/Madeira' 			=> 'Madeira',
					'Atlantic/Reykjavik' 		=> 'Reykjavik',
					'Atlantic/South_Georgia' 	=> 'South Georgia',
					'Atlantic/Stanley' 			=> 'Stanley',
					'Atlantic/St_Helena' 		=> 'St Helena',
				),
				'Australia' => array(
				
					'Australia/Adelaide' 		=> 'Adelaide',
					'Australia/Brisbane' 		=> 'Brisbane',
					'Australia/Broken_Hill' 	=> 'Broken Hill',
					'Australia/Currie' 			=> 'Currie',
					'Australia/Darwin' 			=> 'Darwin',
					'Australia/Eucla' 			=> 'Eucla',
					'Australia/Hobart' 			=> 'Hobart',
					'Australia/Lindeman' 		=> 'Lindeman',
					'Australia/Lord_Howe' 		=> 'Lord Howe',
					'Australia/Melbourne' 		=> 'Melbourne',
					'Australia/Perth' 			=> 'Perth',
					'Australia/Sydney' 			=> 'Sydney',
				)
			)
		),
	));
	
	$this->set_customizer('sample_dropdown_posts_control', array(

		'setting' => array(
		
			'transport'		 	=> 'postMessage',
			'sanitize_callback' => 'absint'
		),
		'class' 	=> 'LTPLE_Theme_Dropdown_Posts_Custom_Control',
		'control' 	=> array(
		
			'label' 		=> 'Dropdown Posts Control',
			'description' 	=> 'Sample Dropdown Posts custom control description',
			'section' 		=> 'sample_text_controls_section',
			'input_attrs' 	=> array(
			
				'posts_per_page' 	=> -1,
				'orderby' 			=> 'name',
				'order' 			=> 'ASC',
			),			
		),
	));	
	
	$this->set_customizer('sample_tinymce_editor', array(

		'setting' => array(
		
			'transport'		 	=> 'postMessage',
			'sanitize_callback' => 'wp_kses_post'
		),
		'class' 	=> 'LTPLE_Theme_TinyMCE_Custom_control',
		'control' 	=> array(
		
			'label' 		=> 'TinyMCE Control',
			'description' 	=> 'This is a TinyMCE Editor Custom Control',
			'section' 		=> 'sample_text_controls_section',
			'input_attrs' 	=> array(
			
				'toolbar1' 		=> 'bold italic bullist numlist alignleft aligncenter alignright link',
				'mediaButtons' 	=> true,
			)			
		),
		'partial'  => array(
			
			'selector' 				=> '.test-class',
			'container_inclusive' 	=> false,
			'render_callback' 		=> function() {
				
				return wpautop( LTPLE_Theme::get_mod('sample_tinymce_editor') );
			},
			'fallback_refresh' 		=> false,			
		),
	));
	
	$this->set_customizer('sample_google_font_select', array(

		'setting' => array(
		
			'sanitize_callback' => 'ltple_theme_google_font_sanitization'
		),
		'class' 	=> 'LTPLE_Theme_Google_Font_Select_Custom_Control',
		'control' 	=> array(
		
			'label' 		=> 'Google Font Control',
			'description' 	=> 'All Google Fonts sorted alphabetically',
			'section' 		=> 'sample_text_controls_section',
			'input_attrs' 	=> array(
			
				'font_count' 	=> 'all',
				'orderby' 		=> 'alpha',
			),			
		),
	));