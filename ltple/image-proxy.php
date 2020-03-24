<?php

    // CORS Allow from any origin
	
    if (isset($_SERVER['HTTP_ORIGIN'])) {
		
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
		
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

	$request_url = $_GET['url'];
	
	if(isset($_GET['url'])){
		
		header('Content-type: image');
		readfile($request_url);		
	}
	
	flush();
	exit;
	die;