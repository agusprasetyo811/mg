<?php

function filemtime_remote($uri) {
	$uri = parse_url($uri);
	$handle = @fsockopen($uri ['host'], 80);
	if (! $handle) return 0;
	
	fputs($handle, "GET $uri[path] HTTP/1.1\r\nHost: $uri[host]\r\n\r\n");
	$result = 0;
	while ( ! feof($handle) ) {
		$line = fgets($handle, 1024);
		if (! trim($line)) break;
		
		$col = strpos($line, ':');
		if ($col !== false) {
			$header = trim(substr($line, 0, $col));
			$value = trim(substr($line, $col + 1));
			if (strtolower($header) == 'last-modified') {
				$result = strtotime($value);
				break;
			}
		}
	}
	fclose($handle);
	return $result;
}

function is_url($url) {
	$regex = "((https?|ftp)\:\/\/)?"; // SCHEME
	$regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
	$regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
	$regex .= "(\:[0-9]{2,5})?"; // Port
	$regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
	$regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
	$regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor
	
	if(preg_match("/^$regex$/", $url)) {
		return 1;
	} else {
		return 0;
	}
}