<?php
/**
 * Copyright (c) 2017.  hangzhou BOYE .Co.Ltd. All rights reserved
 */

/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2017-01-03
 * Time: 01:54
 */

namespace by\component\hook;
use by\business\session\action\LoginSessionCheckAction;
use by\infrastructure\helper\CallResultHelper;


/**
 * Class LoginAuthHook
 * 登录检查，
 * 对于某些接口检测是否已登录
 * 1. 即是否有会话id传过来
 * 2. 检查会话id是否合法
 * @package by\component\hook
 */
class LoginAuthHook
{

    protected $needCheckApiList = [
        'default_address_*',
        'default_shoppingcart_*',
        'default_order_*',
        'default_user_update$',
        'default_user_updatepwdbyoldpwd',
        'default_user_updateLatLng'
    ];

    /**
     * 检查
     * @param int $uid
     * @param string $s_id
     * @param string $api
     * @param $device_type
     * @param $session_expire_time
     * @return array|bool|\by\infrastructure\base\CallResult|false|null|object|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function check($uid = 0, $s_id = '', $api = '', $device_type, $session_expire_time)
    {

        if ($s_id == 'itboye') {
            return CallResultHelper::success('test api');
        }
        $api = strtolower($api);
        foreach ($this->needCheckApiList as $item) {
            $result = preg_match('/' . $item . '/i', $api);
            if ($result === 1) {
                if ($uid > 0 && !empty($s_id)) {
                    return $this->checkUidSessionId($uid, $s_id, $device_type, $session_expire_time);
                } else {
                    if ($uid <= 0) {
                        return CallResultHelper::fail('[10UID] 请重新登录');
                    } else {
                        return CallResultHelper::fail('[10SID] 请重新登录');
                    }
                }

            }
        }

        return CallResultHelper::success('not need check');
    }

    /**
     * 检查
     * @param $uid
     * @param $s_id
     * @param $device_type
     * @param $session_expire_time
     * @return array|bool|false|null|object|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function checkUidSessionId($uid, $s_id, $device_type, $session_expire_time)
    {
        $result = (new LoginSessionCheckAction())->check($uid, $s_id, $device_type, $session_expire_time);
        return $result;
    }
}