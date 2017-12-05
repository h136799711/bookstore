<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-03
 * Time: 17:24
 */

namespace by\domain;

use by\component\tp5\logic\ConfigLogic;
use by\infrastructure\helper\CallResultHelper;

/**
 * index 首页
 * Class IndexDomain
 * @author hebidu <email:346551990@qq.com>
 * @package by\src\domain
 */
class TestDomain extends BaseDomain
{

    /**
     * @return \by\infrastructure\base\CallResult
     * @throws \by\component\base\exception\BusinessException
     */
    public function index(){
        $username = $this->_post('username', '');
        return CallResultHelper::success($username);
    }

    /**
     * @return \by\infrastructure\base\CallResult
     * @throws \by\component\base\exception\BusinessException
     */
    public function array_data(){
        $username = $this->_post('username', '');
        $password = $this->_post('password', '');
        $data = ['username'=>$username, 'password'=>$password ,'a'=>1,'b'=>2,'c'=>3];
        return CallResultHelper::success($data);
    }

    public function fail(){
        return CallResultHelper::fail('fail test', ['a'=>1,'b'=>2,'c'=>3]);
    }

    /**
     * @return \by\infrastructure\base\CallResult
     * @throws \by\component\base\exception\BusinessException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function db_array(){
        $name = $this->_post('name', '');
        $map['name'] = ['like', '%'.$name.'%'];
        $result = (new ConfigLogic())->query($map, $this->getPageParams());
        return CallResultHelper::success($result);
    }

}