<?php
namespace app\resume\validate;
use think\Validate;

class Projectexperience extends Validate
{

	protected $rule = [
		'userId' => 'require',
		'project_name' => 'require|min:2|max:20',
		'role' => 'require',
		'start_time' => ['regex'=>'([0-9]{3}[1-9]|[0-9]{2}[1-9][0-9]{1}|[0-9]{1}[1-9][0-9]{2}|[1-9][0-9]{3})-(0[1-9]|1[0-2])$'],
		'end_time' => ['regex'=>'([0-9]{3}[1-9]|[0-9]{2}[1-9][0-9]{1}|[0-9]{1}[1-9][0-9]{2}|[1-9][0-9]{3})-(0[1-9]|1[0-2])$'],
		'info' => 'require|max:300',
		'url' => 'max:300',
		'achievement' => 'max:300',
		'type' => 'require'
		
	];

	protected $message = [
		'userId.require' => '用户id必须传递',
		'project_name.require' => '项目名称必须存在',
		'project_name.min' => '项目名称不得少于2个字符',
		'project_name.max' => '项目名称长不得大于20个字符',
		'role.require' => '担任角色必须存在',
		'start_time.regex' => '开始时间格式错误',
		'end_time.regex' => '结束时间格式错误',
		'info.require' => '项目描述必须存在',
		'info.max' => '项目描述最多填写300个字符',
		'url.max' => '项目链接长度最多不得超过300个字符',
		'achievement.max' => '项目业绩最多填写300个字符',
		'type.require' => '简历类型必须传递'
		
	];

	protected $scene = [
		'update' => ['project_name','role','start_time','end_time','info','url','achievement'],
		'select' => ['type','userId']
	];
}


?>