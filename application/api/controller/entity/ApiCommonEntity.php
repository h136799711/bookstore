<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-05 17:09
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\api\controller\entity;


use by\infrastructure\base\BaseEntity;

/**
 * Class ApiCommonEntity
 * 接口通用参数 - 不管采用何种传输算法
 * @package app\api\controller\dto
 */
class ApiCommonEntity extends BaseEntity
{
    /**
     * 请求的语言
     * @var string
     */
    private $lang;
    /**
     * 请求服务
     * @var string
     */
    private $serviceType;

    /**
     * 发起请求应用的类型
     * @var string
     */
    private $appType;

    /**
     * 发起请求应用版本号
     * @var string
     */
    private $appVersion;

    /**
     * 传输给业务的数据
     * @var array
     */
    private $data;

    /**
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @return string
     */
    public function getServiceType()
    {
        return $this->serviceType;
    }

    /**
     * @param string $serviceType
     */
    public function setServiceType($serviceType)
    {
        $this->serviceType = $serviceType;
    }

    /**
     * @return string
     */
    public function getAppType()
    {
        return $this->appType;
    }

    /**
     * @param string $appType
     */
    public function setAppType($appType)
    {
        $this->appType = $appType;
    }

    /**
     * @return string
     */
    public function getAppVersion()
    {
        return $this->appVersion;
    }

    /**
     * @param string $appVersion
     */
    public function setAppVersion($appVersion)
    {
        $this->appVersion = $appVersion;
    }

}