<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-12
 * Time: 11:47
 */

namespace by\component\oauth2\logic;


use by\component\oauth2\entity\OauthClientsEntity;
use by\component\tp5\logic\BaseLogic;
use by\infrastructure\helper\CallResultHelper;

class OauthClientsLogic extends BaseLogic
{

    /**
     * 获取密钥信息
     * @param $client_id
     * @return \by\infrastructure\base\CallResult
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getClientSecret($client_id)
    {
        $result = $this->getInfo(['client_id' => $client_id]);


        if ($result instanceof OauthClientsEntity) {
            return CallResultHelper::success($result->getClientSecret());
        }

        if (empty($info)) {
            return CallResultHelper::fail(lang('invalid_parameter', ['param' => 'client_id']));
        }

        return CallResultHelper::fail($info);
    }
}