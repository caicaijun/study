<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

class Cases extends Common
{
	public function overView()
	{
		return $this->fetch();
	}

	public function travel()
	{
		return $this->fetch();
	}

	public function travelShow()
	{
		return $this->fetch();
	}

    public function comfortable()
    {
		return $this->fetch();
    }
}
