<?php 

$theme = LTPLE_Theme();

// get navbar

echo'<div id="editor-nav" class="row" style="box-shadow:inset 0 -1px 10px -6px rgba(0,0,0,0.75);background: #eeeeee;padding: 0;height: 45px;margin: 0;border-bottom: 1px solid #ddd;position: absolute;top: 0;left: 0;right: 0;z-index: 10;">';

	echo'<div class="col-xs-6 col-sm-4" style="z-index:10;padding:0 8px;">';			
		
		echo'<div class="pull-left" style="padding:8px 0;">';

			echo'<a href="' . $theme->editor->get_dashboard_url() .'" title="Dashboard">';
					
				echo'<img src="'.get_site_icon_url().'" style="background: #ffffff;border-radius: 100px;padding: 1px;height: 30px;width: 30px;margin: -2px 15px 0 5px;box-shadow: rgb(138, 138, 138) 1px 1px 5px;"/>';
				
			echo'</a>';
			
		echo'</div>';
		
		/*
		echo'<div class="hidden-xs" style="font-size: 21px;padding: 8px 0;font-weight: bold;">';
		
			echo'Live Template Editor';
			
		echo'</div>';	
		*/
		
		do_action('ltple_left_editor_navbar');
		
	echo'</div>';
	
	echo'<div class="col-xs-6 col-sm-8 text-right" style="padding:8px 5px;">';
		
		if( $layer = $theme->editor->get_layer() ){

			if( !$layer->is_media ){
				
				if( $layer->is_editable && !empty($layer->urls['edit']) ){
				
					// save button

					echo'<form style="display:inline-block;margin:0;" target="_parent" action="' . $layer->urls['edit'] . '" id="savePostForm" method="post">';
						
						echo'<input type="hidden" name="postContent" id="postContent" value="">';
						echo'<input type="hidden" name="postCss" id="postCss" value="">';
						echo'<input type="hidden" name="postJs" id="postJs" value="">';
						echo'<input type="hidden" name="postAction" id="postAction" value="save">';
						echo'<input type="hidden" name="postSettings" id="postSettings" value="">';
						 
						wp_nonce_field( 'user_layer_nonce', 'user_layer_nonce_field' );
						
						echo'<input type="hidden" name="submitted" id="submitted" value="true">';
						
						echo'<div id="navLoader" style="float:left;margin-right:10px;display:none;"><img src="' . $theme->assets_url . 'images/loader.gif" style="height: 20px;"></div>';				
						
						echo'<button style="border:none;" class="btn btn-sm btn-success" type="button" id="saveBtn">Save</button>';
						
					echo'</form>';
				}
				
				if( !empty($layer->urls['settings']) ){
					
					// settings button
					
					echo'<a href="' . $layer->urls['settings'] . '" style="background-color:#58cac5;color:#fff;border:none;margin-left:2px;" class="btn btn-sm" type="button">Settings</a>';
				}
				
				if( !empty($layer->urls['preview']) ){
					
					// view button 

					echo '<a target="_blank" class="btn btn-sm" href="' . $layer->urls['preview'] . '" style="margin-left:2px;margin-right:2px;border:none;color: #fff;background-color: rgb(189, 120, 61);">View</a>';
				}
			}
			
			do_action('ltple_right_editor_navbar',$layer);
				
			if( !$layer->is_media && $layer->is_editable ){
					
				echo'<div style="margin:0 2px;" class="btn-group">';
				
					echo'<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 15px;height:28px;background: none;border: none;color: #a5a5a5;box-shadow: none;"><span class="glyphicon glyphicon-cog icon-cog" aria-hidden="true"></span></button>';
										
					echo'<ul class="dropdown-menu dropdown-menu-right" style="width:250px;">';
						
						if( !empty($layer->urls['settings']) ){
							
							echo'<li style="position:relative;">';
							
								echo '<a href="' . $layer->urls['settings'] . '">Edit Settings</a>';
							
							echo'</li>';
						}						
						
						if( !empty($layer->urls['preview']) ){
							
							echo'<li style="position:relative;">';
								
								echo '<a target="_blank" href="' . $layer->urls['preview'] . '"> Preview Template</a>';

							echo'</li>';
						}

						do_action('ltple_editor_navbar_settings',$layer);

					echo'</ul>';
						
				echo'</div>';
			}
		}
			
	echo'</div>';
	
echo'</div>';

do_action('ltple_message');
