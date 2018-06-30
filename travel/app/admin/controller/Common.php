<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Common extends Controller
{
    protected $request;
    protected static $action_qx;  // 用户权限控制器
    protected static $function_qx;  // 用户权限方法
    protected $user;
    public function __construct()
    {
        parent::__construct();
        $this->request = Request::instance();
        $this->assign('manager', Session::get('user'));
        if(!Session::get('user')){
            $this->error('请先登录系统！', url('Login/index'));
        } else {
            $this->user = Session::get('user');
        }
        /* 权限开始 */
        if (empty(self::$action_qx) && empty(self::$function_qx)) {
            if ($this->user['role']['pri_id_list'] != '*') {
                $cname = Db::name('privilege')->field('cname')->where('id', 'IN', $this->user['role']['pri_id_list'])->select();
                foreach ($cname as $value) {
                     self::$action_qx[] = $value['cname'];
                }
                $aname = Db::name('privilege')->field('aname')->where('id', 'IN', $this->user['role']['pri_id_list'])->select();
                foreach ($aname as $value) {
                     self::$function_qx[] = $value['aname'];
                }
            }
        }
        $moduleName     = $this->request->module();// 模块名
        $controllerName = $this->request->controller();// 控制器名
        $actionName     = $this->request->action();// 方法名
        //首页和退出是任何管理员可以访问的
        if($moduleName == 'admin' && $controllerName == 'Index'){
        	return true; 
        }
        if($moduleName == 'admin' && $controllerName == 'login' && $actionName == 'loginout'){
        	return true; 
        }
        if (!empty(self::$action_qx) && !empty(self::$function_qx)) {
            if (!in_array($controllerName, self::$action_qx) && !in_array($actionName, self::$function_qx)) {
                $this->error('没有权限访问该功能,请联系超级管理员');
            }
        }
        /* 权限结束 */
    }

    /* 
    *  文件上传
    */
    public function upload($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            $img = [];
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                //echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $img['status'] = 200;
                $img['url']    = $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                // $info->getFilename(); 
            }else{
                // 上传失败获取错误信息
                $img['status'] = 300;
                $img['error_message'] = $file->getError();
            }
            return $img;
    }

    /* 递归树 */
    public function tree($array, $id = 0)
    {
        $data = [];
        foreach ($array as $k => $v) {
            if ($v['parentid'] == $id) {
                $v['son'] = $this->tree($array, $v['id']);
                if (empty($v['son'])) {
                    unset($v['son']);
                }
                $data[] = $v;
            }
        }
        return $data;
    }

    /* 按父ID排列 */ //待改进，性能差
    public function son($array, $parentid = 0, $level = 0)
    {
        static $data = [];
        foreach ($array as $key => $value) {
            if ($value['parentid'] == $parentid) {
                $value['level'] = $level;
                $data[] = $value;
                $this->son($array, $value['id'], $value['level']+1);
            }
        }
        return $data;
    }

}