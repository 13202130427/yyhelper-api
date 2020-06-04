<?php

namespace app\system\controller;
use think\Controller;
use think\Request;
use think\Db;
class Colleges extends Controller{
  
/**
 * universityId 学校ID
 * @return colleges arr 学院
 */
public function Colleges(){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();

	$data = $request->param();
	//dump($data);
	//信息筛选
	if(empty($data['universityId'])){
		$arr =  array(
			'statuscode' => '1003',
			'msg' => '学校ID未传递',
		);
		exit(json_encode($arr));
	}
		$result = Db::table('yyhelper_colleges')->where('university_id',$data['universityId'])->field('id,name')->select();
		$arr =  array(
			'statuscode' => '200',
			'msg' => '请求成功',
			'colleges' => $result
		);
		
	
	exit(json_encode($arr));
}


  
}
?>