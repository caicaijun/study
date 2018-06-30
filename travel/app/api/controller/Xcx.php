<?php
namespace app\api\controller;

use Think\Controller;
use think\Db;

class Xcx
{
    public function index()
    {
    	// 1.banner图 2.公司介绍 3.产品展示 4.联系我们
		$fan	 = '\\';
		$conpany = Db::name('articlecate')->field('id, name, content')->select();
		$conpanys = [];
		foreach ($conpany as $k => $v) {
			$conpanys[$v['id']] = $v;
			$conpanys[$v['id']]['content'] = strip_tags($conpanys[$v['id']]['content']);
		}
		$pro = Db::name('article')->field('title, pic, cateid')->order('time DESC')->select();
		/* 图片反斜杠处理*/
		foreach ($pro as $k => $v) {
			if ($conpanys[$v['cateid']]) {
				$v['pic'] = str_replace($fan, '/', $v['pic']);
				$conpanys[$v['cateid']]['pics'][] = $v;
			}
		}
		echo json_encode(['status' => 200, 'message' => '查询成功', 'pics' => $conpanys]);
    }

	public function conpanyDetail()
	{
		$contact = Db::name('webconfig')->field('web_name, web_title, web_phone, web_mobile, web_addresss, web_info')->find();
		echo json_encode(['status' => 200, 'message' => '查询成功', 'contact' => $contact]);
	}
}