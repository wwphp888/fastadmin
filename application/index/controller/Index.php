<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\library\Pay;

class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {
        $data = [
            'orderno' => time(),
            'amount' => 1,
            'body' => 'testsss',
        ];
        $pay = Pay::send(1, 'scan', $data);
        dump($pay);
    }

    public function news()
    {
        $newslist = [];
        return jsonp(['newslist' => $newslist, 'new' => count($newslist), 'url' => 'https://www.fastadmin.net?ref=news']);
    }

}
