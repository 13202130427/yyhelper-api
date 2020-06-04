<?php

namespace app\video\controller;
use think\Controller;
use think\Request;
use think\Db;
class Video extends Controller{
  
	
	/**
	 * 获取视频列表
	 */
	public function Index(){
		header('Content-Type:application/json; charset=utf-8');
		$request = Request::instance();
		$data = $request->param();
		if(empty($data['from'])){
			$arr =  array(
				'statuscode' => '400',
				'msg' => '获取方向未传递',
			);
			exit(json_encode($arr));
		}
		if($data['from']  == 'search'){
			//从搜索框传来
			if(empty($data['key'])){
				//显示所有视频
				$result = Db::table('yyhelper_video')->where('status',1)->select();
			}else{
				//按照关键字在数据库检索
				$result = Db::table('yyhelper_video')->where('name','like','%'.$data['key'].'%')->where('status',1)->field('id,name,photo,url,userId')->select();
			}
			if($result){
				$arr =  array(
					'statuscode' => '200',
					'msg' => '请求成功',
					'videolist'=> $result
				);
			}else{
				$arr =  array(
					'statuscode' => '404',
					'msg' => '请求的资源不存在',
				);
			}
		}
			
		
		exit(json_encode($arr));
	}
	/**
	 * 获取视频信息
	 */
	public function View($id){
		header('Content-Type:application/json; charset=utf-8');
		$request = Request::instance();
		$data = $request->param();
		if(empty($id)){
			$arr =  array(
				'statuscode' => '55101',
				'msg' => '视频ID未传递',
			);
			exit(json_encode($arr));
		}
		$video = Db::table('yyhelper_video')->where('id',$id)->find();
		if(!$video){
			$arr =  array(
				'statuscode' => '55102',
				'msg' => '无效的视频ID',
			);
			exit(json_encode($arr));
		}else{
			if($video['status'] == 0){
				$arr =  array(
					'statuscode' => '55103',
					'msg' => '当前视频未审核',
				);
				exit(json_encode($arr));
			}
			if($video['status'] == 2){
				$arr =  array(
					'statuscode' => '55104',
					'msg' => '当前视频审核不通过',
				);
				exit(json_encode($arr));
			}
		}
		$result = Db::table('yyhelper_videoinfo')->where('video_id',$id)->select();
		if($result){
			$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'videolist'=> $result
			);
		}else{
			$arr =  array(
				'statuscode' => '404',
				'msg' => '请求的资源不存在',
			);
		}
			
			
		
