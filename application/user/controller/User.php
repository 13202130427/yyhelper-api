<?php

namespace app\user\controller;
use think\Controller;
use think\Request;
use think\Db;
class User extends Controller{

/**
 * 获取用户信息
 * id int 用户ID
 */
public function user_info($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();
	if(empty($id)){
		$arr =  array(
				'statuscode' => '52001',
				'msg' => '用户ID未传递'
			);
			exit(json_encode($arr));
	}
	if(empty($data['phone']) || $data['phone'] == false){
		$user = Db::table('yyhelper_user')->where('id',$id)->field('name,photo')->find();
		if($user){
		$arr = array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'userInfo' =>['nickName'=>$user['name'],'avatarUrl'=>$user['photo']]
			);
		}else{
			$arr =  array(
				'statuscode' => '1002',
				'msg' => '无效的useId'
			);
		}
	}else{
		$user = Db::table('yyhelper_user')->where('id',$id)->field('name,photo,phone')->find();
		if($user){
		$arr = array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'userInfo' =>['nickName'=>$user['name'],'avatarUrl'=>$user['photo'],'phone'=>$user['phone']]
			);
		}else{
			$arr =  array(
				'statuscode' => '1002',
				'msg' => '无效的useId'
			);
		}
	}
	
	
	

	exit(json_encode($arr));
}
  
/**
 * open_id string 用户当前小程序的id凭证
 * session_key     string 系统生成的临时session
 * @return 3rd_session string 随机生成的新session
 */
public function user_exist(){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	
	$ip = $request->ip();
	$time = date('Y-m-d H:i:s', time());
	$data = $request->param();
	//dump($data);
	//信息筛选
	$validate = new \app\user\validate\UserId;
	if(!$validate->check($data)){
		$arr =  array(
			'statuscode' => '400',
			'msg' => $validate->getError(),
		);
	}else{
		//生成一个唯一的随机码
		$rand = md5('zyy2020'.$data['session_key']);
		//判断当前用户是否已登录过
		$result = Db::table('yyhelper_uid')->where('open_id',$data['open_id'])->find();
		if($result){
			//获取当前用户信息
			$result0 = Db::table('yyhelper_user')->where('uid',$result['id'])->find();
			//更新session_key和3rd_session
			$result1 = Db::table('yyhelper_uid')->where('open_id',$data['open_id'])->update(['session_key'=>$data['session_key'],'3rd_session'=>$rand]);
			//更新user数据
			$result2 = Db::table('yyhelper_user')->where('id',$result0['id'])->data(['uid'=>$result['id'],'ip'=>$ip,'login_times'=>$time])->update();

			//判断用户是否已注册成功
			if($result['status'] == '1'){
				$arr = array(
					'statuscode' => '200',
					'msg' => '当前用户已注册',
					'status' => $result['status'],
					'user_status'=>$result0['status'],
					'3rd_session' => $result['3rd_session'],
					'userId' => $result0['id']
				);
			}else{
				$arr = array(
					'statuscode' => '200',
					'msg' => '当前用户未注册',
					'status' => $result['status'],
					'user_status'=>$result0['status'],
					'3rd_session' => $result['3rd_session'],
					'userId' => $result0['id']
				);
			}
			
				
		}else{
			//创建uid表数据
			$uidin = Db::table('yyhelper_uid')->insertGetId(['open_id'=>$data['open_id'],'session_key'=>$data['session_key'],'status'=>'0','3rd_session'=>$rand]);
			//创建user表数据
			$result2 = Db::table('yyhelper_user')->insertGetId(['uid'=>$uidin,'ip'=>$ip,'login_times'=>$time,'create_time'=>$time]);
			if($uidin){
				$arr = array(
					'statuscode' => '200',
					'msg' => '当前用户未注册',
					'user_status' => '0',
					'status' => '0',
					'3rd_session' => $rand,
					'userId' => $result2
				);
			}else{
				$arr = array(
					'statuscode' => '999',
					'msg' => '操作数据库失败'
				);
			}
			
		}
	}
	

	exit(json_encode($arr));
}
public function user_status($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();
	if(empty($id)){
		$arr =  array(
				'statuscode' => '52001',
				'msg' => '用户ID未传递'
			);
			exit(json_encode($arr));
	}
	$user = Db::table('yyhelper_user')->where('id',$id)->field('status')->find();
	if($user){
		$arr = array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'status' =>$user['status']
			);
	}else{
		$arr =  array(
			'statuscode' => '1002',
			'msg' => '无效的useId'
		);
	}
	exit(json_encode($arr));	
}
/**
 * userId int 用户id
 * name: string 名字
 * sex: number 0未知1男2女
 * city: string 城市
 * province: string 省份
 * country: string 国家
 * photo: string 头像地址
 * @return name photo
 */
