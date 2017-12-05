<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 2017-12-02
 * Time: 17:02
 */

namespace by\component\bs\factory;


use by\component\bs\model\BsBookPageContentModel;
use by\component\spider\constants\BookSiteIntegerType;

class PageContentModelFactory
{
    public static function create($type)
    {
        switch ($type) {
            case BookSiteIntegerType::XIA_SHU_BOOK_SITE:
                return new BsBookPageContentModel([], 'xiashu_book_page_content');
            default:
                return null;
        }
    }
}