<?php
namespace app\admin\controller;

use Think\Controller;
use think\Db;
use think\Env;
use think\Config;
use app\common\controller\Index as commonIndex;

class Index extends Common
{
	/*public function __construct()
	{
		parent::__construct();
		// config('akey', 'avalue'); //局部动态配置
		// $sta = config('app_status');	//获取配置值
	}*/

    public function index()
    {
       return $this->fetch();
    }
}