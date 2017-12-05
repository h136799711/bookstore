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

namespace app\component\oauth2\entity;


use by\infrastructure\base\BaseEntity;
use by\infrastructure\helper\Object2DataArrayHelper;
use by\infrastructure\interfaces\ObjectToArrayInterface;

class OauthClientsEntity extends BaseEntity implements ObjectToArrayInterface
{
    private $clientId;
    private $clientName;
    private $clientSecret;
    private $redirectUri;
    private $grantTypes;
    private $scope;
    private $userId;
    private $publicKey;
    private $apiAlg;

    public function toArray()
    {
        return Object2DataArrayHelper::getDataArrayFrom($this);
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
    public function setClientId($clientId): void
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
    public function setClientName($clientName): void
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
    public function setClientSecret($clientSecret): void
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
    public function setRedirectUri($redirectUri): void
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
    public function setGrantTypes($grantTypes): void
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
    public function setScope($scope): void
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
    public function setUserId($userId): void
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
    public function setPublicKey($publicKey): void
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
    public function setApiAlg($apiAlg): void
    {
        $this->apiAlg = $apiAlg;
    }
}