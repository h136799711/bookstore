<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-17 11:37
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\spider\xia_shu;


use app\component\spider\base\AbstractSpider;

class XiaShuBookPageSpider extends AbstractSpider
{
    public function __construct()
    {
        // TODO construct
    }

    function nextBatchUrls($limit = 10)
    {
        // TODO: 根据当前书页，生成下一个书页
    }

    // construct

    function parseUrl($data)
    {
        // TODO: 根据当前书页，读取书页信息
    }

}