public function User_login(){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();

	$validate = new \app\user\validate\User;
	if(!$validate->check($data)){
		$arr =  array(
			'statuscode' => '400',
			'msg' => $validate->getError(),
		);
	}else{
		//判断当前用户是否已注册过
		$uid = Db::table('yyhelper_user')->where('id',$data['userId'])->find();
		$result = Db::table('yyhelper_uid')->where('id',$uid['uid'])->where('status',1)->find();
		if($result){
			//已注册
			$arr = array(
					'statuscode' => '200',
					'msg' => '登录成功',
					'userInfo' => ['nickName' => $uid['name'],
					'avatarUrl' => $uid['photo']]
					
				);
			exit(json_encode($arr));
		}
		$user = array(
			'name' => $data['name'], 
			'sex' => $data['sex'], 
			'city' => $data['city'], 
			'province' => $data['province'], 
			'country' => $data['country'], 
			'photo' => $data['photo']
			);
		
		$user_update= Db::table('yyhelper_user')->where('id',$data['userId'])->data($user)->update();
		$uid_update= Db::table('yyhelper_uid')->where('id',$uid['uid'])->data(['status'=>'1'])->update();
		if($user_update){
			$arr = array(
					'statuscode' => '200',
					'msg' => '注册成功进行登录',
					'userInfo' => ['nickName' => $data['name'],
					'avatarUrl' => $data['photo']]
					
				);
		}else{
			$arr =  array(
				'statuscode' => '999',
				'msg' =>'数据库操作失败',
			);
		}
		
	}	

	exit(json_encode($arr));
}
/**
 * 获取用户路线信息
 */
public function Routeview($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	
	$data = $request->param();
	if(empty($id)){
		$arr =  array(
			'statuscode' => '52001',
			'msg' => '用户ID未传递',
		);
		exit(json_encode($arr));
	}
	$result = Db::table('yyhelper_user')->where('id',$id)->field('route_id')->find();
	if($result){
		if($result['route_id'] == 0){
			//没有路线
			$arr =  array(
				'statuscode' => '52201',
				'msg' => '当前用户未选择路线',
			);
		}else{
			//有路线
			$result1 = Db::table('yyhelper_route')->where('id',$result['route_id'])->field('id,name,info')->find();
			if($result1){
				$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'userRoute' => $result1
			);
			}else{
				$arr =  array(
				'statuscode' => '51001',
				'msg' => '无效的路线ID',
			);
			}
		}
	}else{
		$arr =  array(
			'statuscode' => '1002',
			'msg' => '无效的userId',
		);
	}
		
		
	
	exit(json_encode($arr));
}
/**
 * 获取用户实名认证信息
 */
public function Auditperson($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	
	$data = $request->param();
	if(empty($id)){
		$arr =  array(
			'statuscode' => '52001',
			'msg' => '用户ID未传递',
		);
		exit(json_encode($arr));
	}
	$result = Db::table('yyhelper_user')->where('id',$id)->field('person_status')->find();
	if($result){
		if($result['person_status'] == 0){
				$arr =  array(
					'statuscode' => '200',
					'msg' => '请求成功',
					'audit' => '未实名'
				);
				exit(json_encode($arr));
		}
		if($result['person_status'] == 1){
			$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'audit' => '已实名'
			);
			exit(json_encode($arr));
		}
		if($result['person_status'] == 2){
				$arr =  array(
					'statuscode' => '200',
					'msg' => '请求成功',
					'audit' => '待审核'
				);
				exit(json_encode($arr));
		}
	}else{
		$arr =  array(
			'statuscode' => '1002',
			'msg' => '无效的userId',
		);
		exit(json_encode($arr));
	}
		
		
	
	exit(json_encode($arr));
}
/**
 * 获取用户学生认证信息
 */
