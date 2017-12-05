<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-04 11:54
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\user\interfaces;


interface AccountInterface
{


    /**
     * 注销登录会话
     * @param integer $uid 用戶id
     * @param string $auto_login_code  注銷授權碼
     * @return mixed
     */
    function logout($uid, $auto_login_code);

    /**
     * 通过手机号登录
     * @param integer $app_id   应用id
     * @param string $mobile      手机号
     * @param string $code       string 验证码
     * @param string $country    string 国家代码
     * @return mixed
     */
    function loginByCode($app_id, $mobile, $code, $country);

    /**
     * @param $username
     * @param $password
     * @return mixed
     */
    function login($username, $password);

    /**
     * 注册
     * @param $entity
     * @return mixed
     */
    function register($entity);

    /**
     * 更新用户信息
     * @param $uid
     * @param $entity
     * @return mixed
     */
    function update($uid, $entity);

    /**
     * 更新密码
     * @param $map
     * @param $newPwd
     * @return mixed
     */
    function updatePwd($map, $newPwd);

    /**
     * 删除
     * @param $entity
     * @return mixed
     */
    function delete($entity);

    /**
     * 验证用户会话是否有效
     * @param $uid
     * @param $log_session_id
     * @param $device_type
     * @param $session_expire_time
     * @return mixed
     */
    function autoLogin($uid, $log_session_id, $device_type, $session_expire_time);


}