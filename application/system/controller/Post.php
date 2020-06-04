<?php

namespace app\system\controller;
use think\Controller;
use think\Request;
use think\Db;
class Post extends Controller{
  
/**
 * 
 * @return post json 三级职位信息
 */
public function Index(){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();

	$result = Db::table('yyhelper_job')->field('id,name')->select();
	foreach ($result as $key => $value) {
		$result[$key]['sec_name'] = Db::table('yyhelper_jobtwo')->where('jobid',$value['id'])->field('id,name')->select();
		foreach ($result[$key]['sec_name'] as $key1 => $value1) {
			$result[$key]['sec_name'][$key1]['third_name'] = Db::table('yyhelper_jobthree')->where('jobtwoid',$value1['id'])->field('id,name')->select();
		}
	}
	$arr = array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'industry_type' => $result 
		);
		

	
	exit(json_encode($arr));
}

public function Searchkey(){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();

	$data = $request->param();
	if(empty($data['key'])){
		$find = Db::table('yyhelper_jobthree')->field('id,name')->select();
		$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'result'=> $find
			);
	}else{
		$find = Db::table('yyhelper_jobthree')->where('name','like','%'.$data['key'].'%')->field('id,name')->select();
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