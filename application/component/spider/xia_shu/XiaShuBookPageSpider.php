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
use app\component\spider\constants\BookSiteType;
use app\component\spider\xia_shu\parser\XiaShuBookPageParser;
use app\component\spider\xia_shu\repo\XiaShuSpiderBookPageUrlRepo;

/**
 * Class XiaShuBookPageSpider
 * 一本书爬取
 * @package app\component\spider\xia_shu
 */
class XiaShuBookPageSpider extends AbstractSpider
{
    private $bookId;
    private $startPage;
    private $latestPageIndex;

    public function getBookLatestPageUrl()
    {
        return BookSiteType::XIA_SHU_BOOK_SITE . "/" . $this->bookId . "/read_" . $this->latestPageIndex . ".html";
    }

    public function getBookPageUrl()
    {
        return BookSiteType::XIA_SHU_BOOK_SITE . "/" . $this->bookId . "/read_" . $this->startPage . ".html";
    }

    public function __construct($bookId)
    {
        $this->bookId = $bookId;
        $this->latestPageIndex = 0;
        $this->startPage = 1;
    }

    function nextBatchUrls($limit = 10)
    {
        // 根据当前书页，生成下一个书页
        $this->startPage += $limit;
        $this->latestPageIndex += $this->startPage - 1;
    }

    // construct

    function parseUrl($data)
    {
        $flag = true;
        $parser = new XiaShuBookPageParser();
        while ($flag) {
            $url = $this->getBookPageUrl();
            // 读取书页
            $result = $parser->parse($this->bookId, $this->startPage, $url);

            if (!$result->isSuccess()) {
                // 读取失败了
                // 保证当前读取的url记录
                $this->updateLastestPageUrl();
                break;
            }

            // 读取下一页
            $this->nextBatchUrls(1);
        }
    }

    public function start()
    {
        $this->parseUrl([]);
    }

    /**
     * 更新当前书本最新书页地址
     */
    private function updateLastestPageUrl()
    {
        $this->latestPageIndex;
        $repo = new XiaShuSpiderBookPageUrlRepo();
        $repo->where('book_id', $this->bookId)->save(['url' => $this->getBookLatestPageUrl()]);
    }

    private function parsePage()
    {

    }

    private function isBookStateOver()
    {

    }
}