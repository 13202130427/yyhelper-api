<?php

namespace app\index\controller;
use think\Controller;
use think\Request;
class Index extends Controller{
  

  public function index(){


    $request = Request::instance();

    if($request->isGet()){
    	echo '请求方法:' . $request->method() . "</br>";
    }
    
    echo '访问地址:' . $request->ip() . "</br>";
    echo '请求参数:' ;
    dump($request->param());

  }


  
}
?>