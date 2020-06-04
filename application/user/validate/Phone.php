<?php
namespace app\user\validate;
use think\Validate;

class Phone extends Validate
{
	protected $rule = [
		'phone' => 'require|mobile',
		'open_id' => 'require',
		'3rd_session' => 'require'
	];

	protected $message = [
		'phone.require' => '手机必须存在',
		'phone.mobile' => '手机号格式错误',
		'open_id' => 'open_id必须存在'
		'3rd_session' => '3rd_session必须存在'
	];
}


?>.