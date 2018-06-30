<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

class Index extends Common
{
	public function _initialize()
	{
		parent::_initialize();
	}

    public function index()
    {
    	$banner = Db::name('Ad')->field('ad_pic')->where('pid', 1)->select();
    	$this->assign('ban', $banner);
		return $this->fetch();
    }
}
