<?php
use app\manager\model\Productcategory;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//通过产品栏目ID获得产品栏目名称
function procategorynamebyprocateid($cateid){
	$productcategory=new Productcategory();
	$onecategory=0;
	$twocategory=0;
	$threecategory=0;
	$wherecate["id"]=$cateid;
	$lmxx=$productcategory->find($wherecate);
	return $lmxx["name"];
}
//通过产品栏目ID获得产品栏目详细  即一级栏目 二级栏目 三级栏目
function procategoryallbyprocateid($cateid){
	$productcategory=new Productcategory();
	$onecategory=0;
	$twocategory=0;
	$threecategory=0;
	$wherecate["id"]=$cateid;
	$lmxx=$productcategory->find($wherecate);
	if($lmxx["level"]==1){
		$onecategory=$cateid;
	}
	if($lmxx["level"]==2){
		$onecategory=$lmxx["pId"];
		$twocategory=$cateid;
	}
	if($lmxx["level"]==3){
		$wherecatet["id"]=$lmxx["pId"];
		$lmxxt=$productcategory->find($wherecatet);
		$onecategory=$lmxxt["pId"];
		$twocategory=$lmxx["pId"];
		$threecategory=$cateid;
	}

	$catedata["onecategory"]=$onecategory;
	$catedata["twocategory"]=$twocategory;
	$catedata["threecategory"]=$threecategory;
	
	return $catedata;
}