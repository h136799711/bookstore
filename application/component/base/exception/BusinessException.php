<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-08
 * Time: 20:09
 */

namespace by\component\base\exception;


use think\Exception;
use Throwable;

/**
 * Class BusinessException
 * 任何业务异常的基类
 * @author  hebidu <email:346551990@qq.com>
 * @package by\src\base\exception
 */
class BusinessException extends Exception
{
    public function __construct($message = "", $code = -1, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}