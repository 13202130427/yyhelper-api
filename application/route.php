<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;


Route::get('/','/index/index/index');
//微信端
Route::get('1.0/weixin/getopenid','weixin/weixin/getopenid');
//工作经历
Route::post('1.0/jobhistories','resume/jobhistory/create');
Route::put('1.0/jobhistories/:id','resume/jobhistory/update');
Route::delete('1.0/jobhistories/:id','resume/jobhistory/delete');
Route::get('1.0/jobhistories/:id','resume/jobhistory/view');
Route::get('1.0/jobhistories','resume/jobhistory/index');
//校园经历
Route::post('1.0/campusexperiences','resume/campusexperience/create');
Route::put('1.0/campusexperiences/:id','resume/campusexperience/update');
Route::delete('1.0/campusexperiences/:id','resume/campusexperience/delete');
Route::get('1.0/campusexperiences/:id','resume/campusexperience/view');
Route::get('1.0/campusexperiences','resume/campusexperience/index');
//项目经历
Route::post('1.0/projectexperiences','resume/projectexperience/create');
Route::put('1.0/projectexperiences/:id','resume/projectexperience/update');
Route::delete('1.0/projectexperiences/:id','resume/projectexperience/delete');
Route::get('1.0/projectexperiences/:id','resume/projectexperience/view');
Route::get('1.0/projectexperiences','resume/projectexperience/index');
//个人奖项
Route::post('1.0/prizes','resume/prize/create');
Route::put('1.0/prizes/:id','resume/prize/update');
Route::delete('1.0/prizes/:id','resume/prize/delete');
Route::get('1.0/prizes/:id','resume/prize/view');
Route::get('1.0/prizes','resume/prize/index');
//掌握技能
Route::post('1.0/skills','resume/skill/create');
Route::put('1.0/skills/:id','resume/skill/update');
Route::delete('1.0/skills/:id','resume/skill/delete');
Route::get('1.0/skills/:id','resume/skill/view');
Route::get('1.0/skills','resume/skill/index');
//职业类型
Route::get('1.0/posts','system/post/Index');
Route::get('1.0/posts/three','system/post/searchkey');
//行业类型
Route::get('1.0/industries','system/industry/industry');
Route::get('1.0/industries/two','system/industry/searchkey');
//大学定位
Route::get('1.0/universities','system/university/university');
//院系信息
Route::get('1.0/colleges','system/colleges/colleges');
//用户
Route::get('1.0/users/route/more/:id','user/user/routemorelist');
Route::get('1.0/users/route/:id','user/user/routeview');
Route::get('1.0/users/status/:id','user/user/user_status');
Route::get('1.0/users/auditperson/:id','user/user/auditperson');
Route::get('1.0/users/auditstudent/:id','user/user/auditstudent');
Route::get('1.0/users/auditnote/:id','user/user/auditnote');
Route::get('1.0/users/video/:id','user/user/videolist');
Route::get('1.0/users/:id','user/user/user_info');
Route::put('1.0/users/name/:id','user/user/updateusername');
Route::put('1.0/users/phone/:id','user/user/updateuserphone');
Route::put('1.0/users/route/:id','user/user/routeupdate');
Route::put('1.0/users','user/user/user_login');
Route::post('1.0/users/photo/:id','user/user/userphotoupload');
Route::post('1.0/users/auditperson/:id','user/user/createauditperson');
Route::post('1.0/users/auditstudent/:id','user/user/createauditstudent');
Route::post('1.0/users','user/user/user_exist');
Route::delete('1.0/users/route/:id','user/user/routedelete');
//短信验证
Route::get('1.0/sms/:id/:code','user/user/testphonecode');
Route::put('1.0/sms/:id/:phone','user/user/sms');
//新闻板块
Route::get('1.0/news/:id','news/newslist/view');
Route::get('1.0/news','news/newslist/index');
//求职意向
Route::put('1.0/jobintentions/:userId','base/jobintention/update');
Route::get('1.0/jobintentions/:userId','base/jobintention/view');
//附件简历
Route::get('1.0/resumes/:userId','resume/resume/select');
Route::post('1.0/resumes','resume/resume/upload');
//视频列表
Route::get('1.0/videos/:id','video/video/view');
Route::get('1.0/videos','video/video/index');
Route::post('1.0/videos/videoinfo','video/video/createinfo');
Route::post('1.0/videos','video/video/create');

//路线列表
Route::get('1.0/routes/:id','route/route/view');
Route::get('1.0/routes','route/route/index');
//学习
Route::get('1.0/studies/:userId','route/study/studyspeed');
Route::put('1.0/studies/:userId','route/study/update');
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
