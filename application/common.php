<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Db;

	//我们的项目（程序），在服务器上的绝对路径
	define('SA_PATH',dirname(dirname(__FILE__)));
	//我们的项目在web根目录下面的位置（哪个目录里面）
	define('SUB_URL',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('\\','/',SA_PATH)).'/');


 function encryption($value,$type=0){
	$key = config('encryption_key');
	if($type == 0){
		return str_replace('/','',base64_encode($value^$key));
	}else{
		return base64_decode($value)^ $key;
	}
}
 function httpGet($url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HEADER, 0 );
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}
 function https_request($url,$data=null){
    $curl = curl_init();
    curl_setopt($curl,CURLOPT_URL,$url);
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE);
    if(!empty($data)){
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
    }
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    //Content-Type: application/json 修改  zsh
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Content-Length: ' . strlen($data)
    ));
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}