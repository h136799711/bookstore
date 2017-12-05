<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-20 11:59
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\tp5\model;

use by\infrastructure\helper\Object2DataArrayHelper;
use think\Model;

abstract class BaseModel extends Model
{
    /**
     * model 转换成 entity
     * 如果存在entity的话
     * @return null|object
     */
    public function toEntity()
    {
        $clsName = str_replace("Model", "", get_class($this));
        $clsName = str_replace("model", "entity", $clsName);
        $clsName .= 'Entity';
        $entity = null;
        if (class_exists($clsName)) {
            $entity = new $clsName;
            Object2DataArrayHelper::setData($entity, $this->getData());
        }
        return $entity;
    }
}