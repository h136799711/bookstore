<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-05 16:44
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\encrypt\md5v3;


use by\component\encrypt\exception\CryptException;
use by\component\encrypt\interfaces\TransportInterface;
use by\infrastructure\helper\Object2DataArrayHelper;

class Md5V3Transport implements TransportInterface
{

    private $entity;

    /**
     * Md5V3Transport constructor.
     * @param array $data
     * @throws CryptException
     */
    public function __construct($data = [])
    {
        if (!array_key_exists('itboye', $data)) {
            throw new CryptException(lang('lack_parameter', ['param'=>'itboye']));
        }

        $itboye = $data['itboye'];

        //读取传输过来的加密参数

//        $algFactory = new AlgFactory();
//        $this->algInstance = $algFactory->getAlg($alg);
//        $data = $this->algInstance->decryptTransmissionData($post,$this->alParams->getClientSecret());
//        $data = $this->filter_post($data);
//        $obj = json_decode($data,JSON_OBJECT_AS_ARRAY);
//
//        $data = empty($obj) ? [] : $obj;
//        $data = array_merge($data,empty($_GET) ? [] : $_GET);
//
//        $this->alParams->initFromArray($data);
//
//        $this->alParams->isValid();

        $this->entity = new DataStructEntity();
        Object2DataArrayHelper::setData($this->entity, $data);
    }




    function encrypt()
    {

    }

    function decrypt()
    {

    }

    /**
     * @return DataStructEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param DataStructEntity $entity
     */
    public function setEntity(DataStructEntity $entity): void
    {
        $this->entity = $entity;
    }

}