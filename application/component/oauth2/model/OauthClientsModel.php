<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-12
 * Time: 11:25
 */

namespace by\component\oauth2\model;

use by\component\tp5\model\BaseModel;

class OauthClientsModel extends BaseModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'oauth_clients';

    protected $connection = "oauth_db";
}