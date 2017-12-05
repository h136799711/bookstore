<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-05 16:32
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\encrypt\md5v3;


use by\infrastructure\base\BaseEntity;

class DataStructEntity extends BaseEntity
{

    private $projectId;
    private $notifyId;
    private $clientSecret;
    private $clientId;
    private $data;
    private $time;
    private $type;
    private $sign;
    private $apiVer;

    public function __construct()
    {
        parent::__construct();
        $this->setApiVer(100);
        $this->setClientId('');
        $this->setClientSecret('');
        $this->setData('');
        $this->setTime('');
        $this->setType('');
        $this->setSign('');
    }

    public function toArray()
    {
        $data = parent::toArray();
        unset($data['create_time']);
        unset($data['update_time']);
        return $data;
    }

    /**
     * 获取项目id
     * @return integer
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * 设置项目id
     * @param integer $projectId
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * 获取应用密钥
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * 设置应用密钥
     * @param mixed $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * 获取应用id
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * 设置应用id
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * 获取携带数据
   * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 设置携带数据
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * 获取id
     * @return string
     */
    public function getNotifyId()
    {
        return $this->notifyId;
    }

    /**
     * 设置id
     * @param string $notifyId
     */
    public function setNotifyId($notifyId)
    {
        $this->notifyId = $notifyId;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * @param mixed $sign
     */
    public function setSign($sign)
    {
        $this->sign = $sign;
    }

    /**
     * @return mixed
     */
    public function getApiVer()
    {
        return $this->apiVer;
    }

    /**
     * @param mixed $apiVer
     */
    public function setApiVer($apiVer)
    {
        $this->apiVer = $apiVer;
    }
}