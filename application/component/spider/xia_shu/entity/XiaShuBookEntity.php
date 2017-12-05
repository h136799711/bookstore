<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-14 14:40
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\spider\xia_shu\entity;


use by\component\bookstore\v1\entity\BookEntity;
use by\component\spider\base\interfaces\ToArrayInterface;
use by\infrastructure\helper\Object2DataArrayHelper;

class XiaShuBookEntity extends BookEntity implements ToArrayInterface
{

    public function toArray()
    {
        return Object2DataArrayHelper::getDataArrayFrom($this);
    }


    public function __construct()
    {
        parent::__construct();
    }
}