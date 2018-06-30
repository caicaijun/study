<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

class News extends Common
{
	public function news()
	{
		return $this->fetch();
	}

	public function newsshow()
	{
		return $this->fetch();
	}
}
