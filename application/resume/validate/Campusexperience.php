<?php
namespace app\resume\validate;
use think\Validate;

class Campusexperience extends Validate
{

	protected $rule = [
		'userId' => 'require',
		'company_name' => 'require|min:2|max:20',
		'start_time' => ['regex'=>'([0-9]{3}[1-9]|[0-9]{2}[1-9][0-9]{1}|[0-9]{1}[1-9][0-9]{2}|[1-9][0-9]{3})-(0[1-9]|1[0-2])$'],
		'start_time' => 'require',
		'end_time' => ['regex'=>'([0-9]{3}[1-9]|[0-9]{2}[1-9][0-9]{1}|[0-9]{1}[1-9][0-9]{2}|[1-9][0-9]{3})-(0[1-9]|1[0-2])$'],
		'end_time' => 'require',
		'department' => 'require|min:2|max:10',
		'info' => 'max:300',
		'type' => 'require'
		
	];

	protected $message = [
		'userId.require' => '用户id必须传递',
		'company_name.require' => '组织名称必须存在',
		'company_name.min' => '组织名称不得少于2个字符',
		'company_name.max' => '组织名称长不得大于20个字符',
		'start_time.require'=>'开始时间必须存在',
		'start_time.regex' => '开始时间格式错误',
		'end_time.regex' => '结束时间格式错误',
		'end_time.require'=>'结束时间必须存在',
		'department.require' => '部门/岗位必须存在',
		'department.min' => '部门/岗位名称不得少于2个字符',
		'department.max' => '部门/岗位名称不得大于10个字符',
		'info.max' => '工作内容最多填写300个字符',
		'type.require' => '简历类型必须传递'
		
	];

	protected $scene = [
		'update' => ['company_name','start_time','end_time','department','info'],
		'select' => ['type','userId']
	];
}


?>