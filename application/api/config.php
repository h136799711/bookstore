<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-05 19:34
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

return [

    'exception_handle'       => '\\app\\src\\base\\exception\\JsonExceptionHandler',

    //********************START 文件相关配置 START***************
    //支持裁减大小宽
    'file_cfg'=>[
        'picture_crop_size'  => [50,60,120,150,160,180,200,240,360,400,480,640,720,960],
        //裁减图位置无需/结尾
        'thumbnail_path'=>'./upload/user_picture_thumb',
    ],
    //********************END  文件相关配置 END***************

    // 默认输出类型
    'default_return_type'    => 'json',
    // 异常页面的模板文件
    'exception_tmpl'         => APP_PATH . 'api' . DS . 'view/exception.json',
    // 异常处理忽略的错误类型，支持PHP所有的错误级别常量，多个级别可以用|运算法
    // 参考：http://php.net/manual/en/errorfunc.constants.php
    'exception_ignore_type'  => 0,
    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 错误定向页面
    'error_page'             => '',
    // 显示错误信息
    'show_error_msg'         => false,


];