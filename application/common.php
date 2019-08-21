<?php

// 公共助手函数

if (!function_exists('__')) {

    /**
     * 获取语言变量值
     * @param string    $name 语言变量名
     * @param array     $vars 动态变量值
     * @param string    $lang 语言
     * @return mixed
     */
    function __($name, $vars = [], $lang = '')
    {
        if (is_numeric($name)) {
            return $name;
        }

        if (!is_array($vars)) {
            $vars = func_get_args();
            array_shift($vars);
            $lang = '';
        }
        return think\Lang::get($name, $vars, $lang);
    }

}

if (!function_exists('format_bytes')) {

    /**
     * 将字节转换为可读文本
     * @param int $size 大小
     * @param string $delimiter 分隔符
     * @return string
     */
    function format_bytes($size, $delimiter = '')
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 6; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . $delimiter . $units[$i];
    }

}

if (!function_exists('datetime')) {

    /**
     * 将时间戳转换为日期时间
     * @param int $time 时间戳
     * @param string $format 日期时间格式
     * @return string
     */
    function datetime($time, $format = 'Y-m-d H:i:s')
    {
        $time = is_numeric($time) ? $time : strtotime($time);
        return date($format, $time);
    }

}

if (!function_exists('human_date')) {

    /**
     * 获取语义化时间
     * @param int $time 时间
     * @param int $local 本地时间
     * @return string
     */
    function human_date($time, $local = null)
    {
        return \fast\Date::human($time, $local);
    }

}

if (!function_exists('cdnurl')) {

    /**
     * 获取CDN的地址
     * @param int $time 时间戳
     * @param string $format 日期时间格式
     * @return string
     */
    function cdnurl($url)
    {
        return preg_match("/^https?:\/\/(.*)/i", $url) ? $url : think\Config::get('cdnurl') . $url;
    }

}

if (!function_exists('is_really_writable')) {

    /**
     * 判断文件或文件夹是否可写
     * @param    string $file 文件或目录
     * @return    bool
     */
    function is_really_writable($file)
    {
        if (DIRECTORY_SEPARATOR === '/') {
            return is_writable($file);
        }
        if (is_dir($file)) {
            $file = rtrim($file, '/') . '/' . md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === false) {
                return false;
            }
            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);
            return true;
        } elseif (!is_file($file) or ($fp = @fopen($file, 'ab')) === false) {
            return false;
        }
        fclose($fp);
        return true;
    }

}

if (!function_exists('rmdirs')) {

    /**
     * 删除文件夹
     * @param string $dirname 目录
     * @param bool $withself 是否删除自身
     * @return boolean
     */
    function rmdirs($dirname, $withself = true)
    {
        if (!is_dir($dirname)) {
            return false;
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        if ($withself) {
            @rmdir($dirname);
        }
        return true;
    }

}

if (!function_exists('copydirs')) {

    /**
     * 复制文件夹
     * @param string $source 源文件夹
     * @param string $dest 目标文件夹
     */
    function copydirs($source, $dest)
    {
        if (!is_dir($dest)) {
            mkdir($dest, 0755);
        }
        foreach (
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->isDir()) {
                $sontDir = $dest . DS . $iterator->getSubPathName();
                if (!is_dir($sontDir)) {
                    mkdir($sontDir);
                }
            } else {
                copy($item, $dest . DS . $iterator->getSubPathName());
            }
        }
    }

}

if (!function_exists('comselectinitparams')) {
    /**
     * 公用弹窗的参数处理，传址传递
     * @param input
     * url, title, field ---- multi
     * parentSelector, pkinputname ---- redirect
     * fields ---- single
     */
    function comselectinitparams(&$yjyComSelectParams, $fields = [])
    {
        $mode                       = input('mode', '');
        $yjyComSelectParams['mode'] = $mode;
        $fieldsSelctorPre           = '#field_';

        //直接传ID选择器 可能会被解析为锚点，采用@代替，使用时进行转换
        if (!empty(input('fldSelPre'))) {
            $fieldsSelctorPre = str_replace('@', '#', input('fldSelPre'));
        }

        if ($mode == 'redirect') {
            $url                                  = input('url', '');
            $title                                = input('title', '');
            $field                                = input('field', '');
            $yjyComSelectParams['redirectParams'] = [
                'url'   => $url,
                'title' => $title,
                'field' => $field,
            ];
        } else {
            if ($mode == 'multi') {
                $parentSelector = $yjyComSelectParams['parentSelector'];
                if (!empty(input('parentSelector'))) {
                    $parentSelector = input('parentSelector');
                }
                $pkInputName                       = input('pkinputname', 'pkinputname');
                $yjyComSelectParams['multiParams'] = [
                    'parentSelector' => $parentSelector,
                    'pkinputname'    => $pkInputName,
                    'fields'         => $fields,
                ];
            } else {
                $singleFields = [];
                foreach ($fields as $field) {
                    $singleFields[$field] = $fieldsSelctorPre . $field;
                }
                $yjyComSelectParams['singleParams'] = [
                    'fields' => $singleFields,
                ];
            }
        }
    }
}

