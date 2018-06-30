<?php
namespace app\admin\controller;

use think\Db;
use think\Request;
use think\Paginator;
use think\Cache;

class Article extends Common
{
	public function cateList()
    {
    	$results = Db::query('select id,parentid,name,level,template,sort,navshow from tp_articlecate');
        $parentid = [];
        foreach ($results as $value) {
            $parentid[] = $value['parentid'];
        }
       $results = $this->son($results);
        foreach ($results as $key => $value) {
         if (in_array($value['id'], $parentid)) {
                $results[$key]['son'] = 1;
           }
        }
        $this->assign('cates', $results);
        return $this->fetch();
    }

    /* 
    *   文章类目 增 改
    */
	public function cate()
    {
        $parameter = $this->request->param();
        if(!$_POST && !empty($parameter['type']) && $parameter['type'] == 'add') {    //新添加页面
            $this->assign('act', 'add');
        } elseif (!empty($parameter['act']) && $parameter['act'] == 'add') { // 添加提交
            $add = [];
            if ($parameter['parentid'] != 0) {
                $parent = explode(',', $parameter['parentid']);
                $add['parentid'] = $parent[0];
                $add['level'] = $parent[1] + 1;
            } else {
                $add['parentid'] = $parameter['parentid'];
                $add['level']    = 1;
            }
            $add['name']      = $parameter['name'];
            $add['template']  = $parameter['template'];
            $add['des']       = $parameter['des'];
            $add['sort']      = $parameter['sort'];
            $add['content']   = $parameter['content'];
            /* 图片上传 */
            $file = request()->file('image');
            if ($file) {
                $img = $this->upload($file);
                if ($img['status'] == 200) {
                    $add['pic'] = $img['url'];
                } else {
                    $this->error($img['error_message']);
                }
            }
            if (db('Articlecate')->insert($add)) {
                $this->success('添加成功!', url('Article/cateList'));
            } else {
                $this->error('添加失败！');
            }
        } elseif (!$_POST && !empty($parameter['cate_id'])) {  //  修改初始信息
            $cate_info = db::query('select * from tp_articlecate where id = ' . $parameter['cate_id']);
            $this->assign('info', $cate_info[0]);
            $this->assign('act', 'save');
        } elseif ($parameter['act'] == 'save') {    // 修改提交
            $save = [];
            $save['name']      = $parameter['name'];
            $save['parentid']  = $parameter['parentid'];
            $save['template']  = $parameter['template'];
            $save['des']       = $parameter['des'];
            $save['sort']      = $parameter['sort'];
            $save['content']   = $parameter['content'];
            /* 图片上传 */
            $file = request()->file('image');
            if ($file) {
                $img = $this->upload($file);
                if ($img['status'] == 200) {
                    $save['pic'] = $img['url'];
                } else {
                    $this->error($img['error_message']);
                }
            }
            if (db('Articlecate')->where('id', $parameter['cate_id'])->update($save)) {
                $this->success('修改成功!', url('Article/cateList'));
            }
        }
		$category = db::query('select * from tp_articlecate');
        $cate     = $this->cateTree($category);
        $template = db::query('select * from tp_template');
		$this->assign('category', $cate);
        $this->assign('template', $template);
        return $this->fetch();
    }

    public function cateDel()
    {
        $parameter = $this->request->param();
        if (empty($parameter)) {
            echo json_encode(array('status' => '0', 'msg' => '你要删除的类目不存在，请刷新重试!'));
            exit;
        }
        if (db('Articlecate')->delete($parameter['cate_id'])) {
            echo json_encode(array('status' => '1', 'msg' => '删除成功!'));
        } else {
            echo json_encode(array('status' => '2', 'msg' => '删除失败，请刷新重试!'));
        }
    }

    public function cateTree(Array $data)
    {
        $new = [];
        foreach ($data as $v) {
            if ($v['parentid'] == 0 && $v['level'] == 1) {
                $new[$v['id']] = $v;
            } elseif ($v['level'] == 2) {
                $new[$v['parentid']]['son'][] = $v;
            }
        }
        return $new;
    }
	
	public function navigation()
	{
		$navshow = $this->request->param();
		$data['navshow'] = $navshow['value'];
		Db::name('articlecate')->where('id', $navshow['id_value'])->update($data);
	}
	
