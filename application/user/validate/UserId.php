<?php
namespace app\user\validate;
use think\Validate;

class UserId extends Validate
{
	protected $rule = [
		'open_id' => 'require',
		'session_key' => 'require',
	];

	protected $message = [
		'open_id.require' => 'open_id必须存在',
		'session_key.require' => 'session_key必须存在'
	];
}


?>