public function Auditstudent($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	
	$data = $request->param();
	if(empty($id)){
		$arr =  array(
			'statuscode' => '52001',
			'msg' => '用户ID未传递',
		);
		exit(json_encode($arr));
	}
	$result = Db::table('yyhelper_user')->where('id',$id)->field('student_status')->find();
	if($result){
		if($result['student_status'] == 0){
			
				$arr =  array(
					'statuscode' => '200',
					'msg' => '请求成功',
					'audit' => '未认证'
				);
				exit(json_encode($arr));
		}
		if($result['student_status'] == 1){
			$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'audit' => '已认证'
			);
			exit(json_encode($arr));
		}
		if($result['student_status'] == 2){
			
				$arr =  array(
					'statuscode' => '200',
					'msg' => '请求成功',
					'audit' => '待审核'
					
				);
				exit(json_encode($arr));
		}
	}else{
		$arr =  array(
			'statuscode' => '1002',
			'msg' => '无效的userId',
		);
		exit(json_encode($arr));
	}
		
		
	
	exit(json_encode($arr));
}
/**
 * 获取用户认证记录信息
 */
public function Auditnote($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	
	$data = $request->param();
	if(empty($id)){
		$arr =  array(
			'statuscode' => '52001',
			'msg' => '用户ID未传递',
		);
		exit(json_encode($arr));
	}
	if(empty($data['type']) || $data['type'] == 'person'){
		//未实名 检测是否有实名记录
		 $result1 = Db::table('yyhelper_audit')->where('userId',$id)->where('type',0)->order('id', 'desc')->limit(1)->field("edu_bg,start_time,type,university_id,status,id,userId,update_time,delete_time",true)->select();
		 if($result1){
		 	$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'note' => $result1
			);
			exit(json_encode($arr));
		 }else{
		 	$arr =  array(
				'statuscode' => '404',
				'msg' => '请求的资源不存在'
			);
			exit(json_encode($arr));
		 }
	}
	if($data['type'] == 'student'){
		//未实名 检测是否有实名记录
		 $result1 = Db::table('yyhelper_audit')->alias('ya')->join('yyhelper_university yu','ya.university_id = yu.id')->where('ya.userId',$id)->where('ya.type',1)->order('ya.id', 'desc')->limit(1)->field("ya.username,ya.idcard,yu.name school,ya.edu_bg,ya.start_time,ya.cause")->select();
			if($result1){
			 	$arr =  array(
					'statuscode' => '200',
					'msg' => '请求成功',
					'note' => $result1
				);
				exit(json_encode($arr));
			 }else{
			 	$arr =  array(
					'statuscode' => '404',
					'msg' => '请求的资源不存在'
				);
				exit(json_encode($arr));
			 }
	}else{
		$arr =  array(
			'statuscode' => '52301',
			'msg' => '审核类型错误',
		);
		exit(json_encode($arr));
	}	
		
	
	exit(json_encode($arr));
}
/**
*获取用户上传的视频信息 
*/
public function Videolist($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();
	if(empty($id)){
		$arr =  array(
			'statuscode' => '52001',
			'msg' => '用户ID未传递',
		);
		exit(json_encode($arr));
	}
	$user = Db::table('yyhelper_user')->where('id',$id)->find();
	if(!$user){
		$arr =  array(
			'statuscode' => '1002',
			'msg' => '无效的userId'
		);
		exit(json_encode($arr));
	}
	$result = Db::table('yyhelper_video')->where('userId',$id)->select();
	if($result){
		$arr = array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'videolist' =>$result
			);
	}else{
		$arr = array(
				'statuscode' => '404',
				'msg' => '请求的资源不存在'
			);
	}
	exit(json_encode($arr));
}
/**
 * 创建实名认证审核数据
 */
