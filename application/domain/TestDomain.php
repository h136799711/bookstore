<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-03
 * Time: 17:24
 */

namespace by\domain;

use by\infrastructure\helper\CallResultHelper;

/**
 * index 首页
 * Class IndexDomain
 * @author hebidu <email:346551990@qq.com>
 * @package by\src\domain
 */
class TestDomain extends BaseDomain
{

    public function index(){
        return CallResultHelper::success('test');
    }

}