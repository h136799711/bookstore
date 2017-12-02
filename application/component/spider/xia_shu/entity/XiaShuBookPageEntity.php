<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-17 11:26
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\spider\xia_shu\entity;


use app\component\spider\base\interfaces\ToArrayInterface;
use app\component\spider\constants\BookSiteIntegerType;
use by\component\bookstore\v1\entity\BookPageEntity;
use by\infrastructure\helper\Object2DataArrayHelper;

class XiaShuBookPageEntity extends BookPageEntity implements ToArrayInterface
{
    public function toArray()
    {
        $data = ['update_time', 'create_time', 'page_content', 'page_title', 'source_type', 'book_id', 'page_no'];
        return Object2DataArrayHelper::getDataArrayFrom($this, $data);
    }

    // construct
    public function __construct()
    {
        parent::__construct();
        $this->setSourceType(BookSiteIntegerType::XIA_SHU_BOOK_SITE);
    }

    public function getSourceDesc()
    {
        return BookSiteIntegerType::getDesc($this->getSourceType());
    }
}