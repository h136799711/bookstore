<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-15
 * Time: 9:45
 */

namespace app\component\encrypt\algorithm;


use app\component\encrypt\des\Des;
use app\component\encrypt\exception\CryptException;

/**
 * Class Md5V1Alg
 * 目前使用于 java
 * @author hebidu <email:346551990@qq.com>
 * @package app\component\encrypt\algorithm
 */
class Md5V1Alg extends IAlgorithm
{

    function decryptTransmissionData($param, $desKey)
    {
        $data = Des::decode(base64_decode($param), $desKey);

        return ($data);
    }

    function encryptTransmissionData($param, $desKey)
    {
        throw  new CryptException("Unused");
    }

    function verify_sign($sign, AlgParams $algParams)
    {
        $tmp_sign = Md5V1Alg::sign($algParams);
        if ($sign == $tmp_sign) {
            return true;
        }

        return false;
    }

    function sign(AlgParams $param)
    {
        $param->isValid();
        return $param->getSign();
    }

    function decryptData($encryptData)
    {
        return json_decode(base64_decode(base64_decode($encryptData)), JSON_OBJECT_AS_ARRAY);
    }

    function encryptData($data)
    {
        $str = json_encode($data, 0, 512);
        return base64_encode(base64_encode($str));
    }

}