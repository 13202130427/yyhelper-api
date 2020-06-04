<?php

namespace app\resume\controller;
use think\Controller;
use think\Request;
use think\Db;
class Prize extends Controller{
  
/**
 * 新增个人奖项数据
 * POST传递
 * @return 
 */
public function Create(){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();
	
	$validate = new \app\resume\validate\Prize;
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
		$result = Db::table('yyhelper_prize')->strict(false)->insert($data);
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
 * 修改个人奖项数据
 * id  int 个人奖项识别ID
 * @return 
 */
public function Update($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();
	
	$validate = new \app\resume\validate\Prize;
	if(!$validate->scene('update')->check($data)){
		$arr =  array(
			'statuscode' => '400',
			'msg' => $validate->getError()
		);
	}else{
		//验证id是否传递
		if(empty($id)){
			$arr =  array(
				'statuscode' => '54401',
				'msg' => '个人奖项ID未传递',
			);
			exit(json_encode($arr));
		}
		$result = Db::table('yyhelper_prize')->where('id',$id)->update($data);
		if($result){
			$arr =  array(
			'statuscode' => '202',
			'msg' => '更新成功'
		);
		}else{
			$arr =  array(
			'statuscode' => '54402',
			'msg' => '无效的个人奖项ID',
		);
		}
		
	}
		

	
	exit(json_encode($arr));
}


/**
 * 查看个人奖项数据
 * @return prize json
 */
public function Index(){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();
	
	$validate = new \app\resume\validate\Prize;
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
		$prize = Db::table('yyhelper_prize')->where('type',$data['type'])->where('userId',$data['userId'])->field('id,info')->find();
		//将字符串变成数组
		$prize['info'] = explode('</br>', $prize['info']);
		if($prize){
			$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'prize'=> $prize
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
 * 指定id查看个人奖项数据
 * id int 个人奖项识别ID
 * @return  prize json
 */
public function View($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();
	
	if(empty($id)){
		$arr =  array(
				'statuscode' => '54401',
				'msg' => '个人奖项ID未传递',
			);
	}else{
		$result = Db::table('yyhelper_prize')->where('id',$id)->field('id,info')->find();
		if($result){
			//将字符串变成数组
			$result['info'] = explode('</br>', $result['info']);
			$arr =  array(
			'statuscode' => '200',
			'msg' => '请求成功',
			'prize'=> $result
		);
		}else{
			$arr =  array(
			'statuscode' => '54402',
			'msg' => '无效的个人奖项ID'
		);
		}
		
	}
	exit(json_encode($arr));
}

	/**
 * 指定id删除个人奖项数据
 * id int 个人奖项识别ID
 * @return  json
 */
public function Delete($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
		$data = $request->param();
		if(empty($id)){
		$arr =  array(
				'statuscode' => '54401',
				'msg' => '个人奖项ID未传递',
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
		if(empty($data['index']) && $data['index'] != 0){
			$arr =  array(
				'statuscode' => '54404',
				'msg' => '索引行数未传递'
			);
			exit(json_encode($arr));
		}
		$result = Db::table('yyhelper_prize')->where('id',$id)->where('userId',$data['userId'])->field('id,info')->find();
		if($result){
			//将字符串变成数组
			$result['info'] = explode('</br>', $result['info']);
			//定义一个字符串存储
			$info = '';
			foreach ($result['info'] as $key => $value) {
				if($key != $data['index']){
					//该保留的行
					$info = $info."</br>".$value;
				}
			}
			$info = ltrim($info,"</br>");
			if(empty($info)){
					$result1 = Db::table('yyhelper_prize')->where('id',$id)->delete();
			}else{
				$result1 = Db::table('yyhelper_prize')->where('id',$id)->update(['info'=>$info]);
			}
			if($result1){
				$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功'			
				);
			}
		}else{
			$arr =  array(
			'statuscode' => '54403',
			'msg' => '用户ID和个人奖项ID不匹配'
			);
		}
			

	
	exit(json_encode($arr));
}


  
}
?>