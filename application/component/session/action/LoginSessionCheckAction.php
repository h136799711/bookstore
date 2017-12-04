<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2017-02-17
 * Time: 11:53
 */

namespace app\component\session\action;


use app\component\base\action\BaseAction;
use app\component\session\entity\LoginSessionEntity;
use app\component\session\logic\LoginSessionLogic;

/**
 * Class LoginSessionCheckAction
 * 会话检测
 * 1. 是否登录
 * 2. 是否过期
 * 3. 刷新过期时间
 * @package app\component\session\action
 */
class LoginSessionCheckAction extends BaseAction
{
    const MIN_SESSION_EXPIRE_TIME = 300;

    public function check($uid, $log_session_id, $device_type, $session_expire_time = 1296000)
    {
        $log_session_id = empty($log_session_id) ? "-1" : $log_session_id;
        $logic = new LoginSessionLogic();
        $map = ['uid' => $uid, 'log_session_id' => $log_session_id];
        $result = $logic->getInfo($map);
        $now = time();
        if ($result instanceof LoginSessionEntity) {
            $id = $result->getId();
            $expire_time = intval($result->getExpireTime());

            if ($now > $expire_time) {
                (new LoginSessionLogoutAction())->logout($uid, $log_session_id);
                return ['status' => false, 'info' => lang("err_re_login")];
            }

            if ($log_session_id != "-1" && $log_session_id != $info['log_session_id']) {
                (new LoginSessionLogoutAction())->logout($uid, $log_session_id);
                return ['status' => false, 'info' => lang("err_login_" . $info['login_device_type'], ['time' => date("Y-m-d H:i", $info['update_time'])])];
            }

            //至少5分钟
            if (empty($session_expire_time) || $session_expire_time <= 10) {
                $session_expire_time = self::MIN_SESSION_EXPIRE_TIME;
            }

            $session_expire_time = intval($session_expire_time);

            //检测成功,更新过期时间
            $result = $logic->saveByID($id, ['expire_time' => $now + $session_expire_time]);

            return $result;
        }

        (new LoginSessionLogoutAction())->logout($uid, $log_session_id);
        return ['status' => false, 'info' => lang("err_re_login")];
    }
}