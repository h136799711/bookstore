<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-17 11:23
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\spider\constants;

/**
 * Class BookSiteIntegerType
 * 书籍站点类型
 * @package by\component\spider\constants
 */
class BookSiteIntegerType
{
    const XIA_SHU_BOOK_SITE = 1;

    /**
     * 获取中文描述
     * @param $type
     * @return string
     */
    public static function getDesc($type)
    {
        switch ($type) {
            case self::XIA_SHU_BOOK_SITE:
                return "下书网";
            default:
                return "未知";
        }
    }
}