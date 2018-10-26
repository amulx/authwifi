<?php
ini_set('display_errors', 0);
ini_set("error_reporting","E_ALL & ~E_NOTICE");
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

// 业务逻辑
// do something

/**
	参数		说明
	extend	为上文中调用呼起微信JSAPI时传递的extend参数，这里原样回传给商家主页
	openId	用户的微信openId
	tid		为加密后的用户手机号码（仅作网监部门备案使用） 
 */
//封装https请求（GET和POST）    
function https_request($url,$data=null)
{
    //1、初始化curl
    $ch = curl_init();

    //2、设置传输选项
    curl_setopt($ch, CURLOPT_URL, $url);//请求的url地址
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//将请求的结果以文件流的形式返回
    
    if(!empty($data))
    {
        curl_setopt($ch,CURLOPT_POST,1);//请求POST方式
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);//POST提交的内容
    }

    //3、执行请求并处理结果
    $outopt = curl_exec($ch);

    //把json数据转化成数组
    $outoptArr = json_decode($outopt,TRUE);

    //4、关闭curl
    curl_close($ch);

    //如果返回的结果$outopt是json数据，则需要判断一下
    if(is_array($outoptArr))
    { 
        return $outoptArr;
    }
    else
    {
        return $outopt;
    }     
}
// 1、获取用户 openid
	$openid = $_GET['openId'];

// 2、获取Token
	// 2.1 这里可以加缓存 2 小时有效期
	$token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=***&secret=***';
    $token_data = https_request($token_url);
/* 响应结果
{
    "access_token":"O3PwqnKoiiYmsHeAh8viWLQyhGRrGU6RT9o53pvlmhIBWQnTXeZDSkYNw6YufzIDUspzQguvxtmLXtAWmQd2NmurXKa4N4PsbwG7RvI25pqzSC3-cLl50iqSW5VaZ4xmGXQgAFAJAT",
    "expires_in":7200
}
 */

 // 3、使用Token和OpenID获取用户信息
	$userInfo_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=***&openid=***&lang=zh_CN';
    $userInfo_data = https_request($userInfo_url);
/*
{
    "subscribe":1,
    "openid":"oeQDZt0n4VCZ70wy***",
    "nickname":"背上***旅行",
    "sex":1,
    "language":"zh_CN",
    "city":"昌平",
    "province":"北京",
    "country":"中国",
    "headimgurl":"http://wx.qlogo.cn/mmopen/kBwGJuwqK9**********************ibVUEpgFE90LH3b3uj7AYRjZP/0",
    "subscribe_time":1474964999,
    "unionid":"oGCG8t5**********jPQTPw",
    "remark":"",
    "groupid":0,
    "tagid_list":[

    ]
}
*/

// 4 判断是否关注
if ($userInfo_data['subscribe']) {
	// do something
	header('HTTP/1.1 200 OK');
	header('Location: http://www.example.org/');
} else {
	header('HTTP/1.1 301 Moved Permanently');
	header('HTTP/1.1 401 Unauthorized');
	header("http/1.1 403 Forbidden");
	header('HTTP/1.1 404 Not Found');
	header('HTTP/1.1 500 Internal Server Error');
}
