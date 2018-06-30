<?php
namespace Yxadmin\Controller;
use Think\Controller;
class LinksController extends CommonController {
    public function links(){       
        $act = I('GET.act','add');
        $links_id = I('GET.links_id');
        $links_info = array();
        if($links_id){
            $links_info = D('links')->where('links_id='.$links_id)->find();
        }
        if($act == 'add')          
           $links_info['pid'] = $_GET['pid'];                
        $position = D('links_position')->where('1=1')->select();
        $this->assign('info',$links_info);
        $this->assign('act',$act);
        $this->assign('position',$position);
        $this->display();
    }
    public function listdel(){
        $id=I('id');
         $links=D('links');
        if($links->delete($id)){
            echo json_encode(array ("status"=>'1',"msg"=>'操作成功'));     
        }else{
            echo json_encode(array ("status"=>'-1',"msg"=>'操作失败'));     
        }
    }
    public function listsort(){
        $links=D('links');
        $id=I('id');
        $new_sort=I('new_sort');
        $res=$links->where("links_id=".$id)->setField('orderby',$new_sort);
        if($res){
			echo json_encode(array ("status"=>'1',"msg"=>'操作成功'));     
		}else{
			echo json_encode(array ("status"=>'-1',"msg"=>'操作失败'));     
		}
    }
    public function linksList(){
        delFile(RUNTIME_PATH.'Html'); // 先清除缓存, 否则不好预览
        $links =  M('links'); 
        $where = "1=1";
        if(I('pid')){
        	$where = "pid=".I('pid');
        	$this->assign('pid',I('pid'));
        }
        $keywords = I('keywords',false);
        if($keywords){
        	$where = "links_name like '%$keywords%'";
        }
        $count = $links->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $res = $links->where($where)->order('pid desc')->limit($Page->firstRow.','.$Page->listRows)->select();
                                     
        $links_position_list = M('LinksPosition')->getField("position_id,position_name,is_open");  
        $this->assign('links_position_list',$links_position_list);//广告位 
        $show = $Page->show();// 分页显示输出
        $this->assign('list',$res);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    public function linksHandle(){
        $datapost = I('post.');
        $data ["pid"] =$datapost["pid"];
        $data["links_name"] = $datapost["links_name"];
        $data ["links_link"] =$datapost["links_link"];
        $data ["links_desc"] =$datapost["links_desc"];
        
		
    	if($_FILES['pic']['tmp_name']!=""){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 31457280 ;// 设置附件上传大小
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg', 'zip');
            $upload->rootPath = './'; // 设置附件上传根目录
            $upload->savePath = './Public/Uploads/';
            $info=$upload->uploadOne($_FILES['pic']);
            if(!$info) {
                echo $this->error($upload->getError());
            }else{
                $data['links_pic']=$info['savepath'].$info['savename'];
            }
        }
    
      
    	if($datapost['act'] == 'add'){
    		$r = D('links')->add($data);
    	}
    	if($datapost['act'] == 'edit'){
    		$r = D('links')->where('links_id='.$datapost['links_id'])->save($data);
    	}
    	if($datapost['act'] == 'del'){
    		$r = D('links')->where('links_id='.$datapost['del_id'])->delete();
    		if($r) exit(json_encode(1));
    	}
    	$referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U('Admin/Ad/adList');
        delFile(RUNTIME_PATH.'Html'); // 先清除缓存, 否则不好预览       
    	if($r){
    		$this->success("操作成功",$referurl);
    	}else{
    		$this->error("操作失败",$referurl);
    	}
    }
	
	    
	
	
	
    public function position(){
	C('TOKEN_ON',false);
        $act = I('GET.act','add');
        $position_id = I('GET.position_id');
        $info = array();
        if($position_id){
            $info = D('links_position')->where('position_id='.$position_id)->find();
            $this->assign('info',$info);
        }
        $this->assign('act',$act);
        $this->display();
    }
    public function positionList(){
        $Position =  M('links_position');
        $count = $Position->where('1=1')->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $list = $Position->order('position_id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        
        $this->assign('list',$list);// 赋值数据集                
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    public function positionHandle(){
        $datapost = I('post.');
		$data["position_name"] = $datapost["position_name"];
		$data ["position_desc"] =$datapost["position_desc"];
		$data ["is_open"] =$datapost["is_open"];

		if($datapost['act'] == 'add'){
            $r = M('links_position')->add($data);
        }
        if($datapost['act'] == 'edit'){
        	$r = M('links_position')->where('position_id='.$datapost['position_id'])->save($data);
        }
        
		
        if($datapost['act'] == 'del'){
        	if(M('links')->where('pid='.$datapost['position_id'])->count()>0){
        		$this->error("此广告位下还有广告，请先清除",U('positionList'));
        	}else{
        		$r = M('ad_position')->where('position_id='.$datapost['position_id'])->delete();
        		if($r) exit(json_encode(1));
        	}
        }
        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U('positionList');
        if($r){
        	$this->success("操作成功",$referurl);
        }else{
        	$this->error("操作失败",$referurl);
        }
    }
    
    public function changeAdField(){
    	$data[$_REQUEST['field']] = I('GET.value');
    	$data['ad_id'] = I('GET.ad_id');
    	M('ad')->save($data); // 根据条件保存修改的数据
    }


}