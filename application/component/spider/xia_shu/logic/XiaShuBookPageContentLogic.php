<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 2017-12-02
 * Time: 17:34
 */

namespace by\component\spider\xia_shu\logic;


use by\component\bs\logic\BsBookPageContentLogic;
use by\component\spider\constants\BookSiteIntegerType;

class XiaShuBookPageContentLogic extends BsBookPageContentLogic
{
    public function __construct()
    {
        parent::__construct(BookSiteIntegerType::XIA_SHU_BOOK_SITE);
    }
}