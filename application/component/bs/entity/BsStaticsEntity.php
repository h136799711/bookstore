<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-01 17:06
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\bs\entity;


use by\infrastructure\base\BaseEntity;
use by\infrastructure\helper\Object2DataArrayHelper;
use by\infrastructure\interfaces\ObjectToArrayInterface;

class BsStaticsEntity extends BaseEntity implements ObjectToArrayInterface
{
    public function toArray()
    {
        return Object2DataArrayHelper::getDataArrayFrom($this);
    }


    private $stKey;
    private $stValue;

    /**
     * @return mixed
     */
    public function getStKey()
    {
        return $this->stKey;
    }

    /**
     * @param mixed $stKey
     */
    public function setStKey($stKey)
    {
        $this->stKey = $stKey;
    }

    /**
     * @return mixed
     */
    public function getStValue()
    {
        return $this->stValue;
    }

    /**
     * @param mixed $stValue
     */
    public function setStValue($stValue)
    {
        $this->stValue = $stValue;
    }

}