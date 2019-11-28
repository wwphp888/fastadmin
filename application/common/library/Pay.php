<?php

namespace app\common\library;

use Yansongda\Pay\Pay as PayApi;

/**
 * 短信验证码类
 */
class Pay
{
    private static $payWay = [
        1 => 'wxpay',
        2 => 'alipay',
    ];

    /**
     * @desc 发起支付
     * @param $type
     * @param $mode
     * @param $data
     * @return mixed
     */
    public static function send($type, $mode, $data)
    {
        return call_user_func_array([self::class, self::$payWay[$type]], [$mode, $data]);
    }

    /**
     * @desc 微信支付
     * @param $mode
     * @param array $data
     * @return mixed
     */
    public static function wxpay($mode, $data = [])
    {
        $config = [
            'app_id' => 'wx5239dda9b1a7bb2a',
            'mch_id' => '1520247871',
            'key' => '8f41c6b26031cdfe47415c3376bb621c',
            'notify_url' => 'http://yanda.net.cn/notify.php',
            'http' => [
                'timeout' => 3,
                'connect_timeout' => 3,
            ]
        ];

        $body = [
            'out_trade_no' => $data['orderno'],
            'total_fee' => $data['amount'] * 100,
            'body' => $data['body'],
        ];
        if (!empty($data['openid'])) {
            $body['openid'] = $data['openid'];
        }
        return PayApi::wechat($config)->{$mode}($body);
    }

    /**
     * @desc 支付宝支付
     * @param $mode
     * @param array $data
     * @return mixed
     */
    public static function alipay($mode, $data = [])
    {
        $config = [
            'app_id' => '2088331662165740',
            'notify_url' => 'http://yanda.net.cn/notify.php',
            'return_url' => 'http://yanda.net.cn/notify.php',
            //'ali_public_key' => 'lejian201811@163.com',
            'private_key' => 'h1rrpybot1b5omqmi27wa96fewe18q8c',
            'http' => [
                'timeout' => 3,
                'connect_timeout' => 3,
            ]
        ];
        $body = [
            'out_trade_no' => $data['orderno'],
            'total_fee' => $data['amount'] * 100,
            'subject' => $data['body'],
        ];
        return PayApi::alipay($config)->{$mode}($body);
    }
}
