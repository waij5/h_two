<?php

// +----------------------------------------------------------------------
// | Author: Leekaen <leekaenwoo1990@outlook.com>
// +----------------------------------------------------------------------

return [
    'default'     => 'pm_his_api',

    'resolutions' => [
        'yx'         => [
            'class'  => \app\common\library\YxSms::class,
            'config' => [
                'appId'  => 'cf_1jia1',
                'appKey' => '9a33f27b0adb96eb45123d81277c4063',
            ],
            'ali'    => [],
        ],
        'pm_his_api' => [
            'class'  => \app\common\library\PmHisApi::class,
            'config' => [
                'tag' => 'test',
                'url' => 'http://114.98.230.232:10001/api/sendsms',
            ],
        ],
    ],

    'templates' => [
        ''
    ],
];
