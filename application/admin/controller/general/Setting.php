<?php

namespace app\admin\controller\general;

use app\common\controller\Backend;
use app\common\library\Email;
use think\Validate;

/**
 * 系统配置
 * @remark 系统设置
 */
class Setting extends Backend
{
    protected $model = null;
    protected $noNeedRight = ['check', 'rulelist'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Setting');
    }

    /**
     * 查看
     */
    public function index()
    {
        error_reporting(E_ALL ^ E_NOTICE);
        $data = config('setting');
        $this->assign($data);
        return $this->fetch();
    }

    /**
     * @desc 保存
     * @param null $ids
     */
    public function edit($ids = null)
    {
        $name = $this->request->get('name');
        $value = $this->request->post('value/a');
        $is = $this->model->where(['name' => $name])->find();
        $value = json_encode($value);
        if ($is) {
            $this->model->where(['name' => $name])->update(['value' => $value]);
        } else {
            $this->model->insert(['name' => $name, 'value' => $value]);
        }
        $this->refreshFile();
        $this->success();
    }

    /**
     * 刷新配置文件
     */
    protected function refreshFile()
    {
        $data = $this->model->column('value', 'name');
        foreach ($data as $k => &$v) {
            $v = json_decode($v, true);
        }
        file_put_contents(
            APP_PATH . 'extra' . DS . 'setting.php',
            '<?php' . "\n\nreturn " . var_export($data, true) . ";"
        );
    }

    /**
     * 发送测试邮件
     * @internal
     */
    public function emailtest()
    {
        $row = $this->request->post('row/a');
        $receiver = $this->request->post("receiver");
        if ($receiver) {
            if (!Validate::is($receiver, "email")) {
                $this->error(__('Please input correct email'));
            }
            \think\Config::set('site', array_merge(\think\Config::get('site'), $row));
            $email = new Email;
            $result = $email
                ->to($receiver)
                ->subject(__("This is a test mail"))
                ->message('<div style="min-height:550px; padding: 100px 55px 200px;">' . __('This is a test mail content') . '</div>')
                ->send();
            if ($result) {
                $this->success();
            } else {
                $this->error($email->getError());
            }
        } else {
            return $this->error(__('Invalid parameters'));
        }
    }
}
