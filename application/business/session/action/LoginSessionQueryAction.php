<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2017-02-17
 * Time: 11:10
 */

namespace by\business\session\action;


use by\component\base\action\BaseAction;
use by\component\paging\vo\PagingParams;
use by\component\session\logic\LoginSessionLogic;

class LoginSessionQueryAction extends BaseAction
{
    /**
     * @param $uid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function query($uid)
    {
        $result = (new LoginSessionLogic())->query(['uid' => $uid], new PagingParams());
        return $result;
    }
}