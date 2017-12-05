<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-15
 * Time: 9:45
 */

namespace by\component\encrypt\algorithm;

/**
 * Class EmptyAlg
 * 原始数据
 * @author hebidu <email:346551990@qq.com>
 * @package by\component\encrypt\algorithm
 */
class EmptyAlg extends IAlgorithm
{

    function decryptTransmissionData($transmissionData, $desKey)
    {
        return $transmissionData;
    }

    function encryptTransmissionData($param, $desKey)
    {
        return $param;
    }

    function verify_sign($sign, AlgParams $algParams)
    {
        return true;
    }

    function sign(AlgParams $param)
    {
        return '';
    }

    function decryptData($encryptData)
    {
        return $encryptData;
    }

    function encryptData($data)
    {
        return $data;
    }

}