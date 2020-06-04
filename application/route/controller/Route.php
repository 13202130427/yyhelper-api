<?php

namespace app\route\controller;
use think\Controller;
use think\Request;
use think\Db;
class Route extends Controller{
  
	
	/**
	 * 获取路线列表
	 */
	public function Index(){
		header('Content-Type:application/json; charset=utf-8');
		$request = Request::instance();
		$data = $request->param();
		if(empty($data['userId'])){
			//没传 全部
			$result = Db::table('yyhelper_route')
					->alias('jr')
					->where('jr.status',1)
		            ->join('yyhelper_user ju','jr.userId = ju.id')       
		            ->field('jr.id,jr.name,ju.name developer')
		            ->select();
		}else{
			//传了推荐
			$user = Db::table('yyhelper_job_intention')->where('userId',$data['userId'])->field('industry_id,job_id')->find();
			if($user){
				//个人求职意向有数据
				$industry_id = $user['industry_id'];
				$job_id = $user['job_id'];
			}else{
				$industry_id =0;
				$job_id=0;
			}
			$result = Db::table('yyhelper_route')
					->alias('jr')
					->where('jr.status',1)
					->where('(jr.industry_id = '.$industry_id.' OR jr.industry_id = 0) AND (jr.job_id = '.$job_id.' OR jr.job_id = 0)')
		            ->join('yyhelper_user ju','jr.userId = ju.id')       
		            ->field('jr.id,jr.name,ju.name developer')
		            ->select();
		    if(!$result){
	    		$arr =  array(
					'statuscode' => '51003',
					'msg' => '当前用户没有适配路线',
				);
				exit(json_encode($arr));
		    }
		}
		if($result){
			$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'routeList' => $result
			);
		}else{
			$arr =  array(
				'statuscode' => '1002',
				'msg' => '无效的userId'
			);
		}
		
		exit(json_encode($arr));
	}
	/**
	* 指定id查看路线信息
	*/
	public function View($id){
		header('Content-Type:application/json; charset=utf-8');
		$request = Request::instance();

		$data = $request->param();
		if(empty($id)){
			$arr =  array(
				'statuscode' => '51002',
				'msg' => '路线ID未传递'
			);
			exit(json_encode($arr));
		}
		$result = Db::table('yyhelper_route')->alias('jr')->where('jr.id',$id)->join('yyhelper_user ju','jr.userId = ju.id')->field('jr.id,jr.name,jr.info,ju.name developer')->find();    
		if($result){
			$arr = array(
					'statuscode'=>'200',
					'msg' => '请求成功',
					'route' => $result
				);
		}else{
			$arr =  array(
				'statuscode' => '51001',
				'msg' => '无效的路线ID'
			);
			exit(json_encode($arr));
		}

		exit(json_encode($arr));
	}
	
	
}
?>