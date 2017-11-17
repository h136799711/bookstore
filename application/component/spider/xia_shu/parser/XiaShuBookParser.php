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


use app\component\spider\xia_shu\entity\XiaShuAuthorEntity;
use app\component\spider\xia_shu\entity\XiaShuBookEntity;
use app\component\spider\xia_shu\repo\XiaShuAuthorRepo;
use Sunra\PhpSimple\HtmlDomParser;

class XiaShuBookParser
{
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
    }

    // construct
    public function __construct($url)
    {
        $this->url = $url;
    }

    public function parse()
    {
        $this->url = 'https://www.xiashu.cc/21400';
        $dom = HtmlDomParser::file_get_html($this->url);
        $entity = new XiaShuBookEntity();
//        <meta property="og:url" content="https://www.xiashu.cc/21400/"/>
//<meta property="og:title" content="我的绝品女总裁"/>
//<meta property="og:image" content="https://img.xiashu.cc/cover/21/21400.jpg"/>
//<meta property="og:novel:category" content="都市生活"/>
//<meta property="og:novel:author" content="晴天小熊"/>
//<meta property="og:novel:book_name" content="我的绝品女总裁"/>
//<meta property="og:novel:read_url" content="https://www.xiashu.cc/21400/read_1.html"/>
//<meta property="og:novel:status" content=" 连载中"/>
//<meta property="og:novel:update_time" content="2017-08-05 13:44:15"/>
        $ret = $dom->find('meta');
        $authorRepo = new XiaShuAuthorRepo();

        $updateTimeProperty = "og:novel:update_time";
        $stateProperty = "og:novel:status";
        $bookNameProperty = "og:novel:book_name";
        $authorProperty = "og:novel:author";
        $thumbnailProperty = "og:image";
        $categoryProperty = "og:novel:category";
        foreach ($ret as $domNode) {
            $property = $domNode->getAttribute('property');
            $content = $domNode->getAttribute('content');
            if (!$property) {
                continue;
            }

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
                $entity->setUpdateTime($content);
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

//        var_dump(Object2DataArrayHelper::getDataArrayFrom($entity));
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
                break;
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