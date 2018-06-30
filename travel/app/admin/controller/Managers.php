<?php
namespace app\admin\controller;

use think\Db;
use think\Request;
use think\Paginator;

class Managers extends Common
{
    public function index()
    { 
       return $this->fetch();
    }

	public function adminlist()
	{
        $adminList = Db::name('admin')->paginate(10);
        $role      = Db::name('role')->select();
        $roles     = [];
        foreach ($role as $value) {
        	$roles[$value['id']][] = $value;
        }
        $this->assign('adminlist',$adminList);
        $this->assign('roles', $roles);
        return $this->fetch();
    }

	public function adminAdd()
	{
		if ($_POST) {
			$request  = $this->request->param();
			$request['password'] = Md5($request['password']);
			if (Db::name('admin')->insert($request)) {
				$this->success('添加成功!', url('Managers/adminlist'));
			} else {
				$this->error('添加失败，请刷新重试!');
			}
		} else {
			$role  = Db::name('role')->select();
			$this->assign('role', $role);
			return $this->fetch();
		}
       
	}

	public function adminEdit()
	{
		$request  = $this->request->param();
		if ($_POST) {
			if (empty($request['password'])) {
				unset($request['password']);
			} else {
				$request['password'] = Md5($request['password']);
			}
			if (Db::name('admin')->update($request)) {
				$this->success('修改成功!', url('Managers/adminlist'));
			}
		} else {
			$Managers = Db::name('admin')->where('id', $request['id'])->select();
			$role     = Db::name('role')->select();
			$this->assign('admin', $Managers[0]);
			$this->assign('role', $role);
			return $this->fetch();
		}
       
	}

	public function adminDel()
	{
		 $request  = $this->request->param();
         if(Db::name('admin')->delete($request['id'])){
		 	echo json_encode(array ("status"=>'1',"msg"=>'操作成功'));     
         }else{
            echo json_encode(array ("status"=>'-1',"msg"=>'操作失败'));     
         }
	}

	public function Privilegelst()
	{
		$privilege 	   = Db::name('privilege')->select();
        $privilegeTree = $this->son($privilege);
        $this->assign('privileges', $privilegeTree);
        return $this->fetch();    
    }

    public function privilegeAdd()
	{    
        if($_POST){
	       	$request  = $this->request->param();
	       	if (Db::name('privilege')->insert($request)) {
	       		$this->success('操作成功', url('Managers/privilegelst'));
	       	} else {
	       		$this->error('操作失败');
	       	}
         } else {
         	$privilege     = Db::name('privilege')->select();
        	$privilegeTree = $this->son($privilege);
        	$this->assign('privileges',$privilegeTree);
        	return $this->fetch();
         }
	}

	public function privilegeEdit()
	{    
		$request  = $this->request->param();
        if($_POST){
	       	if (Db::name('privilege')->where('id', $request['id'])->update($request)) {
	       		$this->success('操作成功', url('Managers/privilegelst'));
	       	} else {
	       		$this->error('操作失败');
	       	}
         } else {
         	$privilege     = Db::name('privilege')->select();
        	$privilegeTree = $this->son($privilege);
        	$pri           = Db::name('privilege')->find($request['id']);
        	$this->assign('privileges', $privilegeTree);
        	$this->assign('pri', $pri);
        	return $this->fetch();
         }
	}

	public function privilegeDel()
	{    
		$request  = $this->request->param();
        if (Db::name('privilege')->delete($request['id'])) {
        	echo json_encode(array ("status"=>'1',"msg"=>'操作成功')); 
        } else {
        	echo json_encode(array ("status"=>'-1',"msg"=>'操作失败')); 
        }
	}

	public function rolelst()
	{
        $role = Db::query('select a.*, GROUP_CONCAT(b.pri_name) pri_name from  tp_role a LEFT JOIN tp_privilege b ON find_in_set(b.id, a.pri_id_list) GROUP BY a.id');
        $this->assign('role', $role);
        return $this->fetch();
    }
	
	public function rolestAdd()
	{
		if ($_POST) {
			$request  				= $this->request->param();
			$request['pri_id_list'] = implode(',', $request['pri_id_list']);
			if (empty($request['pri_id_list'])) {
				$this->error('请选择权限！');
			}
			if (Db::name('role')->insert($request)) {
				$this->success('操作成功', url('managers/rolelst'));
			} else {
				$this->error('操作失败');
			}
		} else {
			$privilege 	   = Db::name('privilege')->select();
        	$privilegeTree = $this->son($privilege);
        	$this->assign('privileges', $privilegeTree);
        	return $this->fetch();
		}
	}
	
	public function rolestEdit()
	{
		$request  = $this->request->param();
		if ($_POST) {
			$request['pri_id_list'] = implode(',', $request['pri_id_list']);
			if (Db::name('role')->where('id', $request['id'])->update($request)) {
				$this->success('操作成功', url('managers/rolelst'));
			} else {
				$this->error('操作失败');
			}
		} else {
			$privilege 	   = Db::name('privilege')->select();
        	$privilegeTree = $this->son($privilege);
        	$role 		   = Db::name('role')->find($request['id']);
        	$this->assign('role', $role);
        	$this->assign('privileges', $privilegeTree);
        	return $this->fetch();
		}
	}

	public function rolestDel()
	{
		 $request  = $this->request->param();
         if(Db::name('role')->delete($request['id'])){
		 	echo json_encode(array ("status"=>'1',"msg"=>'操作成功'));     
         }else{
            echo json_encode(array ("status"=>'-1',"msg"=>'操作失败'));     
         }  
	}
	
}