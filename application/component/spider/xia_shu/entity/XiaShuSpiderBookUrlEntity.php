<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-14 14:41
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\spider\xia_shu\entity;


use app\component\spider\base\entity\SpiderUrlEntity;

/**
 * Class XiaShuSpiderBookUrlEntity
 * 书籍爬取url
 * @package app\component\spider\xia_shu\entity
 */
class XiaShuSpiderBookUrlEntity extends SpiderUrlEntity
{
    /**
     * 初始化
     */
    const SPIDER_STATUS_INIT = 0;

    /**
     * 等待下次，非第一次
     */
    const SPIDER_STATUS_WAITING = 2;

    /**
     * 该数据正在使用中
     */
    const SPIDER_STATUS_BUSYING = 4;

    /**
     * 使用结束
     */
    const SPIDER_STATUS_OVER = 1;

    public function __construct($url)
    {
        parent::__construct($url);
        $this->setSpiderStatus(self::SPIDER_STATUS_INIT);
        $this->setSpiderInfo('');
    }

    private $spiderInfo;
    private $spiderStatus;

    /**
     * @return string
     */
    public function getSpiderInfo()
    {
        return $this->spiderInfo;
    }

    /**
     * @param string $spiderInfo
     */
    public function setSpiderInfo($spiderInfo)
    {
        $this->spiderInfo = $spiderInfo;
    }

    /**
     * @return mixed
     */
    public function getSpiderStatus()
    {
        return $this->spiderStatus;
    }

    /**
     * @param mixed $spiderStatus
     */
    public function setSpiderStatus($spiderStatus)
    {
        $this->spiderStatus = $spiderStatus;
    }

}