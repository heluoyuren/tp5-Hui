<?php
namespace app\manager\controller;
use think\Controller;
use think\Session;
class auth extends Controller
{
    function _initialize()
    {			
		if(!Session::has('adminname')){
			session(null); 
			$this->error("您还未登录或已成功退出！","Index/login");
		}
    }
	
}