public function createauditperson($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	
	$data = $request->param();
	if(empty($id)){
		$arr =  array(
			'statuscode' => '52001',
			'msg' => '用户ID未传递',
		);
		exit(json_encode($arr));
	}
	$result = Db::table('yyhelper_user')->where('id',$id)->find();
	if($result){
		if($result['person_status'] == 1 ||$result['person_status'] == 2){
			$arr =  array(
				'statuscode' => '52304',
				'msg' => '当前用户已提交审核或已实名',
			);
			exit(json_encode($arr));
		}
		if(empty($data['name'])){
			$arr =  array(
				'statuscode' => '52302',
				'msg' => '实名认证姓名未传递',
			);
			exit(json_encode($arr));
		}
		if(empty($data['idcard'])){
			$arr =  array(
				'statuscode' => '52303',
				'msg' => '实名认证身份证号码未传递',
			);
			exit(json_encode($arr));
		}
		$time = date('Y-m-d H:i:s', time());
		$result1 = Db::table('yyhelper_audit')->insert(['type'=>0,'username'=>$data['name'],'idcard'=>$data['idcard'],'time'=>$time,'userId'=>$id]);
		if($result1){
			$result2 = Db::table('yyhelper_user')->where('id',$id)->update(['person_status'=>2]);
			if($result2){
				$arr =  array(
					'statuscode' => '201',
					'msg' => '创建成功',
				);
				exit(json_encode($arr));
			}else{
				$arr =  array(
					'statuscode' => '999',
					'msg' => '操作数据库失败',
				);
				exit(json_encode($arr));
			}
		}else{
			$arr =  array(
				'statuscode' => '999',
				'msg' => '操作数据库失败',
			);
			exit(json_encode($arr));
		}
	}else{
		$arr =  array(
			'statuscode' => '1002',
			'msg' => '无效的userId',
		);
		exit(json_encode($arr));
	}
	
	exit(json_encode($arr));
}
/**
 * 创建学生认证审核数据
 */
public function createauditstudent($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	
	$data = $request->param();
	if(empty($id)){
		$arr =  array(
			'statuscode' => '52001',
			'msg' => '用户ID未传递',
		);
		exit(json_encode($arr));
	}
	$result = Db::table('yyhelper_user')->where('id',$id)->find();
	if($result){
		if($result['person_status'] == 0 ||$result['person_status'] == 2){
			$arr =  array(
				'statuscode' => '52305',
				'msg' => '请先实名认证',
			);
			exit(json_encode($arr));
		}
		if($result['student_status'] == 1 ||$result['person_status'] == 2){
			$arr =  array(
				'statuscode' => '52304',
				'msg' => '当前用户已提交审核或已实名',
			);
			exit(json_encode($arr));
		}
		if(empty($data['university_id'])){
			$arr =  array(
				'statuscode' => '52306',
				'msg' => '学生认证学校ID未传递',
			);
			exit(json_encode($arr));
		}
		if(empty($data['edu_bg'])){
			$arr =  array(
				'statuscode' => '52307',
				'msg' => '学生认证学校学历未传递',
			);
			exit(json_encode($arr));
		}
		if(empty($data['start_time'])){
			$arr =  array(
				'statuscode' => '52308',
				'msg' => '学生认证学校入学时间未传递',
			);
			exit(json_encode($arr));
		}
		$person = Db::table('yyhelper_audit')->where(['type'=>0,'status'=>1,'userId'=>$id])->find();
		if(!$person){
			$arr =  array(
				'statuscode' => '52305',
				'msg' => '请先实名认证',
			);
			exit(json_encode($arr));
		}
		$time = date('Y-m-d H:i:s', time());
		$result1 = Db::table('yyhelper_audit')->insert(['type'=>1,'username'=>$person['username'],'idcard'=>$person['idcard'],'time'=>$time,'userId'=>$id,'university_id'=>$data['university_id'],'edu_bg'=>$data['edu_bg'],'start_time'=>$data['start_time']]);
		if($result1){
			$result2 = Db::table('yyhelper_user')->where('id',$id)->update(['student_status'=>2]);
			if($result2){
				$arr =  array(
					'statuscode' => '201',
					'msg' => '创建成功',
				);
				exit(json_encode($arr));
			}else{
				$arr =  array(
					'statuscode' => '999',
					'msg' => '操作数据库失败',
				);
				exit(json_encode($arr));
			}
		}else{
			$arr =  array(
				'statuscode' => '999',
				'msg' => '操作数据库失败',
			);
			exit(json_encode($arr));
		}
	}else{
		$arr =  array(
			'statuscode' => '1002',
			'msg' => '无效的userId',
		);
		exit(json_encode($arr));
	}
	
	exit(json_encode($arr));
}
/**
 * 用户头像上传
 * */
