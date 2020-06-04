<?php
namespace app\resume\validate;
use think\Validate;

class Skill extends Validate
{

	protected $rule = [
		'userId' => 'require',
		'info' => 'require|min:1',
		'type' => 'require'
		
	];

	protected $message = [
		'userId.require' => '用户id必须传递',
		'info.require' => '掌握技能描述必须传递',
		'info.min' => '掌握技能描述最少填写1个字符',
		'type.require' => '简历类型必须传递'
		
	];

	protected $scene = [
		'update' => ['info'],
		'select' => ['type','userId']
	];
}


?>