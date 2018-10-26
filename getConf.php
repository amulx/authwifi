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

$client_ip = getIP();

$weixin_config_arr['appId'] = 'xxx';
$weixin_config_arr['shop_id'] = 'xxx';
$weixin_config_arr['ssid'] = 'xxx';
$weixin_config_arr['secretkey'] = 'xxxxxxxx';
$weixin_config_arr['authUrl'] = 'http://'.$_SERVER['SERVER_ADDR'].'/getInfo.php';

$timestamp= (time()) * 1000;
//用户手机mac地址，格式冒号分隔，字符长度17个，并且字母小写，例如：00:1f:7a:ad:5c:a8  安卓设备必需
$mac = 'FF:FF:FF:FF:FF:FF';
//无线网络设备的无线mac地址，格式冒号分隔，字符长度17个，并且字母小写，例如：00:1f:7a:ad:5c:a8  
$bssid = 'FF:FF:FF:FF:FF:FF';      

// 2、拼接调用微信客户端的js路径
$query_arr = [
    'timestamp' => $timestamp,
    'appId' => $weixin_config_arr['appId'],
    'extend' => $client_ip,            
    'sign' => md5($weixin_config_arr['appId'] . $client_ip . $timestamp . $weixin_config_arr['shop_id'] . $weixin_config_arr['authUrl'] . $mac . $weixin_config_arr['ssid'] . $bssid . $weixin_config_arr['secretkey']),
    'shopId' => $weixin_config_arr['shop_id'],
    'authUrl' => $weixin_config_arr['authUrl'],//注意编码格式  utf-8
    'ssid' => $weixin_config_arr['ssid']
];

// 3、业务逻辑
// do something

$weichaturl = "https://wifi.weixin.qq.com/operator/callWechat.xhtml?".http_build_query($query_arr).'&mac='.$mac.'&bssid='.$bssid;

$result = array("result"=>"success","callback"=>"weichatLogin",'url'=>"https://wifi.weixin.qq.com/operator/callWechat.xhtml?".http_build_query($query_arr).'&mac='.$mac.'&bssid='.$bssid);
echo json_encode($result);
