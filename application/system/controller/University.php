<?php

namespace app\system\controller;
use think\Controller;
use think\Request;
use think\Db;
class University extends Controller{
  
/**
 * city string 市
 * region     string 区
 * @return university arr 大学
 */
public function university(){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();

	if(empty($data['city']) && empty($data['region'])){
		$result = Db::table('yyhelper_university')->field('id,name,status')->select();
		if($result){
			$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'university' => $result
			);
			exit(json_encode($arr));
		}else{
			$arr =  array(
				'statuscode' => '999',
				'msg' => '操作数据库失败'
			);
			exit(json_encode($arr));
		}
		
	}else{
		$validate = new \app\system\validate\University;
		if(!$validate->check($data)){
			$arr =  array(
				'statuscode' => '400',
				'msg' => $validate->getError(),
			);
			exit(json_encode($arr));
		}else{
			$result = Db::table('yyhelper_university')->where('city',$data['city'])->where('region',$data['region'])->field('id,name,status')->select();
	
			$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'university' => $result
			);
			exit(json_encode($arr));
		}	
	}
	
		
	
	

}


  
}
?>