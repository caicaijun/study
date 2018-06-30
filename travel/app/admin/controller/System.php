<?php
namespace app\admin\controller;

use think\Db;
use think\Request;
use think\Cache;

class System extends Common
{
	/*
	*	网站设置
	*	添加，更新
	*/
	public function index()
	{
	  	if ($_POST) {
	  		$data = $this->request->param();
	  		$file = request()->file('web_logo');
            if ($file) {
                $img = $this->upload($file);
                if ($img['status'] == 200) {
                    $data['web_logo'] = $img['url'];
                } else {
                    $this->error($img['error_message']);
                }
            } else {
                $data['web_logo'] = $data['logo'];
            }
            unset($data['logo']);
	  		if (Db::table('tp_webconfig')->update($data)) {
	  			$this->success("更新成功！");
	  		} else {
	  			$this->error('更新失败！');
	  		}
	  	} else {
	  		$data = Db::query('select * from tp_webconfig');
	  		$this->assign('data', $data[0]);
			return $this->fetch();
	  	}
	  }

	 public function template()
	 {
	 	$template = Db::name('template')->select();
	 	$this->assign('tem', $template);
	 	return $this->fetch();
	 }

	 public function templateAdd()
	 {
	 	if ($_POST) {
			$request = $this->request->param();
			if (!$request['template_name']) {
				$this->error('模板不能为空！');
			}
			if (!$request['template_name2']) {
				$this->error('模板名称不能为空！');
			}
			if (Db::name('template')->insert($request)) {
				$this->success('添加成功！', url('system/template'));
			}
		} else {
        	return $this->fetch();
		}
	 }

	 public function templateDel()
	 {
	 	$request = $this->request->param();
	 	if (empty($request)) {
            echo json_encode(array('status' => '0', 'msg' => '你要删除的模板不存在，请刷新重试!'));
            exit;
        }
        if (db('template')->delete($request)) {
            echo json_encode(array('status' => '1', 'msg' => '删除成功!'));
        } else {
            echo json_encode(array('status' => '2', 'msg' => '删除失败，请刷新重试!'));
        }
	 }
	
	/*一键清除*/
	public function cache()
	{
		Cache::clear();
		$this->success('清除成功', url('admin/index/index'));
	}

	 /*
	 	*清除缓存
	 */
	 /*public function cleanCache()
	 {              
		if(IS_POST)
		{    
			dump(I("clears"));    
			 in_array('cache',$_POST['clears']) && delFile('./yxcms/Application/Runtime/Cache');// 系统模板缓存                 
			 in_array('data',$_POST['clears'])  && delFile('./yxcms/Application/Runtime/Data');// 系统项目数据                 
			 in_array('logs',$_POST['clears'])  && delFile('./yxcms/Application/Runtime/Logs');// 系统logs日志                 
			 in_array('temp',$_POST['clears'])  && delFile('./yxcms/Application/Runtime/Temp');// 系统临时数据
			 
			// 删除静态文件                
			$html_arr = glob("./yxcms/Application/Runtime/Html/*.html");//使用glob函数查找文件，遍历文件目录
			foreach ($html_arr as $key => $val)
			{
				in_array('index',$_POST['clears']) && strstr($val,'Home_Index_index.html') && unlink($val); // 首页                    
				in_array('articleList',$_POST['clears']) && strstr($val,'Index_Article_index') && unlink($val);  // 列表页
				in_array('info',$_POST['clears']) && strstr($val,'Index_Article_info') && unlink($val);  // 详情
			}                               
			$this->success("操作完成!!!");
			exit;
		}
		$this->display();                     
	}*/

	/*一键清除图片*/
	/*public function cacheimg()
	{
	    $rtim=del_dir('./Public/Uploads/thumes');
	    if($rtim){
	        $this->success('清除成功',false);
	    }else{
	        $this->error('清除失败');
	    }
	}*/
	
	//删除图片
// 	public function delimg(){
// 	    //         $id=I('id');
	
// 	    //         $path="/Public/Uploads/thumes/".$id;
// 	    //         $rtim=del_dir($path);
	
// 	    //         if($rtim){
// 	    //             $this->success('清除成功',false);
// 	    //         }else{
// 	    //             $this->error('清除失败');
// 	    //         }
	
// 	    $rtim=del_dir('/Public/Uploads/thumes');
// 	    echo $rtim;
// 	    exit();
// 	    if($rtim){
// 	        $this->success('清除成功',false);
// 	    }else{
// 	        $this->error('清除失败');
// 	    }
// 	}
	
	/*ajax更改各种状态*/
	/*public function changeTableVal(){  
          $table = I('table'); // 表名
          $id_name = I('id_name'); // 表主键id名
          $id_value = I('id_value'); // 表主键id值
          $field  = I('field'); // 修改哪个字段
          $value  = I('value'); // 修改字段值                        
          M($table)->where("$id_name = $id_value")->save(array($field=>$value)); // 根据条件保存修改的数据 
    }*/
}