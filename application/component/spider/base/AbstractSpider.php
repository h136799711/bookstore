<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-14 13:47
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\spider\base;


use app\component\spider\base\entity\SpiderUrlEntity;
use by\infrastructure\base\CallResult;

abstract class AbstractSpider
{

    /**
     * 获取指定个数待爬取的地址,
     * @param int $limit 默认10个
     * @return array
     */
    abstract function nextBatchUrls($limit = 10);

    /**
     * 解析当前的地址
     * @param SpiderUrlEntity $urlEntity
     * @return CallResult
     */
    abstract function parseUrl(SpiderUrlEntity $urlEntity);
}