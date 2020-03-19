<?php
/**
 * LTPLE Editor Class
 *
 */

class LTPLE_Editor {
	
	private static $_instance = null;
	
	var $parent 	= null;
	var $_version 	= null;
	var $assets_url = null;
	var $_token 	= 'ltple';
	
	public function __construct( $parent ){
		
		$this->parent 		= $parent;
		
		$this->_version 	= $this->parent->_version;
		
		$this->assets_url 	= $this->parent->assets_url;
		
		add_action('wp_enqueue_scripts', array( $this, 'enqueue_preview_scripts' ),10);
	
		add_action('edit_form_after_title', array($this,'add_edit_button'),0);	
	
		add_filter('init', array($this,'init_editor'),9999999999999999);
		
		add_action('load-post.php', array($this,'get_admin_editor'));	
		
		// removes admin color scheme options
		
		remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
	
		add_filter('the_content', array( $this, 'add_editor_markup' ),999999);
	}
	
	public function enqueue_preview_scripts() {
		
		if( isset($_GET['preview']) && $_GET['preview'] == 'ltple' ){
			
			wp_enqueue_script('jquery-ui-sortable');
		
			wp_register_script( $this->_token . '-preview-script', '', array( 'jquery' ) );
			wp_enqueue_script( $this->_token . '-preview-script' );
		
			wp_add_inline_script( $this->_token . '-preview-script', $this->get_preview_script());
		}
	}
	
	public function get_preview_script(){
		
		$script = ';(function($){

			$(document).ready(function(){
				
					
				$(\'a\').click(function(e) {
					
					e.preventDefault();
				});

			});			
					
		})(jQuery);';
		
		return $script;		
	}
	
	public function add_edit_button(){
		
		if( !empty($_GET['post']) && $this->is_editable($_GET['post']) ){
		
			echo'<div style="margin:20px 0px;">';
				
				echo '<a href="' . add_query_arg( 'action', 'ltple') . '" target="_self" class="button button-primary button-hero">';
					
					echo 'Live Template Editor';
					
				echo '</a>';
			
			echo'</div>';
		}
	}
	
	public function is_editable( $post_id = 0 ){
		
		$is_editable = false;
		
		if( $preview_url = get_preview_post_link($post_id)){
			
			$is_editable = true;
		}
		
		return apply_filters('ltple_is_editable',$is_editable,$post_id);
	}
	
	public function init_editor(){
		
		if( !is_admin() ){
			
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_editor_styles' ), 99999 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ), 99999 );
		}
		elseif( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'ltple' ){
		
			// remove admin bar

			add_filter( 'show_admin_bar', '__return_false' );
			
			wp_deregister_script('admin-bar');
			wp_deregister_style('admin-bar');
			
			// Remove all WordPress actions
			
			remove_all_actions( 'wp_head' );
			remove_all_actions( 'wp_print_styles' );
			remove_all_actions( 'wp_print_head_scripts' );
			remove_all_actions( 'wp_footer' );

			// Handle wp_head
			
			add_action( 'wp_head', 'wp_enqueue_scripts', 1 );
			add_action( 'wp_head', 'wp_print_styles', 8 );
			add_action( 'wp_head', 'wp_print_head_scripts', 9 );
			add_action( 'wp_head', 'wp_site_icon' );

			// Handle wp_footer
			
			add_action( 'wp_footer', 'wp_print_footer_scripts', 20 );
			add_action( 'wp_footer', 'wp_auth_check_html', 30 );

			// Handle wp_enqueue_scripts
			
			remove_all_actions( 'wp_enqueue_scripts' );
		}
	}
	
	public function get_admin_editor(){
		
		if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'ltple' ){

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_editor_styles' ), 10 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ), 10 );
			
			$this->parent->enqueue_main_style();
			
			include($this->parent->dir . '/ltple/editor.php');
			exit;
		}
	}
	
	public function enqueue_editor_styles(){
		
		wp_register_style( $this->_token . '-jquery-ui', esc_url( $this->assets_url ) . 'css/jquery-ui.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-jquery-ui' );		
	
		wp_register_style( $this->_token . '-bootstrap-css', esc_url( $this->assets_url ) . 'css/bootstrap.min.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-bootstrap-css' );				
	
		wp_register_style( $this->_token . '-editor-ui', esc_url( $this->assets_url ) . 'css/editor-ui.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-editor-ui' );		
		
		wp_register_style( $this->_token . '-bootstrap-table', esc_url( $this->assets_url ) . 'css/bootstrap-table.min.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-bootstrap-table' );
		
		wp_register_style( $this->_token . '-toggle-switch', esc_url( $this->assets_url ) . 'css/toggle-switch.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-toggle-switch' );
	
	}
	
	public function enqueue_editor_scripts(){
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-dialog');
		
		wp_register_script($this->_token . '-bootstrap-js', esc_url( $this->assets_url ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->_version);
		wp_enqueue_script( $this->_token . '-bootstrap-js' );
	
		wp_register_script($this->_token . '-lazyload', esc_url( $this->assets_url ) . 'js/lazyload.min.js', array( 'jquery' ), $this->_version);
		wp_enqueue_script( $this->_token . '-lazyload' );

		wp_register_script($this->_token . '-bootstrap-table', esc_url( $this->assets_url ) . 'js/bootstrap-table.min.js', array( 'jquery',$this->_token . '-bootstrap-js' ), $this->_version);
		wp_enqueue_script( $this->_token . '-bootstrap-table' );

		wp_register_script($this->_token . '-bootstrap-table-mobile', esc_url( $this->assets_url ) . 'js/bootstrap-table-mobile.min.js', array( 'jquery',$this->_token . '-bootstrap-js', $this->_token . '-bootstrap-table' ), $this->_version);
		wp_enqueue_script( $this->_token . '-bootstrap-table-mobile' );		

		wp_register_script($this->_token . '-bootstrap-table-filter-control', esc_url( $this->assets_url ) . 'js/bootstrap-table-filter-control.min.js', array( 'jquery',$this->_token . '-bootstrap-js',$this->_token . '-bootstrap-table' ), $this->_version);
		wp_enqueue_script( $this->_token . '-bootstrap-table-filter-control' );

		wp_register_script($this->_token . '-editor-ui-js', esc_url( $this->assets_url ) . 'js/editor-ui.js', array('jquery','jquery-ui-core','jquery-ui-dialog',$this->_token . '-bootstrap-js', $this->_token . '-bootstrap-table', $this->_token . '-lazyload' ), $this->_version);
		wp_enqueue_script( $this->_token . '-editor-ui-js' );	
	}
	
	public function get_iframe_url(){
		
		$iframe_url = '';
		
		return apply_filters('ltple_editor_iframe_url',$iframe_url);
	}
	
	public function get_dashboard_url(){
		
		$dashboard_url = is_admin() ? get_admin_url() : get_home_url();
		
		return apply_filters('ltple_editor_dashboard_url',$dashboard_url);		
	}
	
	public function get_js_settings(){
		
		$js = '';
			
		return apply_filters('ltple_editor_js_settings',$js);
	}
	
	public function add_editor_markup($content){
		
		if( strpos($content,'<ltple-layer>') === false ){
			
			$content = '<ltple-layer>' . $content . '</ltple-layer>';
		}
		
		return $content;
	}
	
	public static function instance ( $parent ) {
		
		if ( is_null( self::$_instance ) ) {
			
			self::$_instance = new self( $parent );
		}
		
		return self::$_instance;
		
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()

}
