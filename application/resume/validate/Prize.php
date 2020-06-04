<?php
namespace app\resume\validate;
use think\Validate;

class Prize extends Validate
{

	protected $rule = [
		'userId' => 'require',
		'info' => 'require|min:1',
		'type' => 'require'
		
	];

	protected $message = [
		'userId.require' => '用户id必须传递',
		'info.require' => '个人奖项描述必须传递',
		'info.min' => '个人奖项描述最少填写1个字符',
		'type.require' => '简历类型必须传递'
		
	];

	protected $scene = [
		'update' => ['info'],
		'select' => ['type','userId']
	];
}


?>