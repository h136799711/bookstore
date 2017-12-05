<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2017-02-17
 * Time: 11:10
 */

namespace by\business\session\action;


use by\component\base\action\BaseAction;
use by\component\session\logic\LoginSessionLogic;
use by\component\string_extend\helper\StringHelper;
use by\component\user\interfaces\MemberConfigInterface;
use by\infrastructure\helper\CallResultHelper;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class LoginSessionLoginAction extends BaseAction
{
    /**
     * @var MemberConfigInterface
     */
    private $memberCfgLogic;

    public function __construct(MemberConfigInterface $memberConfig)
    {
        $this->memberCfgLogic = $memberConfig;
    }

    /**
     * @param $uid
     * @param $device_token
     * @param $device_type
     * @param $login_info
     * @param int $session_expire_time
     * @return array|bool|\by\infrastructure\base\CallResult|false|int|null|object|\PDOStatement|string|\think\Model
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws \think\Exception
     */
    public function login($uid, $device_token, $device_type, $login_info, $session_expire_time = 1296000)
    {

        $logic = new LoginSessionLogic();
        $map = ['uid' => $uid];
        $result = $this->memberCfgLogic->getInfo($map);

        if (empty($result)) {
            return CallResultHelper::fail(lang('err_uid_not_exists', ['uid'=>$uid]));
        }

        $userInfo = $result['info'];
        $login_device_cnt = $userInfo['login_device_cnt'];

        $result = $logic->count($map);
        $cnt = $result['info'];
        if ($cnt >= $login_device_cnt) {
            //相等时，需要踢掉一个登录信息，踢掉最早的
            $result = $logic->getInfo(['uid' => $uid], "expire_time asc");

            $info = $result['info'];
            if (array_key_exists("log_session_id", $info)) {
                $s_id = $info['log_session_id'];
                (new LoginSessionLogoutAction())->logout($uid, $s_id);
            }
        }

        $now = time();
        $r = rand(100000, 999999);
        $log_session_id = md5($device_token . $r . time()) . StringHelper::intTo36Hex($uid);
        //至少5分钟
        if (empty($session_expire_time) || $session_expire_time <= 10) {
            $session_expire_time = 300;
        }
        $session_expire_time = intval($session_expire_time);
        $entity = [
            'log_session_id' => $log_session_id,
            'uid' => $uid,
            'update_time' => $now,
            'login_info' => json_encode($login_info),
            'create_time' => $now,
            'expire_time' => $now + $session_expire_time,
            'login_device_type' => $device_type,
        ];
        $result = $logic->add($entity);
        if ($result['status']) {
            $result['info'] = $log_session_id;
        }
        return $result;
    }
}