<?php

namespace app\resume\controller;
use think\Controller;
use think\Request;
use think\Db;
class Campusexperience extends Controller{
  
/**
 * 新增校园经历数据
 * POST传递
 * @return 
 */
public function Create(){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();
	
	$validate = new \app\resume\validate\Campusexperience;
	if(!$validate->check($data)){
		$arr =  array(
			'statuscode' => '400',
			'msg' => $validate->getError()
		);
	}else{
		//验证type
		if($data['type'] != '大一'&& $data['type'] != '大二'&&$data['type'] != '大三'&&$data['type'] != '大四'){
			$arr =  array(
				'statuscode' => '1001',
				'msg' => '简历类型错误',
			);
			exit(json_encode($arr));
		}
		//验证userId是否存在
		$user =  Db::table('yyhelper_user')->where('id',$data['userId'])->find();
		if(!$user){
			$arr =  array(
				'statuscode' => '1002',
				'msg' => '无效的userId',
			);
			exit(json_encode($arr));
		}
		$result = Db::table('yyhelper_campus_experience')->strict(false)->insert($data);
		if($result){
			$arr =  array(
			'statuscode' => '201',
			'msg' => '创建成功'
		);
		}else{
			$arr =  array(
			'statuscode' => '999',
			'msg' => '操作数据库失败',
		);
		}
		
	}
	
	exit(json_encode($arr));
}

/**
 * 修改校园经历数据
 * id  int 校园经历识别ID
 * @return 
 */
public function Update($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();
	
	$validate = new \app\resume\validate\Campusexperience;
	if(!$validate->scene('update')->check($data)){
		$arr =  array(
			'statuscode' => '400',
			'msg' => $validate->getError()
		);
	}else{
		//验证id是否传递
		if(empty($id)){
			$arr =  array(
				'statuscode' => '54201',
				'msg' => '校园经历ID未传递',
			);
			exit(json_encode($arr));
		}
		$result = Db::table('yyhelper_campus_experience')->where('id',$id)->update($data);
		if($result){
			$arr =  array(
			'statuscode' => '202',
			'msg' => '更新成功'
		);
		}else{
			$arr =  array(
			'statuscode' => '54202',
			'msg' => '无效的校园经历ID',
		);
		}
		
	}
	exit(json_encode($arr));
}


/**
 * 查看校园经历数据
 * @return jobhistory json
 */
public function Index(){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();
	
	$validate = new \app\resume\validate\Campusexperience;
	if(!$validate->scene('select')->check($data)){
		$arr =  array(
				'statuscode' => '400',
				'msg' => $validate->getError()
			);
	}else{
		//验证type
		if($data['type'] != '大一'&& $data['type'] != '大二'&&$data['type'] != '大三'&&$data['type'] != '大四'){
			$arr =  array(
				'statuscode' => '1001',
				'msg' => '简历类型错误',
			);
			exit(json_encode($arr));
		}
		//验证user_id是否存在
		$user =  Db::table('yyhelper_user')->where('id',$data['userId'])->find();
		if(!$user){
			$arr =  array(
				'statuscode' => '1002',
				'msg' => '无效的userId',
			);
			exit(json_encode($arr));
		}
		$campusexperience = Db::table('yyhelper_campus_experience')->where('type',$data['type'])->where('userId',$data['userId'])->select();
		if($campusexperience){
			$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'campusexperience'=> $campusexperience
			);
		}else{
			$arr =  array(
				'statuscode' => '404',
				'msg' => '请求的资源不存在'
			);
		}

	}
		

	exit(json_encode($arr));
}

/**
 * 指定id查看校园经历数据
 * id int 校园经历识别ID
 * @return  campusexperience json
 */
public function View($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();
	
	if(empty($id)){
		$arr =  array(
				'statuscode' => '54201',
				'msg' => '校园经历ID未传递',
			);
	}else{
		$result = Db::table('yyhelper_campus_experience')->where('id',$id)->find();
		if($result){
			$arr =  array(
			'statuscode' => '200',
			'msg' => '请求成功',
			'campusexperience'=> $result
		);
		}else{
			$arr =  array(
			'statuscode' => '54202',
			'msg' => '无效的校园经历ID'
		);
		}
		
	}
	
	exit(json_encode($arr));
}

	/**
 * 指定id删除校园经历数据
 * id int 校园经历识别ID
 * @return jobhistory json
 */
public function Delete($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();
	
	if(empty($id)){
		$arr =  array(
				'statuscode' => '54201',
				'msg' => '校园经历ID未传递',
			);
		exit(json_encode($arr));
	}
	if(empty($data['userId'])){
		$arr =  array(
			'statuscode' => '400',
			'msg' => '请传递用户ID'
		);
		exit(json_encode($arr));
	}
	//验证user_id是否存在
	$user =  Db::table('yyhelper_user')->where('id',$data['userId'])->find();
	if(!$user){
		$arr =  array(
			'statuscode' => '1002',
			'msg' => '无效的userId',
		);
		exit(json_encode($arr));
	}
	$result = Db::table('yyhelper_campus_experience')->where('id',$id)->where('userId',$data['userId'])->delete();
		if($result){
			$arr =  array(
			'statuscode' => '200',
			'msg' => '请求成功'			
			);
		}else{
			$arr =  array(
			'statuscode' => '54203',
			'msg' => '用户ID和校园经历ID不匹配'
			);
		}	

	
	exit(json_encode($arr));
}


  
}
?>