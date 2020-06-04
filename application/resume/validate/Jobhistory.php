<?php
namespace app\resume\validate;
use think\Validate;

class Jobhistory extends Validate
{

	protected $rule = [
		'userId' => 'require',
		'company_name' => 'require|min:2|max:20',
		'industry_id' => 'require',
		'start_time' => ['regex'=>'([0-9]{3}[1-9]|[0-9]{2}[1-9][0-9]{1}|[0-9]{1}[1-9][0-9]{2}|[1-9][0-9]{3})-(0[1-9]|1[0-2])$'],
		'end_time' => ['regex'=>'([0-9]{3}[1-9]|[0-9]{2}[1-9][0-9]{1}|[0-9]{1}[1-9][0-9]{2}|[1-9][0-9]{3})-(0[1-9]|1[0-2])$'],
		'job_id' => 'require',
		'department' => 'require|min:2|max:10',
		'info' => 'require|max:300',
		'achievement' => 'max:300',
		'type' => 'require'
		
	];

	protected $message = [
		'userId.require' => '用户id必须传递',
		'company_name.require' => '公司名称必须存在',
		'company_name.min' => '公司名称不得少于2个字符',
		'company_name.max' => '公司名称长不得大于20个字符',
		'industry_id.require' => '所在行业ID必须存在',
		'start_time.regex' => '开始时间格式错误',
		'end_time.regex' => '结束时间格式错误',
		'job_id.require' => '职业类型必须存在',
		'department.require' => '部门必须存在',
		'department.min' => '部门名称不得少于2个字符',
		'department.max' => '部门名称不得大于10个字符',
		'info.require' => '工作内容必须存在',
		'info.max' => '工作内容最多填写300个字符',
		'achievement.max' => '工作业绩最多填写300个字符',
		'type.require' => '简历类型必须传递'
		
	];

	protected $scene = [
		'update' => ['company_name','industry_id','start_time','end_time','job_id','department','info','achievement'],
		'select' => ['type','userId']
	];
}


?>