if (!function_exists('getMaxDiscount')) {
    /**
     * @param float $amount 原价
     * @param float $minAmount 最低价
     * @param float $maxRate 最大折(%) 如 5折 为 50(%)
     * @return Array 折后金额, 优惠金额, 折扣 如 5折 为 50(%)
     */
    function getMaxDiscount($amount, $minAmount, $maxRate)
    {
        $discountArr = [
            'amount'           => 0,
            'discount_amount'  => 0,
            'discount_percent' => 100.00,
        ];

        if ($amount <= 0) {
            return $discountArr;
        }

        $calcAmount = 1.0 * $amount * $maxRate / 100;
        if ($calcAmount >= $minAmount) {
            $discountArr['amount']           = $calcAmount;
            $discountArr['discount_amount']  = $amount - $calcAmount;
            $discountArr['discount_percent'] = $maxRate;
        } else {
            $discountArr['amount']           = $minAmount;
            $discountArr['discount_amount']  = $amount - $minAmount;
            $discountArr['discount_percent'] = ($minAmount / $amount) * 100;
        }

        return $discountArr;
    }
}
if (!function_exists('isDiscountAllowed')) {
    /**
     * 判断折扣是否被允许
     * @param float $checkAmount 待检查的金额
     * @param float $amount 原价
     * @param float $minAmount 最低价
     * @param float $maxRate 最大折(%) 如 5折 为 50(%)
     * @return boolean 是/否 允许
     */
    function isDiscountAllowed($checkAmount, $amount, $minAmount, $maxRate)
    {
        $discountArr = getMaxDiscount($amount, $minAmount, $maxRate);
        if ($checkAmount < $discountArr['amount']) {
            return false;
        }

        return true;
    }
}
if (!function_exists('getMaskString')) {
    /**
     * 获取掩码后字符串
     * @param string $oriString 原始字符串
     * @param string $maskSymbol 掩码字符
     * @param int $maskLenth 掩码长度
     * @return string $maskedString 掩码处理后字符串
     */
    function getMaskString($oriString, $maskSymbol = '*', $maskLenth = 4)
    {
        $maskedString = $oriString;

        if ($oriString && ($strLen = @mb_strlen((string) $oriString)) > 2) {
            //实际输出掩码长度
            $realMaskLen = ($strLen >= ($maskLenth + 2)) ? $maskLenth : ($strLen - 2);
            //输出掩码起始位置
            $maskStartPos = 0 + floor(($strLen - $realMaskLen) / 2);
            //掩码替换

            $maskedString = mb_substr($oriString, 0, $maskStartPos) . str_repeat($maskSymbol, $realMaskLen) . mb_substr($oriString, $maskStartPos + $realMaskLen);

            // $maskedString = substr_replace($oriString, str_repeat($maskSymbol, $realMaskLen), $maskStartPos, $realMaskLen);
        }

        return $maskedString;
    }
}

if (!function_exists('xml_to_array')) {
    //将xml数据转换为数组格式。
    function xml_to_array($xml)
    {
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if (preg_match_all($reg, $xml, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i++) {
                $subxml = $matches[2][$i];
                $key    = $matches[1][$i];
                if (preg_match($reg, $subxml)) {
                    $arr[$key] = xml_to_array($subxml);
                } else {
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }
}

if (!function_exists('cal_excel_col')) {
/**
 * @summary 根据基准列，增加列数，计算新的列名
 * @param string $curColumn 基准列名不含行号， 范围[0-25]
 * @param int $plusStep 多少列后
 */
    function cal_excel_col($curColumn, $plusStep = 1)
    {
        //当前列名 不含行  如 A1 中的 A 即为列名
        if (preg_match('/[A-Za-z]+/', $curColumn) == false) {
            throw new \Exception("错误的列名", 1);
        }
        //<0为非法操作 不考虑 超过或等于26列的
        if ($plusStep < 0 || $plusStep >= 26) {
            throw new \Exception("只能计算0-25列后的情况", 1);
        } else {
            $curColumn = strtoupper($curColumn);
            $columnLen = strlen($curColumn);
            $columnArr = array_reverse(str_split($curColumn));
            // $columnArr = array_reverse($columnArr);

            $ordA   = ord('A');
            $ordZ   = ord('Z');
            $curPos = 0;
            do {
                //极端情况 增加一位如 ZZ + 1 => AAA
                if (!isset($columnArr[$curPos])) {
                    $columnArr[$curPos] = 'A';
                    break;
                }

                $tmpOrd = ord($columnArr[$curPos]) + $plusStep;
                if ($tmpOrd <= $ordZ) {
                    $columnArr[$curPos] = chr($tmpOrd);
                    break;
                } else {
                    //$tmpOrd % $ordZ + $ordA
                    $columnArr[$curPos] = chr($tmpOrd - $ordZ - 1 + $ordA);
                    //因为 限制最大 增加列为 0 - 25 所以只会为1
                    $plusStep = 1;
                }

                $curPos++;
            } while ($curPos < $columnLen || $plusStep > 0);

            return implode('', array_reverse($columnArr));
        }
    }
}
