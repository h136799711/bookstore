<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-04 10:47
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\session\entity;


use by\infrastructure\base\BaseEntity;

/**
 * Class LoginSessionEntity
 * 登录会话
 * @package app\component\session\entity
 */
class LoginSessionEntity extends BaseEntity
{
    private $uid;
    private $loginSessionId;
    private $loginInfo;
    private $loginDeviceType;
    private $expireTime;

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getLoginSessionId()
    {
        return $this->loginSessionId;
    }

    /**
     * @param mixed $loginSessionId
     */
    public function setLoginSessionId($loginSessionId)
    {
        $this->loginSessionId = $loginSessionId;
    }

    /**
     * @return mixed
     */
    public function getLoginInfo()
    {
        return $this->loginInfo;
    }

    /**
     * @param mixed $loginInfo
     */
    public function setLoginInfo($loginInfo)
    {
        $this->loginInfo = $loginInfo;
    }

    /**
     * @return mixed
     */
    public function getLoginDeviceType()
    {
        return $this->loginDeviceType;
    }

    /**
     * @param mixed $loginDeviceType
     */
    public function setLoginDeviceType($loginDeviceType)
    {
        $this->loginDeviceType = $loginDeviceType;
    }

    /**
     * @return mixed
     */
    public function getExpireTime()
    {
        return $this->expireTime;
    }

    /**
     * @param mixed $expireTime
     */
    public function setExpireTime($expireTime)
    {
        $this->expireTime = $expireTime;
    }
}