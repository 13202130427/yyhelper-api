<?php

namespace app\system\controller;
use think\Controller;
use think\Request;
use think\Db;
class Industry extends Controller{
  
/**
 * 
 * @return Industry json 二级行业信息
 */
public function Industry(){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	
	$result = Db::table('yyhelper_industry')->field('id,name')->select();
	foreach ($result as $key => $value) {
		$result[$key]['info'] = Db::table('yyhelper_industrytwo')->where('industryid',$value['id'])->field('id,name')->select();
	}
	$arr = array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'industry' => $result 
		);
		
	

	exit(json_encode($arr));
}

public function Searchkey(){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();

	$data = $request->param();
	if(empty($data['key'])){
		$find = Db::table('yyhelper_industrytwo')->field('id,name')->select();
		$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'result'=> $find
			);
	}else{
		$find = Db::table('yyhelper_industrytwo')->where('name','like','%'.$data['key'].'%')->field('id,name')->select();
		$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'result'=> $find
			);
	}

	exit(json_encode($arr));
}


  
}
?>