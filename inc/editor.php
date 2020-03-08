<?php
/**
 * LTPLE Editor Class
 *
 */

class LTPLE_Editor {
	
	private static $_instance = null;
	
	var $_file 	= null;
	var $_token = 'ltple-theme-editor';
	
	public function __construct( $file = '' ){
		
		$this->_file = $file;
		
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
	}
	
	public function enqueue_scripts () {
		
		if( isset($_GET['preview']) && $_GET['preview'] == 'ltple' ){
			
			wp_enqueue_script('jquery-ui-sortable');
		
			wp_register_script( $this->_token . '-script', '', array( 'jquery' ) );
			wp_enqueue_script( $this->_token . '-script' );
		
			wp_add_inline_script( $this->_token . '-script', $this->get_script());
		}
	}
	
	public function get_script(){
		
		$script = ';(function($){

			$(document).ready(function(){
				
					
				$(\'a\').click(function(e) {
					
					e.preventDefault();
				});

			});			
					
		})(jQuery);';
		
		return $script;		
	}
	
}