<?php
namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

Class Order extends Command{
	protected function configure(){
		$this->setName('order')->setDescription('delete exceed time order');
	}
	protected function execute(Input $input,Output $output){
		 Db::name('productlist')->where('threecategory',0)->delete();
	}
}