public function Userphotoupload($id){
	header('Content-Type:text/html; charset=utf-8');
	$request = Request::instance();
	if(empty($id)){
		echo '用户ID未传递';
	}else{
		$file = request()->file('file');
		// 移动到框架应用根目录/uploads/ 目录下
		$info = $file->move( './uploads/userphoto');
		if($info){
		// 成功上传后 获取上传信息
		$path = str_replace('\\', '/', 'https://www.api.yyhelper.icu/uploads/userphoto/'.$info->getSaveName());
		$result = Db::table('yyhelper_user')->where('id',$id)->update(['photo'=>$path]);
		if($result){
			echo '上传成功';
		}else{
			echo '上传失败';
		}
		}else{
		// 上传失败获取错误信息
		echo $file->getError();
		}
	}
	
}
/**
 * 更新用户昵称信息
 */
public function Updateusername($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();

	if(empty($id)){
		$arr =  array(
			'statuscode' => '52001',
			'msg' => '用户ID未传递'
		);
		exit(json_encode($arr));
	}
	$user = Db::table('yyhelper_user')->where('id',$id)->find();
	if(!$user){
		$arr =  array(
			'statuscode' => '1002',
			'msg' => '无效的userId'
		);
		exit(json_encode($arr));
	}
	if(empty($data['name'])){
		$arr =  array(
			'statuscode' => '52101',
			'msg' => '用户昵称未传递'
		);
		exit(json_encode($arr));
	}
	$user_update = Db::table('yyhelper_user')->where('id',$id)->update(['name'=>$data['name']]);
	if($user_update){
		$arr = array(
				'statuscode' => '202',
				'msg' => '更新成功'
			);
	}else{
		$arr = array(
				'statuscode' => '999',
				'msg' => '操作数据库失败'
			);
	}
	

	exit(json_encode($arr));
}
/**
*
**/
 public function testphonecode($id,$code){
 	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	if(empty($id)){
		$arr =  array(
			'statuscode' => '52001',
			'msg' => '用户ID未传递'
		);
		exit(json_encode($arr));
	}
	$user = Db::table('yyhelper_user')->where('id',$id)->find();
	if(!$user){
		$arr =  array(
			'statuscode' => '1002',
			'msg' => '无效的userId'
		);
		exit(json_encode($arr));
	}
	if(empty($code)){
		$arr =  array(
			'statuscode' => '52106',
			'msg' => '用户手机验证码未传递'
		);
		exit(json_encode($arr));
	}
	$result = Db::table('yyhelper_user')->where('id',$id)->where('code',$code)->find();
	if($result){
		$arr = array(
				'statuscode' => '200',
				'msg' => '请求成功'
			);
		exit(json_encode($arr));
	}else{
		$arr = array(
				'statuscode' => '400',
				'msg' => '验证码错误'
			);
		exit(json_encode($arr));
	}
 }
/**
 * 更新用户手机号信息
 */
