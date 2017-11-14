<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-14 14:15
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\spider\constants;


class UrlType
{
    const XIA_SHU_BOOK_URL = "XIA_SHU_BOOK_URL";
    const XIA_SHU_BOOK_PAGE_URL = "XIA_SHU_BOOK_PAGE_URL";

    /**
     * 书籍
     */
    private $list = [
        'XIA_SHU_BOOK_URL' => '/^((https|http)?:\/\/)www.xiashu.cc\/\d+\/?$/i',
        'XIA_SHU_BOOK_PAGE_URL' => "/^((https|http)?:\/\/)www.xiashu.cc/\d+/read_\d+.html/i"
    ];

    public static function getUrlType($url)
    {
        // TODO 
        return UrlType::XIA_SHU_BOOK_PAGE_URL;
    }
}