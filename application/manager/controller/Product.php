<?php
namespace app\manager\controller;
use app\manager\model\Productcategory;
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
		if(Request::instance()->isPost()){
			$articlecolumn=input('post.articlecolumn/d');
			$productcategoryname=input('post.product-category-name/s');
			$memo=input('post.memo/s');
			if($productcategoryname==''){
				$prompt['name']='adderror';
				$prompt['msg']='产品分类不能为空！';
				return json($prompt);
			}
		}else{
			$productcategory=new Productcategory();
			$where["name"]=array('neq','');
			$pcatelist=$productcategory->findAllByWhere($where);
			foreach($pcatelist as $k=>$val){
				$jsondata[$k]["id"]=$val["id"];
				$jsondata[$k]["pId"]=$val["pId"];
				$jsondata[$k]["name"]=$val["name"];
				$jsondata[$k]["urlsrc"]="product_category_edit/articlecolumn/".$val["id"];
				if($k==0){
					$jsondata[$k]["open"]=true;
				}
			}
			/*echo json_encode($jsondata);
			echo "<br>--------------------------------------<br>";*/
			$this->assign('jsondata',json_encode($jsondata));
			return $this->fetch();
		}	
    }	
	public function product_category_add()
    {
		$productcategory=new Productcategory();
		if(Request::instance()->isPost()){
			$articlecolumn=input('post.articlecolumn/d');
			$productcategoryname=input('post.product-category-name/s');
			$memo=input('post.memo/s');
			if($productcategoryname==''){
				$prompt['name']='adderror';
				$prompt['msg']='产品分类不能为空！';
				return json($prompt);
			}
			$pid=$articlecolumn;
			$level=1;
			if($articlecolumn!=0){
				$where["id"]=$articlecolumn;
				$acxx=$productcategory->find($where);
				if($acxx){
					$level=$acxx["level"]+1;
					if($level>3){
						$prompt['name']='adderror';
						$prompt['msg']='您选的分类不能作为父级栏目！';
						return json($prompt);
					}
				}
			}
			$data["pId"]=$pid;
			$data["name"]=$productcategoryname;
			$data["memo"]=$memo;
			$data["level"]=$level;
			$productcategory->insert($data);
		}else{
			$where["level"]=array('eq',1);
			$pcatelist=$productcategory->findAllByWhere($where);			
			foreach($pcatelist as $k=>$val){
				$wheretwo["pId"]=array('eq',$val["id"]);
				$wheretwo["level"]=array('eq',2);
				$pcatelist[$k]["two"]=$productcategory->findAllByWhere($wheretwo);
				foreach($pcatelist[$k]["two"] as $ktwo=>$vtwo){
					$zjlist=$pcatelist[$k]["two"][$ktwo];
					$wherethree["pId"]=array('eq',$vtwo["id"]);
					$wherethree["level"]=array('eq',3);
					$zjlist["three"]=$productcategory->findAllByWhere($wherethree);
					//$pcatelist[$k]["two"]=$zjlist["three"];
				}
//				$pcatelist[$k]["three"]=$zjlist;
			}
			//echo "<pre>";
//			print_r($pcatelist);
			$this->assign("pcatelist",$pcatelist);
			return $this->fetch();
		}	
    }
	public function product_category_edit()
    {
		$productcategory=new Productcategory();
		if(Request::instance()->isPost()){
			$id=input('post.id/d');
			$productcategoryname=input('post.product-category-name/s');
			$memo=input('post.memo/s');
			if($id==''){
				$prompt['name']='adderror';
				$prompt['msg']='参数非法！';
				return json($prompt);
			}
			if($productcategoryname==''){
				$prompt['name']='adderror';
				$prompt['msg']='产品分类名称不能为空！';
				return json($prompt);
			}
				$where["id"]=$id;
				$data["name"]=$productcategoryname;
				$data["memo"]=$memo;
				$acxx=$productcategory->updateByWhere($where,$data);				
			
		}else{
			$articlecolumn=input("articlecolumn/d");
			$where["id"]=$articlecolumn;
			$acxx=$productcategory->find($where);
			$this->assign("articlecolumn",$articlecolumn);
			$this->assign("acxx",$acxx);
			return $this->fetch();
		}	
    }	
}
