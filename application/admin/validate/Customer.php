<?php

namespace app\admin\validate;

use think\Validate;

class Customer extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
                        'ctm_name' => 'require',
    ];
    /**
     * 提示消息
     */
    protected $message = [
                        'ctm_name.require'  =>  '用户名必须',
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['ctm_name'],
        'edit' => ['ctm_name'],
    ];
    
}
