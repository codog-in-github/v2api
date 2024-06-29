<?php

namespace App\Exceptions;

//自定义异常处理类

class ErrorException extends BaseException
{
    public $code = -1;
    public $msg = 'invalid parameters';
    public $data = '';

    /**
     * 构造函数
     * @param:
     */
    public function __construct123($msg, $code = -1, $data = '')
    {
        $this->msg = $msg;
        $this->code = $code;
        $this->data = $data;

    }

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
