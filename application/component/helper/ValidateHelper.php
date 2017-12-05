<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-17
 * Time: 11:09
 */

namespace by\component\helper;


class ValidateHelper
{

    /**
     * 判断 是 0 或 1 返回 true 其它情况 false
     * @param $digit
     * @return bool
     */
    public static function isZeroOrOne($digit)
    {
        if (strlen($digit) > 0) {
            $digit = intval($digit);
            return $digit === 0 || $digit === 1;
        }
        return false;
    }

    /**
     * 判断是否为数字
     * @param $str
     * @return bool
     */
    public static function isNumberStr($str)
    {
        return !is_resource($str) && !is_array($str) && !is_object($str) && (is_int($str) || is_numeric($str) || preg_match('/^\d*$/', $str));
    }

    /**
     * 判断是否为合法的密码
     * @author hebidu <email:346551990@qq.com>
     * @param $password
     * @return array
     */
    public static function legalPwd($password)
    {
        if (strlen($password) < 6 || strlen($password) > 64) {
            return array('status' => false, 'info' => lang('tip_password_length'));
        }
        if (!preg_match("/^[0-9a-zA-Z\\\\,.?><;:!@#$%^&*()_+-=\[\]\{\}\|]{6,64}$/", $password)) {
            return ['status' => false, 'info' => lang('tip_password')];
        }
        return ['status' => true, 'info' => lang('success')];
    }

    /**
     * 判定是否为一个合法的11位字符串
     * @author hebidu <email:346551990@qq.com>
     * @param $str
     * @return bool
     */
    public static function isMobile($str)
    {
        if (is_string($str) && preg_match("/^1\d{10}$/", $str)) {
            return true;
        }
        if (is_string($str) && strlen($str) > 1) {
            $ch = substr($str, 0, 1);
            if (intval($ch) > 0) {
                // 第一位是数字就算是手机号登录
                return true;
            }
        }

        return false;
    }

    /**
     * 验证是否合法的结果,含数组
     * @author hebidu <email:346551990@qq.com>
     * @param $result array
     * @return bool
     */
    public static function legalArrayResult($result)
    {

        if (isset($result['info']) && isset($result['status']) && $result['status'] && is_array($result['info']) && count($result['info']) > 0) {
            return true;
        }

        return false;
    }

    /**
     * 判断是否为邮箱
     * @param $email
     * @return bool
     */
    public static function isEmail($email)
    {
        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        if (is_string($email) && preg_match($pattern, $email)) {
            return true;
        }

        return false;
    }
}