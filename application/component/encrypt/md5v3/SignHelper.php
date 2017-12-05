<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-05 20:15
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\encrypt\md5v3;


/**
 * Class SignHelper
 * 签名工具
 * @package app\component\encrypt\md5v3
 */
class SignHelper
{

    public static function sign($time, $type, $data, $secret, $notifyId)
    {
        $sign = $time.$type.$data.$secret.$notifyId;
        return md5($sign);
    }
}