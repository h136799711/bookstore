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

namespace by\component\spider\xia_shu\entity;


use by\component\spider\base\entity\SpiderUrlEntity;
use by\component\spider\base\interfaces\ToArrayInterface;
use by\component\spider\constants\BookSiteType;
use by\infrastructure\helper\Object2DataArrayHelper;

/**
 * Class XiaShuSpiderBookPageUrlEntity
 * 书籍的书页爬取url
 * @package by\component\spider\xia_shu\entity
 */
class XiaShuSpiderBookPageUrlEntity extends SpiderUrlEntity implements ToArrayInterface
{
    public function toArray()
    {
        return Object2DataArrayHelper::getDataArrayFrom($this);
    }


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
    private $bookId;
    private $source;
    private $spiderInfo;
    private $spiderStatus;

    public function __construct($url)
    {
        parent::__construct($url);
        $this->setSpiderStatus(self::SPIDER_STATUS_INIT);
        $this->setSpiderInfo('');
        $this->setSource(BookSiteType::XIA_SHU_BOOK_SITE);
    }

    public function getSourceType()
    {
        return BookSiteType::getDesc($this->getSource());
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * 书本id
     * @return mixed
     */
    public function getBookId()
    {
        return $this->bookId;
    }

    /**
     * 设置书本id
     * @param mixed $bookId
     */
    public function setBookId($bookId)
    {
        $this->bookId = $bookId;
    }

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