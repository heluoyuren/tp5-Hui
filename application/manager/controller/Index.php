<?php
namespace app\manager\controller;
use app\manager\model\Sinouser;
use think\Controller;
use think\View;
use think\Request;
use think\Session;
class index extends Controller
{
    public function index()
    {
        return view('index');
    }
	public function login()
    {
        if(Request::instance()->isPost()){
			$adminname=input('post.adminname');
			$adminpass=input('post.adminpass');
			if(empty($adminname) || empty($adminpass)){
				$prompt['name']='loginerror';
				$prompt['msg']='用户名或者密码不能为空！';
				return json($prompt);
			}
			/*$data = SinouserModel::get('1');
			 $this->assign('data', $data);
			var_dump($data);*/
			$sinouser=new Sinouser();
			$where["LoginName"]=$adminname;
			$where["LoginPwd"]=md5(md5($adminpass));
			$result=$sinouser->find($where);
			if($result){
				Session::set("adminname",$adminname);
			}else{
				$prompt['name']='loginerror';
				$prompt['msg']='用户名或密码错误！';
				return json($prompt);
				exit;
			}
		}else{
			return $this->fetch();
		}		
    }
	public function welcome()
    {
        return view('welcome');
    }
}
