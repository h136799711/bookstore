<?php
/**
 * Copyright (c) 2016.  hangzhou BOYE .Co.Ltd. All rights reserved
 */

/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-12-13
 * Time: 16:02
 */

namespace app\component\hook;

use by\infrastructure\helper\CallResultHelper;


/**
 * 业务层权限验证
 * Class DomainAuthHook
 * @package app\component\hook
 */
class DomainAuthHook
{

    /**
     * 调用者$caller_id对$resource 资源的 可操作性验证
     * @param $resource
     * @param $caller_id
     * @return \by\infrastructure\base\CallResult
     */
    public function auth($resource, $caller_id)
    {
        return CallResultHelper::success('success');
    }
}