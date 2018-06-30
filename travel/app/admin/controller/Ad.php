<?php
namespace app\admin\controller;

use think\Db;
use think\Request;
use think\Paginator;

class Ad extends Common
{
    public function positionList()
    {
        $list = Db::name('ad_position')->order('position_id DESC')->paginate(10);
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function positionDel()
    {
        $position_id = $this->request->param();
        if ($position_id['position_id']) {
            $data = Db::name('ad')->where('pid = ' . $position_id['position_id'])->find();
            if ($data) {
                $this->error("此广告位下有广告，请先清除广告列表", url('ad/adlist'));
            }
            if (Db::name('ad_position')->where('position_id = ' . $position_id['position_id'])->delete()) {
                $this->success("删除成功", url('ad/positionlist'));
            } else {
                $this->error("删除失败，请稍后重试！", url('ad/positionlist'));
            }
        } else {
            $this->error("删除失败，请稍后重试！", url('ad/positionlist'));
        }
    }

    public function positionAdd()
    {
        if ($_POST) {
            $position = $this->request->param();
            if (Db::name('ad_position')->insert($position)) {
                $this->success("添加成功", url('ad/positionlist'));
            } else {
                $this->error("添加失败，请稍后重试！", url('ad/positionlist'));
            }
        } else {
            return $this->fetch();
        }
    }

    public function positionEdit()
    {
        $position = $this->request->param();
        if ($_POST) {
            if (Db::name('ad_position')->where('position_id = ' . $position['position_id'])->update($position)) {
                $this->success("修改成功", url('ad/positionlist'));
            } else {
                $this->error("修改成功，请稍后重试！", url('ad/positionlist'));
            }
        } else {
            if ($position['position_id']) {
                $data = Db::name('ad_position')->find($position['position_id']);
                $this->assign('info', $data);
            } else {
                $this->error("出错了，请稍后重试！", url('ad/positionlist'));
            }
            return $this->fetch();
        }
    }

    public function adList()
    {
        $list   = Db::name('ad')->order('ad_id DESC')->paginate(10);
        $adCate = Db::name('ad_position')->field('position_id, position_name')->select();
        $cate   = [];
        foreach ($adCate as $key => $value) {
            $cate[$value['position_id']][] = $value;
        }
        $this->assign('cate', $cate);
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function adadd()
    {
        if ($_POST) {
            $ad = $this->request->param();
             /* 图片上传 */
            $file = request()->file('ad_pic');
            if ($file) {
                $img = $this->upload($file);
                if ($img['status'] == 200) {
                    $ad['ad_pic'] = $img['url'];
                } else {
                    $this->error($img['error_message']);
                }
            }
            if (Db::name('ad')->insert($ad)) {
                $this->success("添加成功", url('ad/adlist'));
            } else {
                $this->error("添加失败，请稍后重试！", url('ad/adlist'));
            }
        } else {
            $position = Db::name('ad_position')->field('position_id, position_name')->select();
            $this->assign('position', $position);
            return $this->fetch();
        }
    }

    public function adEdit()
    {
        $ad = $this->request->param();
        if ($_POST) {
             /* 图片上传 */
            $file = request()->file('ad_pic');
            if ($file) {
                $img = $this->upload($file);
                if ($img['status'] == 200) {
                    $ad['ad_pic'] = $img['url'];
                } else {
                    $this->error($img['error_message']);
                }
            } else {
                $ad['ad_pic'] = $ad['pic'];
            }
            unset($ad['pic']);
            if (Db::name('ad')->where('ad_id', $ad['ad_id'])->update($ad)) {
                $this->success("修改成功", url('ad/adlist'));
            } else {
                $this->error("修改失败，请稍后重试！", url('ad/adlist'));
            }
        } else {
            $data = Db::name('ad')->find($ad['ad_id']);
            $position = Db::name('ad_position')->field('position_id, position_name')->select();
            $this->assign('info', $data);
            $this->assign('position', $position);
            return $this->fetch();
        }
    }

    public function adDel()
    {
        $ad = $this->request->param();
        if(Db::name('ad')->delete($ad['ad_id'])){
            echo json_encode(array ("status"=>'1',"msg"=>'删除成功'));     
        }else{
            echo json_encode(array ("status"=>'-1',"msg"=>'删除失败'));     
        }
    }

    public function adSort()
    {
        $new_sort     = $this->request->param();
		$data['sort'] = $new_sort['new_sort'];
        $res 	      = Db::name('ad')->where('ad_id = ' . $new_sort['id'])->update($data);
        if($res){
			echo json_encode(array ("status"=>'1'));   
		}else{
			echo json_encode(array ("status"=>'-1'));   
		}
    }


    
    public function changeAdField(){
    	$data[$_REQUEST['field']] = I('GET.value');
    	$data['ad_id'] = I('GET.ad_id');
    	M('ad')->save($data); // 根据条件保存修改的数据
    }


}