<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-15
 * Time: 9:41
 */

namespace by\component\encrypt\factory;

use by\component\encrypt\constants\TransportEnum;
use by\component\encrypt\interfaces\TransportInterface;
use by\component\encrypt\md5v3\Md5V3Transport;

/**
 * 传输算法工厂
 * Class TransportFactory
 * @author hebidu <email:346551990@qq.com>
 * @package by\component\encrypt\algorithm
 */
class TransportFactory
{
    /**
     * 获取传输算法
     * @param string $enum
     * @param $data
     * @return TransportInterface | bool
     */
    public static function getAlg($enum, $data)
    {

        switch ($enum) {
            case TransportEnum::MD5_V3:
                return new Md5V3Transport($data);
            default:
                return false;
        }
    }
}