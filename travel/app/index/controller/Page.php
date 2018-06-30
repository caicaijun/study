<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

class Page extends Common
{
	public function feelback()
	{
		return $this->fetch();
	}

	public function map()
	{
		return $this->fetch();
	}

	public function contact()
	{
		return $this->fetch();
	}
}
