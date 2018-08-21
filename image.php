<?php

	if( class_exists('LTPLE_Client') ){
		
		$ltple = LTPLE_Client::instance();
	
		$url = $ltple->urls->editor . '?pr=' . $post->post_author;

		wp_redirect($url);
		exit;		
	}