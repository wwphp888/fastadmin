<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\library\Pay;
use fast\Form;

class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {
        return Form::select('wee', [1,2,3,4], 1);
    }

    public function news()
    {
        $newslist = [];
        return jsonp(['newslist' => $newslist, 'new' => count($newslist), 'url' => 'https://www.fastadmin.net?ref=news']);
    }

}
