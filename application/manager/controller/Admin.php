<?php
namespace app\manager\controller;
use app\manager\model\Sinouser;
use think\Controller;
use think\View;
use think\Request;
class Admin extends Controller
{
    public function admin_list()
    {
		if(input('post.action')=='del'){
			$sinouser=new Sinouser();
			$findwhere["ID"]=input('post.id/d');
			$sinouser->deleteByWhere($findwhere);
			$prompt['name']='success';
			$prompt['msg']='success！';
			return json($prompt);
			//echo input('post.id');
			
		}else{
			//var_dump(Request::instance()->param());
			$where["loginname"]=array('neq','');
			if(input('post.loginname/s')!=''){
				$where["loginname"]=array('eq',input('post.loginname/s'));
			}
			// 查询
			$list = db("sinouser")
				->where($where)
				->order("id desc")
				->paginate(config('paginate.list_rows'),false, [
					'query' => Request::instance()->param(),//不丢失已存在的url参数
				])
				;
			//echo db("sinouser")->getLastSql();
			// 获取分页显示
			$page = $list->render();
			// 模板变量赋值
			$this->assign('list', $list);
			$this->assign('page', $page);
			return $this->fetch();	
		}		
    }
	public function admin_add()
    {
        if(Request::instance()->isPost()){
			$sinouser=new Sinouser();
			$findwhere["LoginName"]=$adminname=input('post.adminName');
			$adminpass=input('post.password');
			$adminpass2=input('post.password2');
			if($adminpass!=$adminpass2){
				$prompt['name']='passsame';
				$prompt['msg']='您输入的密码不一致！';
				return json($prompt);
			}
			$findresult=$sinouser->find($findwhere);
			if($findresult){
				$prompt['name']='namesame';
				$prompt['msg']='此管理员已经存在！';
				return json($prompt);	
			}
			
			//$this->ajaxReturn($prompt);
			
			$data["LoginName"]=$adminname;
			$data["LoginPwd"]=md5(md5($adminpass));
			$result=$sinouser->insert($data);
			echo $result;
		}else{
			return view('admin_add');
		}		
    }
	public function admin_edit()
    {
		$sinouser=new Sinouser();
		if(Request::instance()->isPost()){
			$adminname=input('post.adminName/s');
			$adminpass=input('post.password');
			$sex=input('post.sex/d');
			$tbmobile=input('post.phone/s');
			$remark=input('post.remark/s');
			$findwhere["LoginName"]=$adminname;
			$findresult=$sinouser->find($findwhere);			
			if(!$findresult){
				$prompt['name']='namenotexists';
				$prompt['msg']='此管理员不存在！';
				return json($prompt);	
			}
			if($adminpass!=""){
				$adminpass2=input('post.password2');
				if(md5(md5($adminpass))!=md5(md5($adminpass2))){
					$prompt['name']='adminpassnotsame';
					$prompt['msg']='您输入的密码不一致！';
					return json($prompt);	
				}
				$userdata["LoginPwd"]=md5(md5($adminpass2));
			}
			$userdata["Sex"]=$sex;
			$userdata["tbmobile"]=$tbmobile;
			$userdata["remark"]=$remark;
			//$updateuserxx=db("sinouser")->where($findwhere)->update($userdata);
			$updateuserxx=$sinouser->updateByWhere($findwhere,$userdata);	
			if($updateuserxx===false){
				$prompt['name']='updateerror';
				$prompt['msg']='信息更改有误！';
				return json($prompt);
			}
		}else{
			$id=input('id/d');
			$findwhere["ID"]=array("eq",$id);
			$userxx=$sinouser->find($findwhere);
			$this->assign('userxx', $userxx);
			return $this->fetch();
		}		
	}
}
