<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 2017-12-02
 * Time: 17:46
 */

namespace by\component\bs\factory;


use by\component\spider\constants\BookSiteIntegerType;
use by\component\spider\xia_shu\logic\XiaShuBookPageContentLogic;

class PageContentLogicFactory
{
    public static function create($type)
    {
        switch ($type) {
            case BookSiteIntegerType::XIA_SHU_BOOK_SITE:
                return new XiaShuBookPageContentLogic();
            default:
                return new XiaShuBookPageContentLogic();
        }
    }
}