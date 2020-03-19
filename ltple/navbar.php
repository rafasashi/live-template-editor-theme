<?php 

$theme = LTPLE_Theme::instance();

// get navbar

echo'<div id="editor-nav" class="row" style="box-shadow:inset 0 -1px 10px -6px rgba(0,0,0,0.75);background: #eeeeee;padding: 8px 0;margin: 0;border-bottom: 1px solid #ddd;position: absolute;top: 0;left: 0;right: 0;z-index: 10;">';

	echo'<div class="col-xs-6 col-sm-4" style="z-index:10;padding:0 8px;">';			
		
		echo'<div class="pull-left">';

			echo'<a href="' . $theme->editor->get_dashboard_url() .'" title="Dashboard">';
					
				echo'<img src="'.get_site_icon_url().'" style="background: #ffffff;border-radius: 100px;padding: 1px;height: 30px;width: 30px;margin: -2px 15px 0 5px;box-shadow: rgb(138, 138, 138) 1px 1px 5px;"/>';
				
			echo'</a>';
			
		echo'</div>';			
		
		do_action('ltple_left_editor_navbar');
		
	echo'</div>';
	
	echo'<div class="col-xs-6 col-sm-8 text-right" style="padding:0 5px;">';
		
		do_action('ltple_right_editor_navbar');
	
	echo'</div>';
	
echo'</div>';

do_action('ltple_message');
