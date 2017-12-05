<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-05 11:03
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\oauth2\entity;


use by\infrastructure\base\BaseEntity;

class OauthClientsEntity extends BaseEntity
{
    /**
     * 项目ID 一个项目包含多个应用
     * @var string
     */
    private $projectId;

    /**
     * 应用id
     * @var string
     */
    private $clientId;

    /**
     * 应用名称
     * @var string
     */
    private $clientName;

    /**
     * 应用名称
     * @var string
     */
    private $clientSecret;

    /**
     * oauth跳转客户端uri
     * @var string
     */
    private $redirectUri;

    /**
     * 授权类型
     * @var string
     */
    private $grantTypes;

    /**
     * 范围
     * @var string
     */
    private $scope;

    /**
     * 用户id
     * @var string
     */
    private $userId;

    /**
     *
     * @var string
     */
    private $publicKey;

    /**
     * 通信算法
     * @var string
     */
    private $apiAlg;

    /**
     * @return string
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * @param string $projectId
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
    }


    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * @param mixed $clientName
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;
    }

    /**
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param mixed $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return mixed
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @param mixed $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * @return mixed
     */
    public function getGrantTypes()
    {
        return $this->grantTypes;
    }

    /**
     * @param mixed $grantTypes
     */
    public function setGrantTypes($grantTypes)
    {
        $this->grantTypes = $grantTypes;
    }

    /**
     * @return mixed
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param mixed $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @param mixed $publicKey
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @return mixed
     */
    public function getApiAlg()
    {
        return $this->apiAlg;
    }

    /**
     * @param mixed $apiAlg
     */
    public function setApiAlg($apiAlg)
    {
        $this->apiAlg = $apiAlg;
    }
}