<?php
namespace Yxadmin\Controller;

use Think\Controller;

class AdminController extends CommonController 
{
    public function adminlist(){
        $admin=D('admin');
        $where=1;
        if($kw=I('kw')){
            $where.=' AND username LIKE "%'.$kw.'%"';
        }

        $count = $admin->where($where)->count();
        $Page = new \Think\Page($count,12);
        $show = $Page->show();
        //$admins = $admin->where($where)->limit($Page->firstRow.','.$Page->listRows)->select(); 
        $admins = $admin->field('a.*,b.rolename')->alias('a')->join('LEFT JOIN jiwei_role b on b.id=a.roleid')->limit($Page->firstRow.','.$Page->listRows)->select(); 
        $this->assign('admin',$admins);
        $this->assign('page',$show);
        $this->display();
    }

    public function add(){
    	 $admin=D("admin");
      	 if(IS_POST){       		       
	       	if($admin->create($data)){               
                $admin->password=md5($admin->password);//加密后重新赋值,如果写在验证前，空加密后就不为空了
	       		if($admin->add()){
	       			$this->success('新增管理员成功',U('adminlist'));
	       		}else{
	       			$this->error('新增管理员失败');
	       		}
	       	}else{
	       		$this->error($admin->getError());
	       	}
	       	return;
         }       
		 
		 
		$role=D('role');
        $role=$role->select(); 
        $this->assign('role',$role);		 
        $this->display();
    }

    public function edit(){
        $admin=D("admin");       

        if(IS_POST){
                    
	       	if($admin->create()){
                $admin->password=md5($admin->password);//

	       		if($admin->save()){
	       			$this->success('修改管理员成功栏目',U('adminlist'));
	       		}else{
	       			$this->error('修改管理员失败');
	       		}
	       	}else{
	       		$this->error($admin->getError());
	       	}
	       	return;
         }
        $id=I('id');
        $admins=$admin->find($id);
        $this->assign('admin',$admins);

        $role=D('role');
        $role=$role->select(); 
        $this->assign('role',$role);

        $this->display();
        
    }


    


    public function del(){
    	 $admin=D("admin"); 
    	 $id=I('id');
    	 if($admin->delete($id)){
    	 	$this->success('删除成功',U('adminlist'));
    	 }else{
    	 	$this->error('删除失败');
    	 }	
    }


        public function bdel(){

 		$admin=D('admin');
        $ids=I('ids');
        $ids=implode(',', $ids);

    	if($ids){
            if($admin->delete($ids)){
                $this->success('批量删除链接成功！',U('adminlist'));
            }else{
                $this->error('批量删除链接失败！');
            }
        }else{
            $this->error('未选中任何内容！');
        }
    }


    public function logout(){
        session(null);
        $this->success('退出成功',U('Login/index'));
    }



}