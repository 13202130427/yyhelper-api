<?php

namespace app\route\controller;
use think\Controller;
use think\Request;
use think\Db;
class Study extends Controller{
  
	
	/**
	 * 获取用户学习进度
	 */
	public function Studyspeed($userId){
		header('Content-Type:application/json; charset=utf-8');
		$request = Request::instance();

		$data = $request->param();
		if(empty($userId)){
			$arr =  array(
					'statuscode' => '52001',
					'msg' => '用户ID未传递',
				);
			exit(json_encode($arr));
		}
		$result = Db::table('yyhelper_user')->where('id',$userId)->find();
	    if(!$result){
    		$arr =  array(
				'statuscode' => '1001',
				'msg' => '无效的userId',
			);
			exit(json_encode($arr));
	    }
    	if($result['route_id'] == 0){
    		$arr =  array(
				'statuscode' => '52201',
				'msg' => '当前用户未选择路线',
			);
			exit(json_encode($arr));
    	}
    	if($result['study_id'] == 0){
    		//设置该路线第一个视频目标为当前学习
    		$video = Db::table('yyhelper_study')->where('route_id',$result['route_id'])->order('sort','asc')->limit(1)->select();
    		$study_id = $video[0]['id'];	
    		$update_user = Db::table('yyhelper_user')->where('id',$userId)->update(['study_id'=>$study_id]);
    	}else{
    		$study_id = $result['study_id'];
    	}
    	if($result['page'] == 0){
    		$update_user = Db::table('yyhelper_user')->where('id',$userId)->update(['page'=>'1']);
    		$page = '1';
    	}else{
    		$page = $result['page'];
    	}
    	if(empty($data['studyId'])){
    		$study = Db::table('yyhelper_study')->alias('ys')->where('ys.id',$study_id)->join('yyhelper_video yv','ys.video_id = yv.id')->field('ys.id study_id,ys.name study_name,ys.info study_info,ys.video_id,yv.name video_name,yv.photo ,yv.url')->find();
    	}else{
    		$study = Db::table('yyhelper_study')->alias('ys')->where('ys.id',$data['studyId'])->join('yyhelper_video yv','ys.video_id = yv.id')->field('ys.id study_id,ys.name study_name,ys.info study_info,ys.video_id,yv.name video_name,yv.photo ,yv.url')->find();
    	}
    	
    	
    	$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'study_speed' => [
						'study_id' =>$study_id,
						'page' => $page,
						'speed' => $result['speed']
					],
				'info' =>$study
			);
			
		exit(json_encode($arr));
	}
	/**
	* 修改用户学习进度
	* userId int 用户ID
	* speed float 进度
	* from string 来源 keep 持续 end 结束 默认keep
	*/
	public function Update($userId){
		//持续 修改speed字段
		
		header('Content-Type:application/json; charset=utf-8');
		$request = Request::instance();

			$data = $request->param();
			if(empty($userId)){
				$arr =  array(
					'statuscode' => '52001',
					'msg' => '用户ID未传递',
				);
				exit(json_encode($arr));
			}
			if(empty($data['from']) || $data['from'] == 'keep'){
				//默认keep 修改speed字段
				if(empty($data['speed'])){
					$arr =  array(
						'statuscode' => '52203',
						'msg' => '视频进度未传递'
					);
					exit(json_encode($arr));
			}
				$user = Db::table('yyhelper_user')->where('id',$userId)->update(['speed'=>$data['speed']]);
				if($user){
					$arr =  array(
						'statuscode' => '202',
						'msg' => '更新成功'
					);
					exit(json_encode($arr));
				}else{
					$arr =  array(
						'statuscode' => '1001',
						'msg' => '无效的userId'
					);
					exit(json_encode($arr));
				}
			}elseif($data['from'] == 'end'){
				//结束 修改page 如果page是最后就进入下一课程	修改study_id字段 page speed归0
				$user = Db::table('yyhelper_user')->where('id',$userId)->find();
				$study = Db::table('yyhelper_study')->where('id',$user['study_id'])->find();
				$page = $user['page']+1;
				$result = Db::table('yyhelper_videoinfo')->where('video_id',$study['video_id'])->where('page',$page)->find();
				if($result){
					//还有分集
					$update_user = Db::table('yyhelper_user')->where('id',$userId)->update(['page'=>$page,'speed'=>'0']);
					if($update_user){
		    			$arr =  array(
							'statuscode' => '202',
							'msg' => '更新成功'
						);
						exit(json_encode($arr));
		    		}else{
		    			$arr =  array(
							'statuscode' => '1001',
							'msg' => '无效的userId'
						);
						exit(json_encode($arr));
		    		}
				}else{
					//进入下一课程
					$video = Db::table('yyhelper_study')->where('route_id',$user['route_id'])->where('sort','>',$study['sort'])->order('sort','asc')->limit(1)->select();
					$study_id = $video[0]['id'];	
		    		$update_user = Db::table('yyhelper_user')->where('id',$userId)->update(['study_id'=>$study_id,'speed'=>'0','page'=>'0']);
		    		if($update_user){
		    			$arr =  array(
							'statuscode' => '202',
							'msg' => '更新成功'
						);
						exit(json_encode($arr));
		    		}else{
		    			$arr =  array(
							'statuscode' => '1001',
							'msg' => '无效的userId'
						);
						exit(json_encode($arr));
		    		}
				}
			}

		exit(json_encode($arr));
	}
	
}
?>