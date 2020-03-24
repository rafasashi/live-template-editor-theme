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
	var $layers		= array();
	var $content	= null;
	
	public function __construct( $parent ){
		
		$this->parent 		= $parent;
		
		$this->_version 	= $this->parent->_version;
		
		$this->assets_url 	= $this->parent->assets_url;
		
		add_action('wp_enqueue_scripts', array( $this, 'enqueue_preview_scripts' ),10);
	
		add_filter('init', array($this,'init_editor'),9999999999999999);

		add_action('load-post.php', array($this,'get_admin_editor'));	
		
		// removes admin color scheme options
		
		remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
	
		add_filter('the_content', array( $this, 'add_editor_markup' ),999999);
	
		add_filter( 'ltple_right_editor_navbar', array( $this, 'get_right_navbar' ),0);			
		
		add_filter( 'template_redirect', array( $this, 'get_editor' ),1);	
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
		
	public function get_layer_id(){
		
		$id = 0;
		
		if(is_admin()){
			
			if( isset($_GET['post']) ){
			
				$id = $_GET['post'];
			}
		}
		else{
			
			$id = apply_filters('ltple_layer_id',$id);
		}
		
		return $id;
	}
	
	public function is_editable( $post_id = 0 ){
		
		$is_editable = false;
		
		if( $preview_url = get_preview_post_link($post_id)){
			
			return true;
		}
		
		return apply_filters('ltple_layer_is_editable',$post_id,$is_editable);
	}
	
	public function get_layer($layer_id=null){
		
		$layer = null;
		
		if( is_null($layer_id) ){
			
			$layer_id = $this->get_layer_id();
		}
		
		if( !isset($this->layers[$layer_id]) ){
		
			if( $layer = get_post($layer_id) ){
				
				$layer->token	= isset($_GET['_']) && is_numeric($_GET['_']) ? intval($_GET['_']) : time();
				
				$layer->key		= md5('layer' . $layer->ID . $layer->token);
				
				$layer->output 	= apply_filters('ltple_layer_output','hosted',$layer);

				$layer->is_editable = $this->is_editable($layer->ID);
				
				$layer->is_media 	= $this->is_media($layer);

				$layer->urls = array(
				
					'edit' 		=> $this->get_edit_url($layer->ID),
					'settings' 	=> $this->get_settings_url($layer->ID),
					'preview' 	=> get_preview_post_link($layer->ID),
					'backend'	=> get_edit_post_link($layer->ID),
				);
			}
		
			$this->layers[$layer_id] = apply_filters('ltple_current_layer',$layer);
		}
		
		return $this->layers[$layer_id];
	}
	
	public function is_media($post){
		
		$types = self::get_media_types();
		
		return ( isset( $types[$post->post_type] ) ? true : false );	
	}
	
	public static function get_media_types(){
		
		return apply_filters('ltple_media_types', array(
					
			'attachment' 	=> 'Uploaded Image',
			'user-image' 	=> 'External Image',
			'default-image' => 'Default Image',
		));
	}
	
	public function init_editor(){
		
		if( !is_admin() ){
			
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_editor_styles' ), 99999 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ), 99999 );
		
			do_action('ltple_editor_action');
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
			
			// editor action
			
			if( isset($_POST['postAction']) ){
				
				$post_id = $this->get_layer_id();
				
				if( $post_id > 0 && current_user_can( 'edit_post', $post_id ) ){
				
					if( $_POST['postAction'] == 'save' ){
						
						if( isset($_POST['postContent']) ){
							
							$is_static		= true;
							
							$post_content 	= urldecode(base64_decode($_POST['postContent']));
							$post_content 	= LTPLE_Editor::sanitize_content( $post_content, $is_static );
						
							$post_css 		= ( !empty($_POST['postCss']) 		? stripcslashes( $_POST['postCss'] ) 		 : '' );
							$post_js 		= ( !empty($_POST['postJs']) 		? stripcslashes( $_POST['postJs'] ) 		 : '' );
							$post_settings 	= ( !empty($_POST['postSettings']) 	? json_decode(stripcslashes($_POST['postSettings']),true): '' );
							
							update_post_meta($post_id, 'layerContent', $post_content);
							
							update_post_meta($post_id, 'layerCss', $post_css);
							
							update_post_meta($post_id, 'layerJs', $post_js);
							
							update_post_meta($post_id, 'layerSettings', $post_settings);
						
							http_response_code(200);
							echo 'Template successfully saved!';
							exit;
						}
						
						http_response_code(404);
						echo 'Content missing';
						exit;
					}
					
					http_response_code(404);
					echo 'Wrong action';
					exit;
				}
				
				http_response_code(404);
				echo 'Permission denied';
				exit;
			}
			elseif( $layer = $this->get_layer() ){
				
				if(!empty($_GET['lk']) ){
					
					if( $layer->is_editable ){
					
						// backend editor iframe

						LTPLE_Editor::get_remote_script($layer,array(
							
							'key'		=> $_GET['lk'],
							'user'		=> 'admin',
							'is_local' 	=> true,
							'plan_url'	=> '', // TODO license provider url
						));
					}
					else{
						
						echo '<div style="background:#fbfbfb;padding:15px;color:#888;font-family:monospace;">';
						
							echo 'This post is not editable...';
						
						echo '</div>';
						
						exit;
					}
				}
			}
		}
		elseif( !empty($_GET['post']) && $this->is_editable($_GET['post']) ){
			
			if( $post = get_post($_GET['post'])){
				
				if( $this->content = get_post_meta($post->ID,'layerContent',true)){
					
					// remove local page support
			
					remove_post_type_support($post->post_type,'editor');
					remove_post_type_support($post->post_type,'revisions');
				}
				
				// add edit button
			
				add_action('edit_form_after_title', array($this,'add_edit_button'),0);
			}
		}
	}
	
	public function add_edit_button(){
		
		if( !empty($this->content) ){
			
			echo'<style>
			
				#elementor-switch-mode{display:none!important;}
				#ltple-start-wrapper{height:400px;padding-top:20%;text-align:center;margin:15px 0 0 0;background:#fafafa;}
				
			</style>';
		
			echo'<div id="ltple-start-wrapper">';
		}
		
		echo'<div id="ltple-start" style="margin:20px 0px;">';
			
			echo '<a href="' . add_query_arg( 'action', 'ltple') . '" target="_self" class="button button-primary button-hero">';
				
				echo 'Live Template Editor';
				
			echo '</a>';
		
		echo'</div>';
		
		if( !empty($this->content) ){
			
			echo'</div>';
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
	
	public function get_iframe_url($layer){
		
		$url = '';

		if( is_admin() ){
			
			$url = add_query_arg(array(
			
				'lk' =>   $layer->key,
				'_'  =>   $layer->token,
				
			),$this->get_edit_url($layer->ID));
		}
		else{
			
			$url = apply_filters('ltple_editor_iframe_url',$url,$layer);
		}
	
		return $url;
	}
	
	public static function get_image_proxy_url($url=''){
		
		return trailingslashit( get_template_directory_uri() ) . 'ltple/image-proxy.php?url=' . ( !empty($url) ? urlencode($url) : '' );
	}
	
	public function get_dashboard_url(){
		
		$url = is_admin() ? get_admin_url() : get_home_url();
		
		return apply_filters('ltple_editor_dashboard_url',$url);		
	}
	
	public function get_settings_url($post_id){
		
		if( is_admin() ){
			
			return get_admin_url() . 'post.php?post=' . $post_id . '&action=edit';
		}
		else{
			
			return apply_filters('ltple_editor_settings_url',$post_id);		
		}
	}
	
	public function get_edit_url($post_id){
		
		if( is_admin() ){
			
			return get_admin_url() . 'post.php?post=' . $post_id . '&action=ltple';
		}
		else{
			
			return apply_filters('ltple_editor_edit_url',$post_id);		
		}
	}
	
	public function get_js_settings($layer){
		
		$js = '';
		
		//include output
		
		$js .= ' var layerOutput = "' . $layer->output . '";' . PHP_EOL;
		
		//include image proxy
		
		$js .= ' var imgProxy = "' . self::get_image_proxy_url() . '";' . PHP_EOL;
		
		if( is_admin() ){
			
			if( $layer->post_type == 'attachment' ){
			
				$attachment_url = wp_get_attachment_url($layer->ID );
				
				$js .= ' var layerImageTpl = "' . self::get_image_proxy_url($attachment_url) . '";' . PHP_EOL;
			}
			else{
				
				// url based preview
				
				$preview = add_query_arg(array(
					
					'preview' => 'ltple',
					
				),get_preview_post_link($layer->ID));
				
				$js .= ' var layerUrl		= "' . $preview . '";' . PHP_EOL;

				$js .= ' var pageDef 		= {};'		.PHP_EOL;
				$js .= ' var disableReturn 	= true;'	.PHP_EOL;
				$js .= ' var autoWrapText 	= false;'	.PHP_EOL;
				$js .= ' var enableIcons 	= false;'	.PHP_EOL;
			}
			
			return $js;
		}
		else{
			
			return apply_filters('ltple_editor_js_settings',$js,$layer);
		}
	}
	
	public function get_right_navbar(){

		if( $elemLibraries = $this->get_elements() ){
			
			echo'<style>'.PHP_EOL;

				echo'#dragitemslistcontainer {
					
					margin: 0;
					padding: 0;
					width: 100%;
					display:inline-block;
				}

				#dragitemslistcontainer li {
					
					float: left;
					position: relative;
					text-align: center;
					list-style: none;
					cursor: move;
					cursor: grab;
					cursor: -moz-grab;
					cursor: -webkit-grab;
				}

				#dragitemslistcontainer li:active {
					cursor: grabbing;
					cursor: -moz-grabbing;
					cursor: -webkit-grabbing;
				}

				#dragitemslistcontainer span {
					
					float: left;
					position: absolute;
					left: 0;
					right: 0;
					background: rgba(52, 87, 116, 0.49);
					color: #fff;
					font-weight: bold;
					padding: 15px 5px;
					font-size: 16px;
					line-height: 25px;
					margin: 48px 4px 0 4px;
				}

				#dragitemslistcontainer li img {
					margin:3px 2px;
				}';		

			echo'</style>'.PHP_EOL;							
			
			echo '<button style="margin-left:2px;margin-right:2px;border:none;background:#9C27B0;" id="elementsBtn" class="btn btn-sm pull-left" href="#" data-toggle="dialog" data-target="#LiveTplEditorDndDialog" data-height="300" data-width="500" data-resizable="false">Insert</button>';
	
			echo '<div id="LiveTplEditorDndDialog" title="Elements library" style="display:none;">';
			echo '<div id="LiveTplEditorDndPanel">';
			
				echo '<div id="dragitemslist">';
					
					$list = [];
					
					foreach( $elemLibraries as $elements ){
				
						if( !empty($elements['name']) ){
							
							foreach( $elements['name'] as $e => $name ){
								
								if( !empty($elements['type'][$e]) ){
								
									$type = $elements['type'][$e];
									
									$content = str_replace( array('\\"','"',"\\'"), "'", $elements['content'][$e] );
									
									$drop = ( !empty($elements['drop'][$e]) ? $elements['drop'][$e] : 'out' );
									
									if( !empty($content) ){
									
										$item = '<li draggable="true" data-drop="' . $drop . '" data-insert-html="' . $content . '">';
										
											$item .= '<span>'.$name.'</span>';
										
											if( !empty($elements['image'][$e]) ){
										
												$item .= '<img title="'.$name.'" height="150" src="' . $elements['image'][$e] . '" />';
											}
											else{
												
												$item .= '<img title="'.$name.'" height="150" src="' . $this->assets_url . 'images/default-element.jpg" />';
												
												//$item .= '<div style="height: 115px;width: 150px;background: #afcfff;border: 4px solid #fff;"></div>';
											}
										$item .= '</li>';
										
										$list[$type][] = $item;
									}
								}
							}
						}
					}
						
					//echo'<div class="library-content">';
							
						echo'<ul class="nav nav-pills" role="tablist">';

						$active=' class="active"';
						
						foreach($list as $type => $items){
							
							echo'<li role="presentation"'.$active.'><a href="#' . $type . '" aria-controls="' . $type . '" role="tab" data-toggle="tab">'.ucfirst(str_replace(array('-','_'),' ',$type)).' <span class="badge">'.count($list[$type]).'</span></a></li>';
							
							$active='';
						}							

						echo'</ul>';
						
					//echo'</div>';

					echo'<div id="dragitemslistcontainer" class="tab-content row">';
						
						$active=' active';
					
						foreach($list as $type => $items){
							
							echo'<ul role="tabpanel" class="tab-pane'.$active.'" id="' . $type . '">';
							
							foreach($items as $item){

								echo $item;
							}
							
							echo'</ul>';
							
							$active='';
						}
						
					echo'</div>';
				
				echo '</div>';
				
			echo '</div>';
			echo '</div>';				
		}
	}
	
	public function get_elements(){
		
		$elemLibraries = array();
		
		return apply_filters('ltple_editor_elements',$elemLibraries);
	}
	
	public function get_editor(){
		
		if( $layer = $this->get_layer() ){
		
			do_action('ltple_editor_frontend',$layer);
		}
	}
	
	public function add_editor_markup($content){
		
		if( strpos($content,'<ltple-layer>') === false ){
			
			$content = '<ltple-layer>' . $content . '</ltple-layer>';
		}
		
		return $content;
	}

	public static function sanitize_content($str,$is_hosted=false){
        
		$str = stripslashes($str);
		
		$str = wp_kses_decode_entities($str);
		
		$str = wp_kses_no_null($str);
		
		$str = wp_kses_normalize_entities($str);

		$str = self::sanitize_ms_word($str);
		
		$str = str_replace(array('cursor: pointer;','data-element_type="video.default"'),'',$str);

		$str = str_replace(array('<body','</body>','src=" ','href=" ','#@'),array('<div','</div>','src="','href="','@'),$str);
		
		//$str = html_entity_decode(stripslashes($str));
		
		//$str = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $str);
		
		$str = preg_replace( array(
		
				//'/<iframe(.*?)<\/iframe>/is',
				'/<title(.*?)<\/title>/is',
				'/<!doctype(.*?)>/is',
				'/<link(.*?)>/is',
				//'/<body(.*?)>/is',
				//'/<\/body>/is',
				//'/<head(.*?)>/is',
				//'/<\/head>/is',				
				'/<html(.*?)>/is',
				'/<\/html>/is'
			), 
			'', $str
		);		
		
		if( !$is_hosted ){
		
			$str = preg_replace( array(
			
					'/<pre(.*?)<\/pre>/is',
					'/<frame(.*?)<\/frame>/is',
					'/<frameset(.*?)<\/frameset>/is',
					'/<object(.*?)<\/object>/is',
					'/<script(.*?)<\/script>/is',
					'/<style(.*?)<\/style>/is',
					'/<embed(.*?)<\/embed>/is',
					'/<applet(.*?)<\/applet>/is',
					'/<meta(.*?)>/is',
					'/onload="(.*?)"/is',
					'/onunload="(.*?)"/is',
				), 
				'', $str
			);
		}
		
		return $str;
	}
	
	public static function sanitize_ms_word( $string ){
		
		// Convert microsoft special characters
		
        $map = array(
		
            '33' => '!', '34' => '"', '35' => '#', '36' => '$', '37' => '%', '38' => '&', '39' => "'", '40' => '(', '41' => ')', '42' => '*',
            '43' => '+', '44' => ',', '45' => '-', '46' => '.', '47' => '/', '48' => '0', '49' => '1', '50' => '2', '51' => '3', '52' => '4',
            '53' => '5', '54' => '6', '55' => '7', '56' => '8', '57' => '9', '58' => ':', '59' => ';', '60' => '<', '61' => '=', '62' => '>',
            '63' => '?', '64' => '@', '65' => 'A', '66' => 'B', '67' => 'C', '68' => 'D', '69' => 'E', '70' => 'F', '71' => 'G', '72' => 'H',
            '73' => 'I', '74' => 'J', '75' => 'K', '76' => 'L', '77' => 'M', '78' => 'N', '79' => 'O', '80' => 'P', '81' => 'Q', '82' => 'R',
            '83' => 'S', '84' => 'T', '85' => 'U', '86' => 'V', '87' => 'W', '88' => 'X', '89' => 'Y', '90' => 'Z', '91' => '[', '92' => '\\',
            '93' => ']', '94' => '^', '95' => '_', '96' => '`', '97' => 'a', '98' => 'b', '99' => 'c', '100'=> 'd', '101'=> 'e', '102'=> 'f',
            '103'=> 'g', '104'=> 'h', '105'=> 'i', '106'=> 'j', '107'=> 'k', '108'=> 'l', '109'=> 'm', '110'=> 'n', '111'=> 'o', '112'=> 'p',
            '113'=> 'q', '114'=> 'r', '115'=> 's', '116'=> 't', '117'=> 'u', '118'=> 'v', '119'=> 'w', '120'=> 'x', '121'=> 'y', '122'=> 'z',
            '123'=> '{', '124'=> '|', '125'=> '}', '126'=> '~', '127'=> ' ', '128'=> '&#8364;', '129'=> ' ', '130'=> ',', '131'=> ' ', '132'=> '"',
            '133'=> '.', '134'=> ' ', '135'=> ' ', '136'=> '^', '137'=> ' ', '138'=> ' ', '139'=> '<', '140'=> ' ', '141'=> ' ', '142'=> ' ',
            '143'=> ' ', '144'=> ' ', '145'=> "'", '146'=> "'", '147'=> '"', '148'=> '"', '149'=> '.', '150'=> '-', '151'=> '-', '152'=> '~',
            '153'=> ' ', '154'=> ' ', '155'=> '>', '156'=> ' ', '157'=> ' ', '158'=> ' ', '159'=> ' ', '160'=> ' ', '161'=> '¡', '162'=> '¢',
            '163'=> '£', '164'=> '¤', '165'=> '¥', '166'=> '¦', '167'=> '§', '168'=> '¨', '169'=> '©', '170'=> 'ª', '171'=> '«', '172'=> '¬',
            '173'=> '­', '174'=> '®', '175'=> '¯', '176'=> '°', '177'=> '±', '178'=> '²', '179'=> '³', '180'=> '´', '181'=> 'µ', '182'=> '¶',
            '183'=> '·', '184'=> '¸', '185'=> '¹', '186'=> 'º', '187'=> '»', '188'=> '¼', '189'=> '½', '190'=> '¾', '191'=> '¿', '192'=> 'À',
            '193'=> 'Á', '194'=> 'Â', '195'=> 'Ã', '196'=> 'Ä', '197'=> 'Å', '198'=> 'Æ', '199'=> 'Ç', '200'=> 'È', '201'=> 'É', '202'=> 'Ê',
            '203'=> 'Ë', '204'=> 'Ì', '205'=> 'Í', '206'=> 'Î', '207'=> 'Ï', '208'=> 'Ð', '209'=> 'Ñ', '210'=> 'Ò', '211'=> 'Ó', '212'=> 'Ô',
            '213'=> 'Õ', '214'=> 'Ö', '215'=> '×', '216'=> 'Ø', '217'=> 'Ù', '218'=> 'Ú', '219'=> 'Û', '220'=> 'Ü', '221'=> 'Ý', '222'=> 'Þ',
            '223'=> 'ß', '224'=> 'à', '225'=> 'á', '226'=> 'â', '227'=> 'ã', '228'=> 'ä', '229'=> 'å', '230'=> 'æ', '231'=> 'ç', '232'=> 'è',
            '233'=> 'é', '234'=> 'ê', '235'=> 'ë', '236'=> 'ì', '237'=> 'í', '238'=> 'î', '239'=> 'ï', '240'=> 'ð', '241'=> 'ñ', '242'=> 'ò',
            '243'=> 'ó', '244'=> 'ô', '245'=> 'õ', '246'=> 'ö', '247'=> '÷', '248'=> 'ø', '249'=> 'ù', '250'=> 'ú', '251'=> 'û', '252'=> 'ü',
            '253'=> 'ý', '254'=> 'þ', '255'=> 'ÿ'
        );

        $search = array();
        $replace = array();

        foreach ($map as $s => $r) {
			
            $search[] = chr((int)$s);
            $replace[] = $r;
        }

		$string = str_replace($search, $replace, $string);

		// Remove any non-ascii character
		
		$string = preg_replace('/[^\x20-\x7E]*/','', $string);
		
		return $string;
	}
	
	public static function get_remote_script($layer,$args=array(
		
		'key'		=> '',
		'user' 		=> '',
		'plan_url'	=> '',
		'is_local' 	=> true,
	)){
		
		if( $layer->key != $args['key'] ){
			
			echo'<div style="background:#fbfbfb;padding:15px;color:#888;font-family:monospace;">';
				
				echo'Malformed request...';
			
			echo'</div>';
		}
		elseif( $server_url = self::get_remote_script_url() ){
			
			// get request url
			
			$ref = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
					
			$ref_key = md5( 'ref' . $ref . $layer->token . $args['user'] );
					
			$iframe_key = md5( 'iframe' . $layer->key . $ref_key . $layer->token . $args['user'] );
				
			$request_url = add_query_arg(array(
					
				'uri' 	=> $layer->ID,
				'lk'	=> $layer->key,
				'lo'	=> $layer->output,
				'll'	=> $args['is_local'] ? md5( 'true' . $layer->ID ) : md5( 'false' . $layer->ID ),
				'ld'	=> ( defined('REW_DEV_SERVER') && $_SERVER['HTTP_HOST'] == REW_DEV_SERVER ) ? md5( 'true' . $layer->ID ) : md5( 'false' . $layer->ID ),
				'ow'	=> $args['user'],
				'pu'	=> urlencode($args['plan_url']),
				'ref'	=> urlencode($ref), 
				'rk'	=> $ref_key,
				'ik'	=> $iframe_key,
				'_'		=> $layer->token,
			
			),$server_url);
			
			// get request ip

			$request_ip = '';
	
			foreach(array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
				
				if( array_key_exists($key, $_SERVER) === true ){
					
					foreach(array_map('trim', explode(',', $_SERVER[$key])) as $ip){
						
						if( filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false ){
							
							$request_ip = $ip;
							
							break(2);
						}
					}
				}
			}
			
			// get server headers

			$headers = array(); 
			
			foreach ($_SERVER as $name => $value){ 
				
				if (substr($name, 0, 5) == 'HTTP_'){ 
					
					$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
				} 
			}

			$headers['Host']				= parse_url($server_url, PHP_URL_HOST);
			$headers['X-forwarded-Host']	= $_SERVER['HTTP_HOST'];
			$headers['X-forwarded-Server']	= $_SERVER['HTTP_HOST'];
			$headers['X-forwarded-For']		= $request_ip;
			$headers['X-forwarded-Key']		= md5('remote'.$request_ip);
			$headers['X-forwarded-User']	= $args['user'];
			
			// get request headers
			
			$request_headers = [];
			
			foreach ($headers as $key => $value) {
				
				$request_headers[] = $key . ': ' . $value;
			}
			
			$ch = curl_init($request_url);
			
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $_SERVER['REQUEST_METHOD']);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
			curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents('php://input'));
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			$response = curl_exec($ch);

			if($response === false) {
				
				header('HTTP/1.1 502 Bad Gateway');
				header('Content-Type: text/plain');
				
				echo 'Upstream host did not respond.';
			} 
			else {
				
				$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					
				if($httpcode < 400){
				
					// get response headers
				
					$header_length = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
					$response_headers = explode("\n", substr($response, 0, $header_length));
					
					foreach ($response_headers as $i => $header) {

						if($header = trim($header)){
							
							if(strpos($header,'Location')!==0){
								
								//header($header);
							}
							else{
								
								echo 'This page moved permanently...';
								curl_close($ch);
								exit;
							}
						}
					}

					// get response body
					
					$response_body = substr($response, $header_length);

					$response_body = gzdecode($response_body);			
					
					$response_body = apply_filters('ltple_editor_script',$response_body);
				
					echo do_shortcode($response_body);
				}
				else{
					
					echo 'This page doesn\'t exists...';
				}
			}

			curl_close($ch);
		}
		else{
			
			echo'<div style="background:#fbfbfb;padding:15px;color:#888;font-family:monospace;">';
				
				echo'No editor server found...';
			
			echo'</div>';
		}
		
		flush();
		exit;
		die;
	}
	
	public static function get_remote_script_url(){
		
		$url = ''; // TODO License provider url
		
		return apply_filters('ltple_remote_script_url',$url);
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
