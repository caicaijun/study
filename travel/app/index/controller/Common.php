<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use app\index\model\Articlecate;

class Common extends Controller
{
	private static $seoList = false;

	public function _initialize()
	{
		$Articlecate = new Articlecate;
		$nav  		 = $Articlecate->queryAll();
		$navParentid = [];
		foreach ($nav as $key => $value) {
			$navParentid[] = $value['parentid'];
		}
		$this->assign('pid', $navParentid);
    	$this->assign('nav', $nav);
    	
    	/*SEO自动匹配开始*/
    	if (self::$seoList == false) {
    		$this->seo();
    	}
    	$request   		= Request::instance();;
    	$controllerName = strtolower($request->controller());
    	$actionName 	= strtolower($request->action());
    	$seoKey         = $controllerName . '_' . $actionName;
    	$seoKeywords	= '';
    	$seoDes 		= '';
    	foreach (self::$seoList as $value) {
    		if ($value['seo_key'] == $seoKey) {
    			$seoKeywords = $value['seo_keywords'];
    			$seoDes      = $value['seo_desc'];
    		}
    	}
        $this->assign('request', $request);
    	$this->assign('seoKeywords', $seoKeywords);
    	$this->assign('seoDes', $seoDes);
    	/*SEO自动匹配结束*/

    	
	}

	/*获取SEO数据*/
	public function seo() {
		self::$seoList = Db::name('seo')->select();
	}
}