		exit(json_encode($arr));
	}
	
	
	/**
	 * 创建视频信息
	 **/
	 public function Create(){
	 	header('Content-Type:text/html; charset=utf-8');
		$request = Request::instance();
		$formData = $request->param();
		if(empty($formData['step'])){
			$arr = ['statuscode'=>55104,'msg'=>'当前步骤未传递'];
				exit(json_encode($arr));
		}
		if($formData['step'] != '1' && $formData['step'] != '2'){
			$arr = ['statuscode'=>55105,'msg'=>'当前步骤格式错误 请输入1或2'];
			exit(json_encode($arr));

		}
		
		if($formData['step'] == 1){
			if(empty($formData['name'])){
				$arr = ['statuscode'=>55106,'msg'=>'视频名称未传递'];
				exit(json_encode($arr));
			}
			if(empty($formData['userId'])){
				$userId = 0;
			}else{
				$userId =$formData['userId'];
				$user = Db::table('yyhelper_user')->where('id',$userId)->find();
				if(!$user){
					$arr = ['statuscode'=>1002,'msg'=>'无效的userId'];
					exit(json_encode($arr));
				}
			}
			//上传封面
			$rand = uniqid();
			$file = request()->file('image');
			// 移动到框架应用根目录/uploads/ 目录下
			$info = $file->validate(['size'=>1048576,'ext'=>'jpg,png,bmp'])->rule('uniqid')->move( './video/'.$rand);
			if($info){
			// 成功上传后 获取上传信息
			$path = str_replace('\\', '/', 'https://www.api.yyhelper.icu/video/'.$rand.'/'.$info->getSaveName());
			$result = Db::table('yyhelper_video')
					->insertGetId([
								'name'=>$formData['name'],
								'photo'=>$path,
								'url'=>'https://www.api.yyhelper.icu/video/'.$rand,
								'status'=>'-1',
								'userId' =>$userId,
							]);
			if($result){
				$arr = ['statuscode'=>0,'msg'=>'上传成功','video_id'=>$result];
				exit(json_encode($arr));
			}else{
				$arr = ['statuscode'=>999,'msg'=>'操作数据库失败'];
				exit(json_encode($arr));
			}
			}else{
			// 上传失败获取错误信息
			$arr = ['statuscode'=>1,'msg'=>$file->getError()];
			exit(json_encode($arr));
			}
		}
		if($formData['step'] == 2){
			//$time = date('Y-m-d H:i:s', time());
			if(empty($formData['video_id'])){
				$arr = ['statuscode'=>55101,'msg'=>'视频ID未传递'];
				exit(json_encode($arr));
			}
			
			//获取视频路径
			$result = Db::table('yyhelper_video')->where('id',$formData['video_id'])->find();
			if(!$result){
				$arr = ['statuscode'=>55102,'msg'=>'无效的视频ID'];
				exit(json_encode($arr));
			}
			$rand = trim(strrchr($result['url'], '/'),'/');
			//上传视频
			$file = request()->file('video');
			// 移动到框架应用根目录/uploads/ 目录下
			$info = $file->rule('uniqid')->validate(['size'=>209715200,'ext'=>'mp4'])->move( './video/'.$rand);
			if($info){
			
			$arr = ['statuscode'=>0,'msg'=>'上传成功','path'=>$info->getSaveName()];
			exit(json_encode($arr));
			}else{
			// 上传失败获取错误信息
			$arr = ['statuscode'=>1,'msg'=>$file->getError()];
			exit(json_encode($arr));
			}
		}
		
		
		
	 }
	 /**
	  * 创建视频分集信息
	  * video_id int
	  * name jsonstr
	  * path jsonstr
	  * */
	 public function Createinfo(){
	 	header('Content-Type:application/json; charset=utf-8');
		$request = Request::instance();
		$datajsonstr = $request->param();
		$time = date('Y-m-d H:i:s', time());
		$data =json_decode($datajsonstr['data'],true);
		
		
		if(empty($data['video_id'])){
			$arr =  array(
				'statuscode' => '55101',
				'msg' => '视频ID未传递',
			);
			exit(json_encode($arr));
		}
		
		$result = Db::table('yyhelper_video')->where('id',$data['video_id'])->find();
		if(!$result){
			$arr = ['statuscode'=>55102,'msg'=>'无效的视频ID'];
			exit(json_encode($arr));
		}
		if(empty($data['name'])){
			$arr = ['statuscode'=>55106,'msg'=>'视频名称未传递'];
			exit(json_encode($arr));
		}
		if(empty($data['path'])){
			$arr = ['statuscode'=>55103,'msg'=>'视频存放路径未传递'];
			exit(json_encode($arr));
		}
		
		if(count($data['name']) != count($data['path'])){
			$arr = ['statuscode'=>55107,'msg'=>'视频名称与存放路径不匹配'];
			exit(json_encode($arr));
		}
	
		for($i=0;$i < count($data['name']);$i++){
			$result[$i] = Db::table('yyhelper_videoinfo')->insert(['video_id'=>$data['video_id'],'name'=>$data['name'][$i],'url'=>'/'.$data['path'][$i],'page'=>$i+1]);
		}
		//修改状态
		$result = Db::table('yyhelper_video')->where('id',$data['video_id'])->update(['status'=>2,'time'=>$time]);
		if($result){
			$arr =  array(
				'statuscode' => '201',
				'msg' => '请求成功',
			);
		}else{
			$arr =  array(
				'statuscode' => '999',
				'msg' => '操作数据库失败',
			);
		}
		
		exit(json_encode($arr));
	 }
	 
	 
}
?>