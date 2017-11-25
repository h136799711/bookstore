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
use app\component\spider\constants\BookSiteIntegerType;
use app\component\spider\constants\BookSiteType;
use app\component\spider\xia_shu\entity\XiaShuBookEntity;
use app\component\spider\xia_shu\entity\XiaShuSpiderBookPageUrlEntity;
use app\component\spider\xia_shu\parser\XiaShuBookPageParser;
use app\component\spider\xia_shu\parser\XiaShuBookStateParser;
use app\component\spider\xia_shu\repo\XiaShuBookPageRepo;
use app\component\spider\xia_shu\repo\XiaShuSpiderBookPageUrlRepo;
use think\Model;

/**
 * Class XiaShuBookPageSpider
 * 一本书爬取
 * @package app\component\spider\xia_shu
 */
class XiaShuBookPageSpider extends AbstractSpider
{
    const  MaxNotUpdateTime = 3 * 30 * 24 * 3600; // 90 天
    private $bookId;
    private $startPage;
    private $latestPageIndex;

    public function __construct($bookId)
    {
        $this->bookId = $bookId;
        $this->setStartPage(1);
    }

    private function setStartPage($pageNO)
    {
        $this->startPage = $pageNO;
        $this->latestPageIndex = $pageNO - 1;
    }

    public function start()
    {
        $repo = new XiaShuSpiderBookPageUrlRepo();
        $entity = new XiaShuSpiderBookPageUrlEntity($this->getBookPageUrl());
        $entity->setBookId($this->bookId);
        $result = $repo->addIfNotExist($entity);
        if ($result->isSuccess()) {
            $bookPage = (new XiaShuBookPageRepo())->where('book_id', 'eq', $this->bookId)->order('page_no', 'desc')->find();
            if (!empty($bookPage)) {
                $pageNo = $bookPage['page_no'] + 1;
            } else {
                $pageNo = 1;
            }
            $this->setStartPage($pageNo);
            $this->parseUrl([]);
        } else {
            echo 'book_page_spider add fail. ' . $result->getMsg();
        }
    }

    public function getXiashuBookUrl()
    {
        return BookSiteType::XIA_SHU_BOOK_SITE . "/" . $this->bookId;
    }

    public function getBookPageUrl()
    {
        return BookSiteType::XIA_SHU_BOOK_SITE . "/" . $this->bookId . "/read_" . $this->startPage . ".html";
    }

    function parseUrl($data)
    {
        $flag = true;
        $parser = new XiaShuBookPageParser();

        if (array_key_exists('should_create_text', $data)) {
            $parser->setShouldCreateText($data['should_create_text']);
        }

        while ($flag) {
            echo 'read page_no = ' . $this->startPage, "\n";
            $url = $this->getBookPageUrl();
            // 读取书页
            $result = $parser->parse($this->bookId, $this->startPage, $url);

            if (!$result->isSuccess()) {
                // 读取失败了
                echo 'read fail ' . $result->getMsg(), "\n";
                // 保证当前读取的url记录
                $this->updateLastestPageUrl();
                // 检查书籍是否完结
                $this->checkIsOver();
                break;
            }
            // 读取下一页
            $this->nextBatchUrls(1);
        }
    }

    /**
     * 检查书籍是否完结
     */
    private function checkIsOver()
    {
        //
        $url = $this->getXiashuBookUrl();
        $parser = new XiaShuBookStateParser($url);
        $ret = $parser->parse();
        if ($ret->isSuccess()) {
            $repo = new XiaShuSpiderBookPageUrlRepo();
            $map = ['book_id' => $this->bookId, 'source_type' => BookSiteIntegerType::XIA_SHU_BOOK_SITE];

            if ($ret->getData() == XiaShuBookEntity::STATE_END) {
                $repo->save(['is_spider_over' => 1, 'spider_info' => '书已完结且爬取完成'], $map);
            } else {
                // 判断update_time 是否大于
                $result = $repo->where($map)->find();
                if (!empty($result)) {
                    if ($result instanceof Model) {
                        // 这个更新时间在每次读取到该书新的章节的时候会更新一次
                        // 没有读取到新章节不会更新
                        $updateTime = $result->getData('update_time');
                        if (time() - self::MaxNotUpdateTime > $updateTime) {
                            // 超过 MaxNotUpdateTime 没有更新了,则判断书籍断更了
                            $day = self::MaxNotUpdateTime / 86400;
                            $info = "超过 " . $day . " 天没有更新了, 可能断更了";
                            $repo->save(['is_spider_over' => 2, 'spider_info' => $info], $map);
                        }
                    }
                }
            }
        }
    }

    /**
     * 更新当前书本最新书页地址
     */
    private function updateLastestPageUrl()
    {
        $repo = new XiaShuSpiderBookPageUrlRepo();
        $map = ['book_id' => $this->bookId, 'source_type' => BookSiteIntegerType::XIA_SHU_BOOK_SITE];
        $repo->saveIfUpdateUrl($map, $this->getBookLatestPageUrl());
    }

    public function getBookLatestPageUrl()
    {
        return BookSiteType::XIA_SHU_BOOK_SITE . "/" . $this->bookId . "/read_" . $this->latestPageIndex . ".html";
    }

    function nextBatchUrls($limit = 10)
    {
        // 根据当前书页，生成下一个书页
        $this->startPage += $limit;
        $this->latestPageIndex += $this->startPage - 1;
    }

    private function parsePage()
    {

    }

    private function isBookStateOver()
    {

    }
}