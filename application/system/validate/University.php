<?php
namespace app\system\validate;
use think\Validate;

class University extends Validate
{
	protected $rule = [
		'region' => 'require|min:3',
		'city' => 'require|min:3',
	];

	protected $message = [
		'region.require' => '区必须存在',
		'region.min' => '区长度必须大于3',
		'city.require' => '市必须存在',
		'city.min' => '市长度必须大于3',
	];
}


?>