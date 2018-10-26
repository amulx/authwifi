<?php
	function getIP() { 
		if (getenv('HTTP_CLIENT_IP')) { 
			$ip = getenv('HTTP_CLIENT_IP'); 
		} 
		elseif (getenv('HTTP_X_FORWARDED_FOR')) { 
			$ip = getenv('HTTP_X_FORWARDED_FOR'); 
		} 
		elseif (getenv('HTTP_X_FORWARDED')) { 
			$ip = getenv('HTTP_X_FORWARDED'); 
		} 
		elseif (getenv('HTTP_FORWARDED_FOR')) { 
			$ip = getenv('HTTP_FORWARDED_FOR');
		} 
		elseif (getenv('HTTP_FORWARDED')) { 
			$ip = getenv('HTTP_FORWARDED'); 
		} 
		else { 
			$ip = $_SERVER['REMOTE_ADDR']; 
		} 
		return $ip; 
	}

	function doRedirect($userip)
	{
		header('Location: ' . 'http://' .$_SERVER['SERVER_ADDR']. '/index.html');
	}

	function iosRespone()
	{
		return "<HTML><HEAD><TITLE>Success</TITLE></HEAD><BODY>Success</BODY></HTML>";
	}	


	$ios_hosts = array(
		'www.appleiphonecell.com',
		"captive.apple.com",
		"www.appleiphonecell.com",
		'www.apple.com',
		"www.ibook.info",
		"www.itools.info",
		"www.airport.us",
		"www.thinkdifferent.us"
	);
	
	$server_protocol = $_SERVER["SERVER_PROTOCOL"];
	$clientip = getIP();
	$uri = $_SERVER["REQUEST_URI"];
	$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
    
	if ($host == "www.apple.com")
	{
		doRedirect($clientip);
		return;
	}
	else if ($server_protocol == "HTTP/1.1" && $host == "captive.apple.com" && $uri == "/hotspot-detect.html")
	{
		doRedirect($clientip);
		return;
	}
	else if ($server_protocol == "HTTP/1.0" && $host == "captive.apple.com" && $uri == "/hotspot-detect.html")
	{
		echo "<HTML><HEAD><TITLE></TITLE></HEAD><BODY></BODY></HTML>";
		return;
	}
	
	// 判断是否为安卓的网络检查
	if ($uri == "/generate_204" || $uri == "/hotspot-detect.html")
	{
		doRedirect($clientip);
	}
	else
	{
		if (in_array($host, $ios_hosts))
		{
			iosRespone();
		}
		else
		{
			doRedirect($clientip);
		}
	}
