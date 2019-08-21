<?php

namespace app\admin\model;

use think\Model;

class DeductImg extends Model
{

    // 表名
    protected $name = 'deduct_imgs';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    const SAVE_PATH = APP_PATH . DS . 'data' . DS . 'deduct_receipts';
    const SAVE_URL  = '/deduct_receipts';

    public static function getSaveUrl($saveName)
    {
        return static::SAVE_URL . '/' . ltrim($saveName, 'DS');
    }

    public static function getThumbSets()
    {
        return [
                'width' => \think\Config::get('deduct_img.width') ? \think\Config::get('deduct_img.width') : 800,
                'height' => \think\Config::get('deduct_img.height') ? \think\Config::get('deduct_img.height') : 800];
    }

    public static function getSavePath($saveName)
    {
        return realpath(static::SAVE_PATH) . DS . ltrim($saveName, DS);
    }

    public static function saveFromFile(\think\File &$file)
    {
        $suffix      = strtolower(pathinfo($file->getInfo('name'), PATHINFO_EXTENSION));
        $imgInfo     = getimagesize($file->getPathname());
        $imagewidth  = isset($imgInfo[0]) ? $imgInfo[0] : $imagewidth;
        $imageheight = isset($imgInfo[1]) ? $imgInfo[1] : $imageheight;
        $sha1        = $file->hash();
        $params      = array(
            'filesize'    => $file->getInfo('size'),
            'imagewidth'  => $imagewidth,
            'imageheight' => $imageheight,
            'imagetype'   => $suffix,
            'imageframes' => 0,
            'mimetype'    => $file->getInfo('type'),
            'url'         => static::getSaveUrl($file->getSaveName()),
            'uploadtime'  => time(),
            'storage'     => 'local',
            'sha1'        => $sha1,
        );

        return static::create($params);
    }

    public static function saveFromImage(\think\Image &$file, $savePath, $realPath = '')
    {
        $realPath = empty($realPath) ? static::getSavePath($savePath) : $realPath;
        $sha1     = hash_file('sha1', $realPath);
        $params   = array(
            'filesize'    => filesize($realPath),
            'imagewidth'  => $file->width(),
            'imageheight' => $file->height(),
            'imagetype'   => $file->type(),
            'imageframes' => 0,
            'mimetype'    => $file->mime(),
            'url'         => static::getSaveUrl($savePath),
            'uploadtime'  => time(),
            'storage'     => 'local',
            'sha1'        => $sha1,
        );

        return static::create($params);
    }

}
