<?php
namespace app\manager\controller;
use app\manager\model\Articlelist;
use think\Controller;
use think\View;
use think\Request;
class product extends Controller
{
    public function product_list()
    {
        /*if(input('post.action')=='del'){
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
		}	*/	
		return $this->fetch();	
    }
	public function product_add()
    {
		return $this->fetch();	
    }	
	public function product_brand()
    {
		return $this->fetch();	
    }
	public function product_category()
    {
		return $this->fetch();	
    }	
	public function product_category_add()
    {
		return $this->fetch();	
    }	
}
