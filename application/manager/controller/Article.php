<?php
namespace app\manager\controller;
use app\manager\model\Articlelist;
use think\Controller;
use think\View;
use think\Request;
use news\Articles;
class article extends Controller
{
    public function article_list()
    {
        if(input('post.action')=='del'){
			$articlelist=new Articlelist();
			$findwhere["ID"]=input('post.id/d');
			$articlelist->deleteByWhere($findwhere);
			$prompt['name']='success';
			$prompt['msg']='success！';
			return json($prompt);
			//echo input('post.id');
			
		}else{
			//var_dump(Request::instance()->param());
			$where["articletitle"]=array('neq','');
			$where["articlecolumn"]=array('neq',0);
			if(input('articletitle/s')!=''){
				$where["articletitle"]=array('like','%'.input('articletitle/s').'%');
			}
			if(input('articlecolumn/d')!=0){
				$where["articlecolumn"]=array('eq',input('articlecolumn/d'));
			}
			// 查询
			$list = db("articlelist")
				->where($where)
				->order("id desc")
				->paginate(config('paginate.list_rows'),false, [
					'query' => Request::instance()->param(),//不丢失已存在的url参数
				])
				;
			// 获取分页显示
			$page = $list->render();
			$data = $list->all();
			foreach ($data as $k => $v){
				if($v['articlecolumn']==1){
					$data[$k]['articlecolumnname'] =' 新闻资讯';
				}else{
					$data[$k]['articlecolumnname'] =' 技术中心';
				}
				
			}

			//echo db("sinouser")->getLastSql();			
			// 模板变量赋值
			$this->assign('list', $data);
			$this->assign('page', $page);
			return $this->fetch();	
		}		
    }
	public function article_class()
    {
        return $this->fetch();	
    }
	public function article_class_edit()
    {
        return $this->fetch();	
    }
	public function article_add()
    {
        if(Request::instance()->isPost()){
			$data["articletitle"]=$articletitle=input('post.articletitle/s');
			$data["articlecolumn"]=$articlecolumn=input('post.articlecolumn/d');
			$data["keywords"]=$keywords=input('post.keywords/s');
			$data["abstract"]=$abstract=input('post.abstract/s');
			$data["author"]=$author=input('post.author/s');
			$data["sources"]=$sources=input('post.sources/s');
			$data["thumb"]=$thumb=input('post.thumb/s');
			$data["content"]=$content=input('post.editorValue');
			
			$article=new articles($articletitle,$articlecolumn,$content,$keywords,$abstract,$author,$sources,$thumb);
			$result=$article->article_add();
			if($result=="articletitlenotempty"){
				$prompt['name']='adderror';
				$prompt['msg']='文章标题不能为空！';
				return json($prompt);	
			}
			if($result=="articlecolumnnotempty"){
				$prompt['name']='adderror';
				$prompt['msg']='文章栏目不能为空！';
				return json($prompt);	
			}
			if($result=="contentnotempty"){
				$prompt['name']='adderror';
				$prompt['msg']='文章内容不能为空！';
				return json($prompt);	
			}
			/*$articleadd=new Articlelist();
			$result=$articleadd->insert($data);*/
			echo $result;
		}else{
			return $this->fetch();
		}			
    }
	public function article_edit()
    {
        if(Request::instance()->isPost()){
			$articletitle=input('post.articletitle/s');
			$articlecolumn=input('post.articlecolumn/d');
			$keywords=input('post.keywords/s');
			$abstract=input('post.abstract/s');
			$author=input('post.author/s');
			$sources=input('post.sources/s');
			$thumb=input('post.thumb/s');
			$content=input('post.editorValue');
			$articleid=input('post.id/d');
			if($articleid==""){
				$prompt['name']='editerror';
				$prompt['msg']='文章ID不能为空！';
				return json($prompt);	
			}
			$article=new articles($articletitle,$articlecolumn,$content,$keywords,$abstract,$author,$sources,$thumb,$articleid);
			$upresult=$article->article_edit();
			if($upresult==="articletitlenotempty"){
				$prompt['name']='editerror';
				$prompt['msg']='文章标题不能为空！';
				return json($prompt);	
			}
			if($upresult==="articlecolumnnotempty"){
				$prompt['name']='editerror';
				$prompt['msg']='文章栏目不能为空！';
				return json($prompt);	
			}
			if($upresult==="contentnotempty"){
				$prompt['name']='editerror';
				$prompt['msg']='文章内容不能为空！';
				return json($prompt);	
			}
			//$articleadd=new Articlelist();
//			$result=$articleadd->insert($data);
		}else{
			$articleid=input('id/d');
			if($articleid==""){
				echo "文章ID不能为空";
				return;
			}
			$findwhere["ID"]=array("eq",$articleid);			
			$articlelist=new Articlelist();
			$articlexx=$articlelist->find($findwhere);
			if($articlexx["articlecolumn"]==1){
				$articlecolumnname='文章资讯';
			}
			if($articlexx["articlecolumn"]==2){
				$articlecolumnname='技术中心';
			}
			//$articlexx["articlecolumnname"]=$articlecolumnname;
			$this->assign('articlexx', $articlexx);
			$this->assign('articlecolumnname', $articlecolumnname);
			return $this->fetch();
		}			
    }
	public function uppic()
	{

		$filename = time().substr($_FILES['photo']['name'], strrpos($_FILES['photo']['name'],'.'));
		$response = array();

		if(move_uploaded_file($_FILES['photo']['tmp_name'], ROOT_PATH.'uploads/'.$filename)){
			$response['isSuccess'] = true;
			$response['photo'] = $filename;
		}else{
			$response['isSuccess'] = false;
		}

		echo json_encode($response);
	}
}
