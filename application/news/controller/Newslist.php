<?php

namespace app\news\controller;
use think\Controller;
use think\Request;
use think\Db;
class Newslist extends Controller{
	/**
	 * 指定ID查找新闻信息
	 * @return json news
	 */
	public function View($id){
		header('Content-Type:application/json; charset=utf-8');
		$request = Request::instance();

		$data = $request->param();
		if(empty($id)){
			$arr =  array(
				'statuscode' => '53101',
				'msg' => '资讯ID未传递'
			);
		}else{
			$result = Db::table('yyhelper_news')->where('id',$id)->find();
			if($result){
				$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'news'=> $result
			);
			}else{
				$arr =  array(
				'statuscode' => '53102',
				'msg' => '无效的资讯ID'
			);
			}
			
		}
			

		exit(json_encode($arr));
	}

	/**
	 * 查找新闻列表
	 */
	public function Index(){
		header('Content-Type:application/json; charset=utf-8');
		$request = Request::instance();
		$data = $request->param();

		if(!empty($data['sort'])){
			//验证数据是否合格
			if($data['sort'] != 'time' && $data['sort'] != 'times'){
				//数据不合格
				$arr =  array(
				'statuscode' => '400',
				'msg' => '传入数据格式错误'
			);
			exit(json_encode($arr));
			}else{
				//判断是否限制降序
				if(!empty($data['desc'])){
					//降序
					if(!empty($data['limit'])){
						//限定获取条数
						if(!empty($data['universityId'])){
							//限制学校
							$newslist = Db::table('yyhelper_news')->where('university_id',$data['universityId'])->limit($data['limit'])->order($data['sort'],'desc')->select();
						}else{
							//查询全部
							$newslist = Db::table('yyhelper_news')->limit($data['limit'])->order($data['sort'],'desc')->select();
						}					
					}else{
						//不限定获取条数
						if(!empty($data['universityId'])){
							//限制学校
							$newslist = Db::table('yyhelper_news')->where('university_id',$data['universityId'])->order($data['sort'],'desc')->select();
						}else{
							//查询全部
							$newslist = Db::table('yyhelper_news')->order($data['sort'],'desc')->select();
						}
					}	
				}else{
					//升序
					if(!empty($data['limit'])){
						//限定获取条数
						if(!empty($data['universityId'])){
							//限制学校
							$newslist = Db::table('yyhelper_news')->where('university_id',$data['universityId'])->limit($data['limit'])->order($data['sort'],'asc')->select();
						}else{
							//查询全部
							$newslist = Db::table('yyhelper_news')->limit($data['limit'])->order($data['sort'],'asc')->select();
						}
					}else{
						//不限定获取条数
						if(!empty($data['universityId'])){
							//限制学校
							$newslist = Db::table('yyhelper_news')->where('university_id',$data['universityId'])->order($data['sort'],'asc')->select();
						}else{
							//查询全部
							$newslist = Db::table('yyhelper_news')->order($data['sort'],'asc')->select();
						}
					}
				}
			}					
		}else{
			//默认排列 时间顺序
				//判断是否限制降序
				if(!empty($data['desc'])){
					//降序
					if(!empty($data['limit'])){
						//限定获取条数
						if(!empty($data['universityId'])){
							//限制学校
							$newslist = Db::table('yyhelper_news')->where('university_id',$data['universityId'])->limit($data['limit'])->order('time','desc')->select();
						}else{
							//查询全部
							$newslist = Db::table('yyhelper_news')->limit($data['limit'])->order('time','desc')->select();
						}
					}else{
						//不限定获取条数
						if(!empty($data['universityId'])){
							//限制学校
							$newslist = Db::table('yyhelper_news')->where('university_id',$data['universityId'])->order('time','desc')->select();
						}else{
							//查询全部
							$newslist = Db::table('yyhelper_news')->order('time','desc')->select();
						}
					}	
				}else{
					//升序
					if(!empty($data['limit'])){
						//限定获取条数
						if(!empty($data['universityId'])){
							//限制学校
							$newslist = Db::table('yyhelper_news')->where('university_id',$data['universityId'])->limit($data['limit'])->order('time','asc')->select();
						}else{
							//查询全部
							$newslist = Db::table('yyhelper_news')->limit($data['limit'])->order('time','asc')->select();
						}
					}else{
						//不限定获取条数
						if(!empty($data['universityId'])){
							//限制学校
							$newslist = Db::table('yyhelper_news')->where('university_id',$data['universityId'])->order('time','asc')->select();
						}else{
							//查询全部
							$newslist = Db::table('yyhelper_news')->order('time','asc')->select();
						}
					}
				}
			
			
		}
		if($newslist){
			$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'newslist'=> $newslist
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