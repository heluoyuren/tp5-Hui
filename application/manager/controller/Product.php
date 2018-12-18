<?php
namespace app\manager\controller;
use app\manager\model\Productcategory;
use app\manager\model\Productlist;
use think\Controller;
use think\View;
use think\Request;
use news\Products;
class product extends Controller
{
    public function product_list()
    {
        $productlist=new Productlist();
		if(input('post.action')=='del'){
			
			$findwhere["ID"]=input('post.id/d');
			$productlist->deleteByWhere($findwhere);
			$prompt['name']='success';
			$prompt['msg']='success！';
			return json($prompt);
			//echo input('post.id');
			
		}else{
			//var_dump(Request::instance()->param());
			$where["proname"]=array('neq','');
			if(input('proname/s')!=''){
				$where["proname"]=array('like','%'.input('proname/s').'%');
			}
			if(input('articlecolumn/d')!=0){
				$catedata=procategoryallbyprocateid(input('articlecolumn/d'));
				
				$where["onecategory"]=$catedata["onecategory"];
				$where["twocategory"]=$catedata["twocategory"];
				$where["threecategory"]=$catedata["threecategory"];
			}
			// 查询
			$list = db("productlist")
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
				if($v['onecategory']!=0){
					$data[$k]['onecategoryname'] =procategorynamebyprocateid($v['onecategory']);
				}
				if($v['twocategory']!=0){
					$data[$k]['twocategoryname'] =procategorynamebyprocateid($v['twocategory']);
				}
				if($v['threecategory']!=0){
					$data[$k]['threecategoryname'] =procategorynamebyprocateid($v['threecategory']);
				}
				
			}

			//echo db("sinouser")->getLastSql();			
			// 模板变量赋值
			$this->assign('list', $data);
			$this->assign('page', $page);
			return $this->fetch();	
		}		
		
    }
	public function product_add()
    {
		$productcategory=new Productcategory();
		if(Request::instance()->isPost()){
			$data["proname"]=$proname=input('post.proname/s');
			$data["articlecolumn"]=$articlecolumn=input('post.articlecolumn/d');
			$data["keywords"]=$keywords=input('post.keywords/s');
			$data["abstract"]=$abstract=input('post.abstract/s');
			$data["thumb"]=$thumb=input('post.thumb/s');
			$data["content"]=$content=input('post.editorValue');
			
			$product=new products($proname,$articlecolumn,$keywords,$abstract,$thumb,$content);
			$result=$product->product_add();
			if($result=="pronamenotempty"){
				$prompt['name']='adderror';
				$prompt['msg']='产品名称不能为空！';
				return json($prompt);	
			}
			if($result=="articlecolumnnotempty"){
				$prompt['name']='adderror';
				$prompt['msg']='产品栏目不能为空！';
				return json($prompt);	
			}
			if($result=="contentnotempty"){
				$prompt['name']='adderror';
				$prompt['msg']='产品详情不能为空！';
				return json($prompt);	
			}
			echo $result;
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
	public function product_edit()
    {
        if(Request::instance()->isPost()){
			$articletitle=input('post.articletitle/s');
			$articlecolumn=input('post.articlecolumn/d');
			$keywords=input('post.keywords/s');
			$abstract=input('post.abstract/s');
			$thumb=input('post.thumb/s');
			$content=input('post.editorValue');
			$id=input('post.id/d');
			if($id==""){
				$prompt['name']='editerror';
				$prompt['msg']='产品ID不能为空！';
				return json($prompt);	
			}
			$product=new products($proname,$articlecolumn,$keywords,$abstract,$thumb,$content,$id);
			$upresult=$product->product_edit();
			if($upresult==="pronamenotempty"){
				$prompt['name']='editerror';
				$prompt['msg']='产品标题不能为空！';
				return json($prompt);	
			}
			if($upresult==="contentnotempty"){
				$prompt['name']='editerror';
				$prompt['msg']='产品详情不能为空！';
				return json($prompt);	
			}
			//$articleadd=new Articlelist();
//			$result=$articleadd->insert($data);
		}else{
			$id=input('id/d');
			if($id==""){
				echo "产品ID不能为空";
				return;
			}
			$findwhere["ID"]=array("eq",$id);			
			$productlist=new Productlist();
			$xx=$productlist->find($findwhere);
			//$articlexx["articlecolumnname"]=$articlecolumnname;
			$this->assign('xx', $xx);
			$this->assign('onecategory', procategorynamebyprocateid($xx["onecategory"]));
			$this->assign('twocategory', procategorynamebyprocateid($xx["twocategory"]));
			$this->assign('threecategory', procategorynamebyprocateid($xx["threecategory"]));
			//$this->assign('articlecolumnname', $articlecolumnname);
			return $this->fetch();
		}			
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
