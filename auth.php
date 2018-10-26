<?php
$name = $_POST['name'];
$password = $_POST['password'];
if ($name == 'bd' && $password == 'bd') {
	$cmd = 'sudo ipset add amu '. getIP();
	exec($cmd);
	echo '<script> location.href = "https://www.baidu.com/";</script>';
} else {
	echo '<script> alert("fuck gay");location.href = "index.php";</script>';
}


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