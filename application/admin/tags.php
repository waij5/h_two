<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用行为扩展定义文件
return [
    // 应用结束
    'app_end'      => [
        'app\\admin\\behavior\\AdminLog',
    ],
    \app\admin\model\OrderItems::TAG_DEDUCT_SUCCESS => [
        'app\\admin\\behavior\\Order', 'deductNdReverse'
    ],
    \app\admin\model\OrderItems::TAG_REVERSE_DEDUCT => [
        'app\\admin\\behavior\\Order', 'deductNdReverse'
    ],
    \app\admin\model\OrderItems::TAG_BATCH_DEDUCT => [
        'app\\admin\\behavior\\Order', 'batchDeduct'
    ],
    \app\admin\model\OrderItems::TAG_BATCH_REVERSE => [
        'app\\admin\\behavior\\Order', 'batchReverse'
    ],
    \app\admin\model\OrderItems::TAG_SWITCH_ITEM => [
        'app\\admin\\behavior\\Order', 'switchItem'
    ],
    \app\admin\model\OrderItems::TAG_PAY_ORDER => [
        'app\\admin\\behavior\\Order', 'payOrder'
    ],
    \app\admin\model\OrderItems::TAG_CHARGEBACK => [
        'app\\admin\\behavior\\Order', 'chargeback'
    ],
    \app\admin\model\CustomerOsconsult::TAG_SAVE_OSCONSULT => [
        'app\\admin\\behavior\\CustomerOsconsult', 'run'
    ],
    \app\admin\model\CustomerConsult::TAG_SAVE_CONSULT => [
        'app\\admin\\behavior\\CustomerConsult', 'run'
    ],
    'rvinfo_save' => [
        'app\\admin\\behavior\\Rvinfo', 'run'
    ],
    'book_success' => [
        'app\\admin\\behavior\\Operaterota', 'success'
    ],
    'book_cancel' => [
        'app\\admin\\behavior\\Operaterota', 'cancel'
    ],
    'customer_account_change' => [
        'app\\admin\\behavior\\Customer', 'accountChange'
    ],
    'yjy_passport_create_token' => [
        'app\\admin\\behavior\\Passport', 'createToken'
    ],
];
