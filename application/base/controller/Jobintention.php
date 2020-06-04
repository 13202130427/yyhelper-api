<?php

namespace app\base\controller;
use think\Controller;
use think\Request;
use think\Db;
class Jobintention extends Controller{

/**
 * 修改求职意向数据
 * userId int 用户ID
 * @return 
 */
public function Update($userId){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();
	//验证userId是否传递
	if(empty($userId)){
		$arr =  array(
			'statuscode' => '52001',
			'msg' => '用户ID未传递',
		);
		exit(json_encode($arr));
	}
	$data['userId']=$userId;
	$user =  Db::table('yyhelper_user')->where('id',$userId)->find();
	if(!$user){
		$arr =  array(
			'statuscode' => '1002',
			'msg' => '无效的userId',
		);
		exit(json_encode($arr));
	}
	$result = Db::table('yyhelper_job_intention')->where('userId',$userId)->find();
	if($result){
		//修改
		$result1 = Db::table('yyhelper_job_intention')->where('userId',$userId)->strict(false)->update($data);
	}else{
		//新增
		$result1 = Db::table('yyhelper_job_intention')->strict(false)->insert($data);
	}
	if($result1){
		$arr =  array(
		'statuscode' => '202',
		'msg' => '更新成功'
	);
	}else{
		$arr =  array(
		'statuscode' => '999',
		'msg' => '数据库操作错误',
	);
	}
		
	

	
	exit(json_encode($arr));
}

/**
 * 指定userId查看求职意向数据
 * userId int 用户ID
 * @return JobIntention json
 */
public function View($userId){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
		$data = $request->param();
		if(empty($userId)){
			$arr =  array(
				'statuscode' => '52001',
				'msg' => '用户ID未传递'
			);
		}else{
			$result = Db::table('yyhelper_job_intention')->where('userId',$userId)->find();
			if($result){
				if($result['job_id'] == 0){
					$result['chooseIndustryType'] = '请选择';
					$result['hasIndustryType'] = '0';
				}else{
					$result1 = Db::table('yyhelper_jobthree')->where('id',$result['job_id'])->find();
					$result['chooseIndustryType'] = $result1['name'];
					$result['hasIndustryType'] = '1';
				}
				if($result['industry_id'] == 0){
					$result['chooseIndustry'] = '请选择';
					$result['hasIndustry'] = '0';
				}else{
					$result1 = Db::table('yyhelper_industrytwo')->where('id',$result['industry_id'])->find();
					$result['chooseIndustry'] = $result1['name'];
					$result['hasIndustry'] = '1';
				}
				//修改数据格式合理输出
				//region
				$result['region'] = explode(',', $result['region']);
				//moneyIndex
				$result['moneyIndex'] = explode(',', $result['moneyIndex']);
				$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'jobintention'=> $result
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
 
}
?>