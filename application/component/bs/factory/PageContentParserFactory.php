<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 2017-12-03
 * Time: 20:17
 */

namespace by\component\bs\factory;


use by\component\spider\constants\BookSiteIntegerType;
use by\component\spider\xia_shu\parser\XiaShuPageContentParser;

class PageContentParserFactory
{

    public static function getBookPageReadUrl($type, $sourceBookId, $pageNo)
    {
        switch ($type) {
            case BookSiteIntegerType::XIA_SHU_BOOK_SITE:
                return "https://www.xiashu.cc/" . $sourceBookId . '/read_' . $pageNo . '.html';
            default:
                return "https://www.xiashu.cc/" . $sourceBookId . '/read_' . $pageNo . '.html';
        }
    }

    public static function create($type)
    {
        switch ($type) {
            case BookSiteIntegerType::XIA_SHU_BOOK_SITE:
                return new XiaShuPageContentParser();
            default:
                return new XiaShuPageContentParser();
        }
    }
}