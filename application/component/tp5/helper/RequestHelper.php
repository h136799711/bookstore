<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-01 10:15
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\tp5\helper;


use by\infrastructure\helper\CallResultHelper;
use think\Request;

class RequestHelper
{
    public static function param($key, $default = '', $emptyErrMsg = '')
    {
        $request = Request::instance();
        $value = $request->param($key, $default);

        if ($default == $value && !empty($emptyErrMsg)) {
            return CallResultHelper::fail($emptyErrMsg);
        }
        $value = self::filterEmoji($value);
        return CallResultHelper::success($value);
    }

    protected static function filterEmoji($strText, $bool = false)
    {
        $preg = '/\\\ud([8-9a-f][0-9a-z]{2})/i';
        if ($bool == true) {
            $boolPregRes = (preg_match($preg, json_encode($strText, true)));
            return $boolPregRes;
        } else {
            $strPregRes = (preg_replace($preg, '', json_encode($strText, true)));
            $strRet = json_decode($strPregRes, JSON_OBJECT_AS_ARRAY);

            if (is_string($strRet) && strlen($strRet) == 0) {
                return "";
            }

            return $strRet;
        }
    }
}