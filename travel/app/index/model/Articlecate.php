<?php
namespace app\index\model;

use think\Model;

class Articlecate extends Model
{
	   //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }

    public function queryAll()
    {
    	$list  = Articlecate::all(function($query){
    		$query->field('id, parentid, name, template')->where('navshow', 1)->order('sort', 'DESC');
    	});
    	$lists = [];
    	foreach ($list as $key => $value) {
    		$lists[] = $value->data;
    	}
        $navTree = $this->getTree($lists);
    	return $navTree;
    }

    function getTree($array, $parentid =0, $level = 0)
    {
        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
        static $list = [];
        foreach ($array as $key => $value) {
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['parentid'] == $parentid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $value['level'] = $level;
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $this->getTree($array, $value['id'], $level+1);
            }
        }
        return $list;
    }
}