	public function cateSort()
	{
		$newSort = $this->request->param();
		$data['sort'] = $newSort['new_sort'];
		if (Db::name('articlecate')->where('id', $newSort['id'])->update($data)) {
			echo json_encode(array('status' => 1));
		} else {
			echo json_encode(array('status' => 2));
		}
	}
	
    public function delimg(){
        $id=I('id');
        
        $path="./Public/Uploads/thumes/".$id;
        $rtim=del_dir($path);
        if($rtim){
            $this->success('清除成功',false);
        }else{
            $this->error('清除失败');
        }
        

    }

    public function articleList()
    {
        if ($_POST) {
            $parameter = $this->request->param();
            if ($key = $parameter['keyword']) { 
                 $articleList = Db::name('article')->where("title like '%$key%'")->paginate(10);
            } elseif ($cat = $parameter['cateid']) {
                 $articleList = Db::name('article')->where('cateid = ' . $cat)->paginate(10);
            }
        } else {
             $articleList = Db::name('article')->order('time DESC')->paginate(10);
        }
        $articleCate = Db::name('articlecate')->select();
        $category = [];
        foreach ($articleCate as $value) {
              $category[$value['id']] = $value['name'];
        }
        $this->assign('category', $category);
        $this->assign('catelist', $this->cateTree($articleCate));
        $this->assign('list', $articleList);
        return $this->fetch();
    }
    
	public function articleAdd()
    {
        if ($_POST) {
            $parameter = $this->request->param();
            $add = [];
            $add['title']     = $parameter['title'];
            $add['time']      = $parameter['time'];
            $add['cateid']    = $parameter['cateid'];
            $add['des']       = $parameter['des'];
            $add['content']   = $parameter['content'];
            $file = request()->file('pic');
            if ($file) {
                $img = $this->upload($file);
                if ($img['status'] == 200) {
                    $add['pic'] = $img['url'];
                } else {
                    $this->error($img['error_message']);
                }
            }
            if (db('Article')->insert($add)) {
                $this->success('添加成功!', url('Article/articleList'));
            } else {
                $this->error('添加失败！');
            }
        } else {
            $articleCate = Db::name('articlecate')->select();
            $this->assign('catelist', $this->cateTree($articleCate));
            return $this->fetch();
        }
        
    }

    public function articleEdit(){
        $parameter = $this->request->param();
		if ($_POST) {
            $save = [];
            $save['title']     = $parameter['title'];
            $save['time']      = strtotime($parameter['time']);
            $save['cateid']    = $parameter['cateid'];
            $save['des']       = $parameter['des'];
            $save['content']   = $parameter['content'];
            /* 图片上传 */
            $file = request()->file('pic');
            if ($file) {
                $img = $this->upload($file);                                   
                if ($img['status'] == 200) {
                    $save['pic'] = $img['url'];
                } else {
                    $this->error($img['error_message']);
                }
            } else {
                $save['pic'] = $parameter['pics'];
            }
            if (db('Article')->where('id', $parameter['id'])->update($save)) {
                $this->success('修改成功!', url('Article/articleList'));
            } else {
                $this->error('修改失败！');
            }
        } else {
            $articleInfo = Db::name('article')->where('id', $parameter['id'])->find();
            $articleCate = Db::name('articlecate')->select();
            $this->assign('info', $articleInfo);
            $this->assign('catelist', $this->cateTree($articleCate));
            return $this->fetch();
        }
    }

    public function articleDel(){
        $parameter = $this->request->param();
        if (empty($parameter)) {
            echo json_encode(array('status' => '0', 'msg' => '出错了，请刷新重试!'));
            exit;
        }
        if (db('Article')->delete($parameter['id'])) {
            echo json_encode(array('status' => '1', 'msg' => '删除成功!'));
        } else {
            echo json_encode(array('status' => '2', 'msg' => '删除失败，请刷新重试!'));
        } 
    }

    public function listsort(){
        $article=D('article');
        $id=I('id');
        $new_sort=I('new_sort');
        $res=$article->where("id=".$id)->setField('sort',$new_sort);
        if($res){
			echo json_encode(array ("status"=>'1',"msg"=>'操作成功'));     
		}else{
			echo json_encode(array ("status"=>'-1',"msg"=>'操作失败'));     
		}
    }

}