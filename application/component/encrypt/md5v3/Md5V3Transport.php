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
use by\infrastructure\interfaces\ObjectToArrayInterface;

class Md5V3Transport implements TransportInterface
{

    private $clientSecret;

    /**
     * @var DataStructEntity
     */
    private $entity;

    private $data;

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

        if (!array_key_exists('client_secret', $data)) {
            throw new CryptException(lang('lack_parameter', ['param'=>'client_secret']));
        }

        $this->entity = new DataStructEntity();
        $this->clientSecret = $data['client_secret'];
        $this->data = $data;

    }

    private function decryptTransmissionData($data, $key)
    {
        $data = openssl_decrypt(base64_decode($data), "des-ecb", $key);

        return ($data);
    }

    protected function filter_post($post){
        $post = trim($post);
        for ($i=strlen($post)-1;$i>=0;$i--) {
            $ord = ord($post[$i]);
            if($ord > 31 && $ord != 127){
                $post = substr($post,0,$i+1);
                return $post;
            }
        }
        return $post;
    }

    private function toStringData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => &$value) {
                $data[$key] = $this->toStringData($value);
            }
        } elseif (!is_object($data) && !is_string($data)) {
            return strval($data);
        } elseif (is_object($data)) {
            if ($data instanceof ObjectToArrayInterface) {
                return $data->toArray();
            }
        }

        return $data;
    }


    /**
     * @param $data
     * @throws CryptException
     */
    protected static function checkNullData($data)
    {
        if (is_null($data)) {
            throw new CryptException(lang('err_return_is_not_null'));
        } elseif (is_array($data)) {
            foreach ($data as $value) {
                self::checkNullData($value);
            }
        } elseif (is_object($data) && method_exists($data, "toArray")) {
            foreach ($data->toArray() as $key => $value) {
                self::checkNullData($value);
            }
        }
    }

    function encryptData($data)
    {
        $str = json_encode($data, 0, 512);
        return base64_encode(base64_encode($str));
    }


    /**
     * @param $data
     * @return array
     * @throws CryptException
     */
    function encrypt($data)
    {
        //
        $data['data'] = $this->toStringData($data['data']);

        $this->checkNullData($data['data']);

        $type = ($data['code'] == 0) ? "T" : "F";
        $data = $this->encryptData($data);
        $entity = new DataStructEntity();
        $entity->setClientId($this->entity->getClientId());
        $returnData =  [
            'client_id' => $this->entity->getClientId(),
            'time' => strval(time()),
            'data' => $data,
            'notify_id' => $this->entity->getNotifyId(),
            'type' => $type,
            'api_ver' => $this->entity->getApiVer()
        ];
        $returnData['sign'] = SignHelper::sign($returnData['time'], $returnData['type'], $returnData['data'], $this->entity->getClientSecret(), $returnData['notify_id']);

        return $returnData;
    }

    /**
     * @param $data
     * @return array
     * @throws CryptException
     */
    function decrypt($data)
    {
        $itboye = $this->data['itboye'];
        unset($this->data['itboye']);
        $otherParams = $this->data;

        // 读取传输过来的加密参数
        $decodeData = $this->decryptTransmissionData($itboye, $this->clientSecret);
        $decodeData = $this->filter_post($decodeData);
        $obj = json_decode($decodeData,JSON_OBJECT_AS_ARRAY);
        $decodeData = empty($obj) ? [] : $obj;
        Object2DataArrayHelper::setData($this->entity, $decodeData);

        $this->entity->setClientSecret($this->clientSecret);
        $this->entity->isValid();

        $data = $this->decryptData($this->entity->getData());
        if (empty($data)) $data = [];
        $requestStructData = $this->entity->toArray();
        unset($requestStructData['data']);

        // 增加前缀，免得 data 数组中同样的参数覆盖掉了
        foreach ($requestStructData as $key=>$vo) {
            $otherParams['by_'.$key]  = $vo;
            unset($otherParams[$key]);
        }

        return array_merge($otherParams, $data);
    }

    function decryptData($encryptData)
    {
        return json_decode(base64_decode(base64_decode($encryptData)), JSON_OBJECT_AS_ARRAY);
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