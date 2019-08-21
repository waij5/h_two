<?php

namespace app\admin\validate;

use think\Validate;

class CustomerBalance extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'total.max' => 'DFSFASDF',
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [],
        'edit' => [],
        'prestore' => [
                        'total' => 'require|number|max:25',
                        'pay_total' => 'require|number|gt:1',
                        'deptment_id'  => 'require',
                        'rec_admin_id' => 'require',

        ],
    ];
    
}
