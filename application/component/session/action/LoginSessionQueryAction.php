<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2017-02-17
 * Time: 11:10
 */

namespace app\component\session\action;


use app\component\base\action\BaseAction;
use app\component\session\logic\LoginSessionLogic;

class LoginSessionQueryAction extends BaseAction
{
    public function query($uid)
    {
        $result = (new LoginSessionLogic())->query(['uid' => $uid]);
        return $result;
    }
}