public function Updateuserphone($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();

	if(empty($id)){
		$arr =  array(
			'statuscode' => '52001',
			'msg' => '用户ID未传递'
		);
		exit(json_encode($arr));
	}
	$user = Db::table('yyhelper_user')->where('id',$id)->find();
	if(!$user){
		$arr =  array(
			'statuscode' => '1002',
			'msg' => '无效的userId'
		);
		exit(json_encode($arr));
	}
	if(empty($data['phone'])){
		$arr =  array(
			'statuscode' => '52102',
			'msg' => '用户手机号未传递'
		);
		exit(json_encode($arr));
	}
	$user_update = Db::table('yyhelper_user')->where('id',$id)->update(['phone'=>$data['phone']]);
	if($user_update){
		$arr = array(
				'statuscode' => '202',
				'msg' => '更新成功'
			);
	}else{
		$arr = array(
				'statuscode' => '999',
				'msg' => '操作数据库失败'
			);
	}
	

	exit(json_encode($arr));
}
/**
 * 更新用户学习路线
 */
public function Routeupdate($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();

	if(empty($id)){
		$arr =  array(
			'statuscode' => '52001',
			'msg' => '用户ID未传递'
		);
		exit(json_encode($arr));
	}
	if(empty($data['routeId'])){
		$arr =  array(
			'statuscode' => '52202',
			'msg' => '学习路线ID未传递'
		);
		exit(json_encode($arr));
	}
	$user = Db::table('yyhelper_user')->where('id',$id)->find();
	if(!$user){
		$arr =  array(
			'statuscode' => '1002',
			'msg' => '无效的userId'
		);
		exit(json_encode($arr));
	}
	$route = Db::table('yyhelper_route')->where('id',$data['routeId'])->find();
	if(!$route){
		$arr =  array(
			'statuscode' => '51001',
			'msg' => '无效的路线ID'
		);
		exit(json_encode($arr));
	}
	$user_update = Db::table('yyhelper_user')->where('id',$id)->update(['route_id'=>$data['routeId']]);
	if($user_update){
		$arr = array(
				'statuscode' => '202',
				'msg' => '更新成功'
			);
	}else{
		$arr = array(
				'statuscode' => '999',
				'msg' => '操作数据库失败'
			);
	}
	

	exit(json_encode($arr));
}
/**
 * 删除用户路线信息
 */
