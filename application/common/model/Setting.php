<?php

namespace app\common\model;

use think\Model;

/**
 * 配置模型
 */
class Setting extends Model
{

    // 表名,不含前缀
    protected $name = 'setting';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    // 追加属性
    protected $append = [
    ];

    /**
     * 将字符串解析成键值数组
     * @param string $text
     * @return array
     */
    public static function decode($text, $split = "\r\n")
    {
        $content = explode($split, $text);
        $arr = [];
        foreach ($content as $k => $v) {
            if (stripos($v, "|") !== false) {
                $item = explode('|', $v);
                $arr[$item[0]] = $item[1];
            }
        }
        return $arr;
    }

    /**
     * 将键值数组转换为字符串
     * @param array $array
     * @return string
     */
    public static function encode($array, $split = "\r\n")
    {
        $content = '';
        if ($array && is_array($array)) {
            $arr = [];
            foreach ($array as $k => $v) {
                $arr[] = "{$k}|{$v}";
            }
            $content = implode($split, $arr);
        }
        return $content;
    }

    /**
     * 本地上传配置信息
     * @return array
     */
    public static function upload()
    {
        $uploadcfg = config('upload');

        $upload = [
            'cdnurl'    => $uploadcfg['cdnurl'],
            'uploadurl' => $uploadcfg['uploadurl'],
            'bucket'    => 'local',
            'maxsize'   => $uploadcfg['maxsize'],
            'mimetype'  => $uploadcfg['mimetype'],
            'multipart' => [],
            'multiple'  => $uploadcfg['multiple'],
        ];
        return $upload;
    }

}
