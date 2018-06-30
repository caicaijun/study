<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;

class Seo extends Common
{
	private $seoFields = array('seo_id', 'seo_key', 'seo_explain', 'seo_title', 'seo_keywords', 'seo_desc');

    public function seoList()
    {
        $seo = Db::name('Seo')->select();
        $this->assign('list', $seo);
        return $this->fetch(); // 输出模板
    }

    private function check()
    {
        $data = $this->checkFields($this->request->param()['data'], $this->seoFields);
        $data['seo_key'] = htmlspecialchars($data['seo_key']);
        if (empty($data['seo_key'])) {
            $this->error('标签不能为空');
        } $data['seo_explain'] = htmlspecialchars($data['seo_explain']);
        if (empty($data['seo_explain'])) {
            $this->error('说明不能为空');
        } $data['seo_title'] = htmlspecialchars($data['seo_title']);
        if (empty($data['seo_title'])) {
            $this->error('SEO标题不能为空');
        } $data['seo_keywords'] = htmlspecialchars($data['seo_keywords']);
        if (empty($data['seo_keywords'])) {
            $this->error('SEO关键字不能为空');
        } $data['seo_desc'] = htmlspecialchars($data['seo_desc']);
        if (empty($data['seo_desc'])) {
            $this->error('SEO描述不能为空');
        }
        return $data;
    }

    public function checkFields($data = array(), $fields = array())
    {
        foreach ($data as $k => $val ) {
            if (!in_array($k, $fields)) {
                unset($data[$k]);
            }
        }
        return $data;
    }

    public function seoCreate() 
    {
        if ($_POST) {
            $data = $this->check();
            if (Db::name('seo')->insert($data)) {
                 $this->success('操作成功', url('seo/seoList'));
            }else{
                 $this->error('操作失败！');
            }
        } else {
            return $this->fetch();
        }
    }

    public function edit($seo_id = 0)
    {
        if ($_POST) {
                $data = $this->check();
                if (Db::name('seo')->where('seo_id', $data['seo_id'])->update($data)) {
                    $this->success('操作成功', url('seo/seoList'));
                }else{
                    $this->error('操作失败,请重试');
                }
            } else {
                if ($seo_id = (int) $seo_id) {
                    $detail = Db::name('seo')->find($seo_id);
                    $this->assign('detail', $detail);
                    return $this->fetch();
                } else {
                    $this->error('出错了，请刷新重试！');
                }
                
            }
    }

    public function delete($seo_id = 0)
    {
        if (is_numeric($seo_id) && ($seo_id = (int) $seo_id)) {
            $obj = D('Seo');
            $obj->delete($seo_id);
            $obj->cleanCache();
            $this->success('删除成功！', U('Seo/seoList'));
        } else {
            $seo_id = I('seo_id');
            if (is_array($seo_id)) {
                $obj = D('Seo');
                foreach ($seo_id as $id) {
                    $obj->delete($id);
                }
                $obj->cleanCache();
                $this->success('删除成功！', U('seo/index'));
            }
            $this->error('请选择要删除的SEO设置');
        }
    }
}