public function routedelete($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	
	$data = $request->param();
	if(empty($id)){
		$arr =  array(
			'statuscode' => '52001',
			'msg' => '用户ID未传递',
		);
		exit(json_encode($arr));
	}
	$result = Db::table('yyhelper_user')->where('id',$id)->update(['route_id'=>0,'study_id'=>0,'page'=>0,'speed'=>0]);
	if($result){
		$arr =  array(
			'statuscode' => '200',
			'msg' => '请求成功'
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
 * 查看用户路线详细信息
 */
public function Routemorelist($id){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$data = $request->param();
	if(empty($id)){
		$arr =  array(
				'statuscode' => '52001',
				'msg' => '用户ID未传递',
			);
		exit(json_encode($arr));
	}
	$result = Db::table('yyhelper_user')->where('id',$id)->find();
	if(!$result){
		$arr =  array(
			'statuscode' => '1002',
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
	$study = Db::table('yyhelper_study')->where('route_id',$result['route_id'])->order('sort','asc')->select();
	if($result['study_id'] == 0){
		foreach ($study as $key => $value) {
					$study[$key]['end'] = false;
				}
	}else{
		$result1 = Db::table('yyhelper_study')->where('id',$result['study_id'])->find();
		foreach ($study as $key => $value) {
					if($value['sort'] >= $result1['sort']){
						$study[$key]['end'] = false;
					}else{
						$study[$key]['end'] = true;
					}
					
				}
	}
	if($study){
    		$arr =  array(
				'statuscode' => '200',
				'msg' => '请求成功',
				'study' => $study
			);
	}else{
		$arr =  array(
			'statuscode' => '999',
			'msg' => '操作数据库失败'
		);
	}
	
	exit(json_encode($arr));
}
/**
 * phone number 手机号
 * type  test 验证 bind 绑定
 * 发送验证码
 */
public function sms($id,$phone){
	header('Content-Type:application/json; charset=utf-8');
	$request = Request::instance();
	$rand = rand(1000,9999);
	$data = $request->param();
	if(empty($id)){
		$arr =  array(
			'statuscode' => '52001',
			'msg' => '用户ID未传递'
		);
		exit(json_encode($arr));
	}
	$result =  Db::table('yyhelper_user')->where('id',$id)->find();
	if(!$result){
		$arr =  array(
			'statuscode' => '1002',
			'msg' => '无效的userId'
		);
		exit(json_encode($arr));
	}
	if(empty($phone)){
		$arr =  array(
			'statuscode' => '52102',
			'msg' => '用户手机号未传递'
		);
		exit(json_encode($arr));
	}
	if(empty($data['type']) ||$data['type'] == 'test' ){
		//验证手机号
		//判断当前手机号是否为当前用户所有
		if($result['phone'] != $phone){
			//手机号非为当前用户所有
			$arr =  array(
				'statuscode' => '52105',
				'msg' => '当前手机号和用户ID不匹配'
			);
			exit(json_encode($arr));
		}
	}else{
		if($data['type'] == 'bind'){
			//绑定新手机号
			//判断当前手机号是否已绑定
			$result =  Db::table('yyhelper_user')->where('phone',$phone)->find();
			if($result){
				if($result['id'] == $id){
					//手机号为当前用户所有
					$arr =  array(
						'statuscode' => '52104',
						'msg' => '请勿绑定相同手机号'
					);
					exit(json_encode($arr));
				}else{
					//手机号为当前用户所有
					$arr =  array(
						'statuscode' => '52103',
						'msg' => '当前手机号已绑定'
					);
					exit(json_encode($arr));
				}
			}
		}else{
			$arr =  array(
						'statuscode' => '400',
						'msg' => 'type类型错误请选择 test 或 bind'
					);
					exit(json_encode($arr));
		}
	}
	
	
	//参数设置
	require_once('Api.php');
	//南方短信节点url地址
	$url = 'http://api01.monyun.cn:7901/sms/v2/std/';
	//北方短信节点url地址
	//$url = 'http://api02.monyun.cn:7901/sms/v2/std/';
	$smsSendConn = new Api($url);
	$data=array();
	//设置账号(必填)
	$data['userid']='E109GM';
	//设置密码（必填.填写明文密码,如:1234567890）
	$data['pwd']='r59Q3N';
	///////////////////////////////////////////////////////////////////////////////////

	/*
	* 单条发送 接口调用
	*/
	// 设置手机号码 此处只能设置一个手机号码(必填)
	$data['mobile']=$phone;
	//设置发送短信内容(必填)
	$data['content']="验证码：" .$rand."，打死都不要告诉别人哦！";
	
	// 业务类型(可选)
	$data['svrtype']='';
	// 设置扩展号(可选)
	$data['exno']='';
	//用户自定义流水编号(可选)
	$data['custid']='';
	// 自定义扩展数据(可选)
	$data['exdata']='';
	try {
	    $result = $smsSendConn->singleSend($data);  

	    if ($result['result'] === 0) {
        	//发送成功
        	
        	$user = Db::table('yyhelper_user')->where('id',$id)->update(['code'=>$rand]);
        	
        	if($user){
        		$arr =  array(
				'statuscode' => '200',
				'msg' =>'请求成功'
			);
			exit(json_encode($arr));
        	}else{
        		$arr =  array(
				'statuscode' => '999',
				'msg' =>'操作数据库失败'
			);
			exit(json_encode($arr));
        	}
	    }else{
	    	$arr =  array(
				'statuscode' => $result['result']
			);
			exit(json_encode($arr));
	    }
	}catch (Exception $e) {
		$arr =  array(
				'statuscode' => '400',
				'msg' =>$e->getMessage()
			);
			exit(json_encode($arr));
	}
	
}


  
}
?>