<?php

namespace yjy\exception;

use think\Exception;

/**
 * 事务 相关异常处理类
 */
class TransException extends \Exception
{
    /**
     * TransException constructor.
     * @param string    $message
     * @param array     $config
     * @param string    $sql
     * @param int       $code
     */
    public function __construct($message, $code = 60800, Throwable $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
    }

}
