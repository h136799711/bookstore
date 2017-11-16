<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-14 13:48
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\spider;


use app\component\spider\base\AbstractSpider;
use app\component\spider\base\entity\SpiderUrlEntity;
use app\component\spider\xia_shu\entity\XiaShuSpiderBookUrlEntity;
use by\infrastructure\helper\CallResultHelper;

class EchoSpider extends AbstractSpider
{
    private $interval = 3;
    private $limit = 10;

    public static function newSpider()
    {
        return new EchoSpider();
    }

    public function init($interval = 3, $limit = 10)
    {
        if (!empty($interval)) {
            $interval = intval($interval);
            $this->interval = $interval <= 1 ? 1 : $interval;
        }
        if (!empty($limit)) {
            $this->limit = $limit;
        }
        return $this;
    }

    public function start()
    {
//        while (true) {
        echo 'current pid= ' . posix_getpid(), "\n";
            $urls = $this->nextBatchUrls($this->limit);
            foreach ($urls as $urlEntity) {
                $this->parseUrl($urlEntity);
            }
//            sleep($this->interval);
//        }

        return $this;
    }

    function nextBatchUrls($limit = 10)
    {
        return [new XiaShuSpiderBookUrlEntity("https://www.xiashu.cc/52977")];
    }

    function parseUrl(SpiderUrlEntity $urlEntity)
    {
        echo 'parse' . $urlEntity->getUrl(), "\n";
        return CallResultHelper::success();
    }

}