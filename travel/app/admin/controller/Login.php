<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Session;
use think\Response;

class Login extends Controller
{
    public function index(Request $request)
    {
		if($_POST){
			$data = $request->param();
			if(!captcha_check($data['captcha'])){
				echo json_encode(array('status' => '-1', 'message' => '验证码错误！'));
				exit;
			} else {
			$user = Db::query('select * from tp_admin where username = ?', [$data['username']]);
				if (!empty($user)) {
					if (MD5($data['password']) != $user[0]['password']) {
						echo json_encode(array('status' => '-2', 'message' => '密码错误！'));
						exit;
					}
					$role = Db::name('role')->find($user[0]['roleid']);
				} else {
					echo json_encode(array('status' => '-3', 'message' => '没有该账号！'));
					exit;
				}
			/* 存入session */
			Session::set('user.name', $user[0]['username']);
			Session::set('user.role', $role);
			echo json_encode(array('status' => '4', 'message' => '登录成功！跳转中。。。'));
			}
		} else {
			return $this->fetch();
		}
    }

    public function loginOut()
    {
    	Session::delete('user');
    	$this->success('退出成功!', url('login/index'));
    }
}