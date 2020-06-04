<?php

namespace app\weixin\controller;
use think\Controller;
use think\Request;
use think\Db;
class Weixin extends Controller{

/**
 * 获取用户信息
 * id int 用户ID
 */
public function Getopenid(){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();
	// appid: 'wx838f2533d204fd59',
	// secret: '1b47808c37d79d7f48f77f09370a2594',
	// grant_type: 'authorization_code'
	$appid = 'wx838f2533d204fd59';
	$secret = '1b47808c37d79d7f48f77f09370a2594';
	if(empty($data['code'])){
		$arr =  array(
				'statuscode' => '1004',
				'msg' => '临时code未传递'
			);
			exit(json_encode($arr));
	}
	$url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$data['code'].'&grant_type=authorization_code';
	$res = httpGet($url);
	
	exit(json_encode($res));
}
  



  
}
?>