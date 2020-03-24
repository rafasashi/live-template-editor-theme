<?php

$theme = LTPLE_Theme();

echo '<!DOCTYPE>';
echo '<html style="margin:0!important;overflow-x:hidden;">';

	echo '<head>';
	
		echo '<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->';
		echo '<!--[if lt IE 9]>';
		echo '<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>';
		echo '<![endif]-->';		

		echo '<meta charset="UTF-8">';
		echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
		
		echo '<link rel="profile" href="http://gmpg.org/xfn/11">';
		
		echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
		echo '<link rel="dns-prefetch" href="//s.w.org">';
 
		echo '<title>Live Template Editor</title>';
		
		wp_head();
		
	echo '</head>';

	echo '<body style="margin:0px;padding:0px;overflow:hidden;background:#fbfbfb!important;">';
		
		echo '<div id="ltple-wrapper" class="boxedcontent" style="position:absolute;z-index:auto;border:none;background:#fbfbfb!important;">';
						
			include( 'navbar.php' );
			
			if( $layer = $theme->editor->get_layer() ){
			
				// output editor iframe
				
				echo'<div class="loadingIframe" style="width:100%;position:absolute;top:45px;background-position: 50% center;background-repeat: no-repeat;background-image:url(\''. $theme->assets_url .'images/loader.gif\');height:64px;"></div>';
				
				echo'<iframe id="editorIframe" src="' . $theme->editor->get_iframe_url($layer) . '" style="position: absolute;width: 100%;top: 45px;bottom: 0;border:0;height: 1300px;overflow: hidden;"></iframe>';

				//editor settings

				echo'<script id="LiveTplEditorSettings">' .PHP_EOL;
					
					echo $theme->editor->get_js_settings($layer);
					
				echo'</script>' . PHP_EOL;
			}
			
		echo '</div>';
		
		wp_footer();
		
	echo '</body>';
	
echo '</html>';
