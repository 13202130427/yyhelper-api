<?php

namespace app\resume\controller;
use think\Controller;
use think\Request;
use think\Db;
class Resume extends Controller{
  
	/**
	 * 上传简历
	 * POST传递
	 * @return 
	 */
	public function Upload(){
		header('Content-Type:text/html; charset=utf-8');
		$request = Request::instance();

		$formData = $request->param();
		$file = request()->file('file');
		// 移动到框架应用根目录/uploads/ 目录下
		$info = $file->move( './uploads');
		if($info){
		// 成功上传后 获取上传信息

		$path = str_replace('\\', '/', 'https://www.api.yyhelper.icu/uploads/'.$info->getSaveName());
		if($formData['size'] >= 1024){
			$size = round($formData['size'] / 1024 * 100) / 100 . 'KB';
		}else{
			$size = $formData['size'] . 'B';
		}
		
		$data = [
			'name' => $formData['name'],
			'time' => date('Y.m.d H:i', $formData['time']),
			'type' => $info->getExtension(),
			'path' => $path,
			'size' => $size,
			'userId' => $formData['userId']
		];
		
		$result = Db::table('yyhelper_resume')->insert($data);
		if($result){
			echo '上传成功';
		}else{
			echo '上传失败';
		}
		}else{
		// 上传失败获取错误信息
		echo $file->getError();
		}
		
		
		//exit(json_encode($data));
	}

	public function Select($userId){
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
		$user =  Db::table('yyhelper_user')->where('id',$userId)->find();
		if(!$user){
			$arr =  array(
				'statuscode' => '1001',
				'msg' => '无效的userId',
			);
			exit(json_encode($arr));
		}
		$resumelist = Db::table('yyhelper_resume')->where('userId',$data['userId'])->select();
						
		if($resumelist){
			$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'resumelist'=> $resumelist
			);
		}else{
			$arr =  array(
				'statuscode' => '404',
				'msg' => '请求的资源不存在'
			);
		}

		exit(json_encode($arr));
	}
}
?>