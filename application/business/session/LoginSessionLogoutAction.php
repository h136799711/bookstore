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

class LoginSessionLogoutAction extends BaseAction
{
    public function logout($uid, $s_id)
    {
        $result = (new LoginSessionLogic())->delete(['uid' => $uid, 'log_session_id' => $s_id]);
        return $result;
    }
}