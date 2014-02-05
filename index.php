<?php
	//first url scrape
	//$url = 'all.html';
	$url = 'http://unsplash.com/';
	$output = file_get_contents($url);
	
	$pattern = "/(\<div class=\"caption\"\>\<p\>\<a href=\")(.*?)(\")/";
	preg_match_all($pattern, $output, $arr);
	$imgs = $arr[2];

	echo "<pre>";
	print_r($imgs);

	foreach ($imgs as $key => $url) {
		$pattern = "/([^\/]*)$/";
		preg_match($pattern, $url, $matches);
		print_r($matches);

		$filename = 'img/'.$matches[0].'.jpg';
		
		if (!file_exists($filename)) {
			$newurl = get_web_page($url);
			$content = file_get_contents($newurl['url']);
			
			$fp = fopen($filename,'wb');
			fwrite($fp, $content);
			fclose($fp);
		}
	}

	function get_web_page( $url ) {
	    $res = array();
	    $options = array( 
	        CURLOPT_RETURNTRANSFER => true,     // return web page 
	        CURLOPT_HEADER         => false,    // do not return headers 
	        CURLOPT_FOLLOWLOCATION => true,     // follow redirects 
	        CURLOPT_USERAGENT      => "spider", // who am i 
	        CURLOPT_AUTOREFERER    => true,     // set referer on redirect 
	        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect 
	        CURLOPT_TIMEOUT        => 120,      // timeout on response 
	        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects 
	    ); 

	    $ch      = curl_init( $url ); 
	    curl_setopt_array( $ch, $options ); 
	    $content = curl_exec( $ch ); 
	    $err     = curl_errno( $ch ); 
	    $errmsg  = curl_error( $ch ); 
	    $header  = curl_getinfo( $ch ); 
	    curl_close( $ch );

	    $res['content'] = $content;
	    $res['url'] = $header['url'];
	    return $res;
	}
?>
