<?php

namespace app\admin\controller\goods;

use app\common\controller\Backend;
use fast\Tree;

/**
 * 商品分类
 */
class Cate extends Backend
{
    protected $model = null;
    protected $catelist = [];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('GoodsCate');
        // 必须将结果集转换为数组
        $cateList = collection($this->model->order('weigh', 'desc')->order('id', 'asc')->select())->toArray();
        Tree::instance()->init($cateList);
        $this->catelist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');

        $parentList = [];
        foreach ($this->catelist as $k => $v) {
            $parentList[$v['id']] = $v;
        }
        $this->view->assign("parentList", $parentList);
    }

    /**
     * 查看
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $list = $this->catelist;
            $total = count($this->catelist);

            return json(['total' => $total, 'rows' => $list]);
        }
        return $this->view->fetch();
    }

//    /**
//     * 添加
//     */
//    public function add()
//    {
//        if ($this->request->isPost()) {
//            $this->token();
//            $params = $this->request->post("row/a", [], 'strip_tags');
//            if ($params) {
//                if (!$params['ismenu'] && !$params['pid']) {
//                    $this->error(__('The non-menu rule must have parent'));
//                }
//                $result = $this->model->validate()->save($params);
//                if ($result === false) {
//                    $this->error($this->model->getError());
//                }
//                Cache::rm('__menu__');
//                $this->success();
//            }
//            $this->error();
//        }
//        return $this->view->fetch();
////    }
//
//    /**
//     * 编辑
//     */
//    public function edit($ids = null)
//    {
//        $row = $this->model->get(['id' => $ids]);
//        if (!$row) {
//            $this->error(__('No Results were found'));
//        }
//        if ($this->request->isPost()) {
//            $this->token();
//            $params = $this->request->post("row/a", [], 'strip_tags');
//            if ($params) {
//                if (!$params['ismenu'] && !$params['pid']) {
//                    $this->error(__('The non-menu rule must have parent'));
//                }
//                if ($params['pid'] != $row['pid']) {
//                    $childrenIds = Tree::instance()->init(collection(AuthRule::select())->toArray())->getChildrenIds($row['id']);
//                    if (in_array($params['pid'], $childrenIds)) {
//                        $this->error(__('Can not change the parent to child'));
//                    }
//                }
//                //这里需要针对name做唯一验证
//                $ruleValidate = \think\Loader::validate('AuthRule');
//                $ruleValidate->rule([
//                    'name' => 'require|format|unique:AuthRule,name,' . $row->id,
//                ]);
//                $result = $row->validate()->save($params);
//                if ($result === false) {
//                    $this->error($row->getError());
//                }
//                Cache::rm('__menu__');
//                $this->success();
//            }
//            $this->error();
//        }
//        $this->view->assign("row", $row);
//        return $this->view->fetch();
//    }

    /**
     * @desc 删除
     * @param string $ids
     */
    public function del($ids = "")
    {
        if ($ids) {
            $delIds = [];
            foreach (explode(',', $ids) as $k => $v) {
                $delIds = array_merge($delIds, Tree::instance()->getChildrenIds($v, true));
            }
            $delIds = array_unique($delIds);
            $count = $this->model->where('id', 'in', $delIds)->delete();
            if ($count) {
                $this->success();
            }
        }
        $this->error();
    }
}