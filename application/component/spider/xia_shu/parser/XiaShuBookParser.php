<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-16 18:40
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\spider\xia_shu\parser;


use app\component\spider\constants\BookSiteType;
use app\component\spider\xia_shu\entity\XiaShuAuthorEntity;
use app\component\spider\xia_shu\entity\XiaShuBookEntity;
use app\component\spider\xia_shu\entity\XiaShuBookSourceEntity;
use app\component\spider\xia_shu\repo\XiaShuAuthorRepo;
use app\component\spider\xia_shu\repo\XiaShuBookRepo;
use app\component\spider\xia_shu\repo\XiaShuBookSourceRepo;
use by\infrastructure\helper\CallResultHelper;
use simplehtmldom_1_5\simple_html_dom;
use Sunra\PhpSimple\HtmlDomParser;
use think\exception\ErrorException;

class XiaShuBookParser
{
    private $errorInfo;
    private $url;

    private $cateArray = [
        "玄幻奇幻", "都市生活", "仙侠武侠",
        "职场商战", "历史传奇", "军事谍战",
        "科幻未来", "游戏竞技", "灵异悬疑",
        "短篇小说", "现代言情", "古代言情",
        "仙侠幻情", "穿越架空", "总裁豪门",
        "浪漫青春", "耽美同人"
    ];

    private function getCateId($cateName)
    {
        foreach ($this->cateArray as $key => $value) {
            if (trim($cateName) == $value) {
                return $key + 1;
            }
        }
        return 0;
    }

    // construct
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * 1. 保存作者信息
     * 2. 保存书本信息
     * 3. 保存第一个书籍页面地址用于书籍页面爬取
     * @return \by\infrastructure\base\CallResult
     */
    public function parse()
    {
        try {
            $dom = HtmlDomParser::file_get_html($this->url);
            $entity = new XiaShuBookEntity();
            // 设置书本概述
            $summary = $this->getBookSummary($dom);
            $entity->setSummary($summary);
            $ret = $dom->find('meta');

            if (count($ret) == 0) {
                $this->errorInfo = "该书页没有meta标签";
            } else {
                $this->errorInfo = "该书页没有有效的meta标签,无法解析";
            }

            $authorRepo = new XiaShuAuthorRepo();
            $updateTimeProperty = "og:novel:update_time";
            $stateProperty = "og:novel:status";
            $bookNameProperty = "og:novel:book_name";
            $authorProperty = "og:novel:author";
            $thumbnailProperty = "og:image";
            $categoryProperty = "og:novel:category";

            foreach ($ret as $domNode) {
                $property = trim($domNode->getAttribute('property'));
                $content = trim($domNode->getAttribute('content'));
                if (!$property) {
                    continue;
                }
                $this->errorInfo = "";

                // 连载状态
                if ($property == $stateProperty) {
                    $state = $this->getState($content);
                    $entity->setState($state);
                }

                // 书名
                if ($property == $bookNameProperty) {
                    $entity->setTitle($content);
                }

                // 更新时间
                if ($property == $updateTimeProperty) {
                    if (!empty($content)) {
                        $entity->setUpdateTime(strtotime($content));
                    }
                }

                // 书籍封面图片
                if ($property == $thumbnailProperty) {
                    $entity->setThumbnail($content);
                }

                // 书籍分类信息
                if ($property == $categoryProperty) {
                    $cateId = $this->getCateId($content);
                    $entity->setCateId($cateId);
                }

                // 作者信息
                if ($property == $authorProperty) {
                    $authorId = $this->getAuthorId($content, $authorRepo);
                    $entity->setAuthorId($authorId);
                    $entity->setAuthorName($content);
                }
            }

            // 检查是否书籍信息都设置了
            if (is_null($entity->getTitle())) {
                return CallResultHelper::fail('缺失书名');
            }

            if (is_null($entity->getCateId())) {
                return CallResultHelper::fail('缺失分类ID');
            }

            if (is_null($entity->getAuthorName())) {
                return CallResultHelper::fail('缺失作者笔名');
            }

            $bookRepo = new XiaShuBookRepo();
            $result = $bookRepo->addIfNotExist($entity);
            if ($result->isSuccess()) {
                $this->addXiaShuBookSource($result->getData(), $this->url);
                return CallResultHelper::success('success');
            } else {
                return CallResultHelper::fail($result->getMsg());
            }
        } catch (ErrorException $exception) {
            return CallResultHelper::fail($exception->getMessage());
        }
    }

    private function addXiaShuBookSource($bookId, $bookAddress)
    {
        $bookSourceEntity = new XiaShuBookSourceEntity();
        $bookSourceEntity->setBookAddress($bookAddress);
        $bookSourceEntity->setBookId($bookId);
        $bookSourceEntity->setBookSourceAddress(BookSiteType::XIA_SHU_BOOK_SITE);
        $bookSourceEntity->setBookSourceName(BookSiteType::getDesc(BookSiteType::XIA_SHU_BOOK_SITE));

        return (new XiaShuBookSourceRepo())->addIfNotExist($bookSourceEntity);
    }

    /**
     * 获取书籍概述
     * @param simple_html_dom $dom
     * @return string
     */
    private function getBookSummary(simple_html_dom $dom)
    {
        $aboutBook = $dom->find('div#aboutbook');
        if (count($aboutBook) > 0) {
            $innerText = $aboutBook[0]->innertext();
            $regex = "/<a.*?<\/a>/i";
            $innerText = preg_replace($regex, "", $innerText);
            $regex = "/<h3.*?<\/h3>/i";
            $innerText = preg_replace($regex, "", $innerText);
            return htmlspecialchars($innerText, ENT_QUOTES, 'UTF-8');
        }

        return "";
    }

    private function getState($content)
    {
        $content = trim($content);
        switch ($content) {
            case "连载中":
                return XiaShuBookEntity::STATE_Serialize;
            case "完结":
                return XiaShuBookEntity::STATE_END;
            default:
                return XiaShuBookEntity::STATE_Unknown;
        }
    }

    /**
     * 获取作者id，同时保存到作者表
     * @param $penName
     * @param XiaShuAuthorRepo $authorRepo
     * @return int|mixed
     */
    private function getAuthorId($penName, XiaShuAuthorRepo $authorRepo)
    {
        $authorEntity = new XiaShuAuthorEntity();
        $authorEntity->setPenName($penName);
        $result = $authorRepo->addIfNotExist($authorEntity);
        if ($result->isSuccess()) {
            return $result->getData();
        }
        return 0;
    }

}