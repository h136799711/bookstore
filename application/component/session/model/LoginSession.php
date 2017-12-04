<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-01
 * Time: 10:46
 */

namespace app\component\session\model;


use app\component\tp5\model\BaseModel;

class LoginSession extends BaseModel
{
    protected $table = "common_login_session";

    protected $auto = ['update_time'];

    protected function setUpdateTimeAttr()
    {
        return time();
    }
}