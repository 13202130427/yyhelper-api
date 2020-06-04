<?php
namespace app\user\validate;
use think\Validate;
class User extends Validate
{
	protected $rule = [
		'userId' => 'require',
		'name' => 'require',
		'sex' => 'require',
		'city' => 'require',
		'province' => 'require',
		'country' => 'require',
		'photo' => 'require',
	];

	protected $message = [
		'userId.require' => 'userId必须存在',
		'name.require' => 'name必须存在',
		'sex.require' => 'sex必须存在',
		'city.require' => 'city必须存在',
		'province.require' => 'province必须存在',
		'country.require' => 'country必须存在',
		'photo.require' => 'photo必须存在',
	];

	protected $scene = [
		'userinfo' => ['userId']
	];
}


?>