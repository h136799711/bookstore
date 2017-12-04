<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-15
 * Time: 17:10
 */

namespace app\component\user\logic;

use app\component\tp5\logic\BaseLogic;
use app\component\user\entity\UcenterMemberEntity;
use app\component\user\helper\PasswordHelper;
use app\component\user\model\UcenterMember;
use by\infrastructure\helper\CallResultHelper;

class UcenterMemberLogic extends BaseLogic
{
    /**
     * 获取表某一字段數據
     * @param integer $uid 用戶id
     * @param string $field 字段名稱
     * @return string
     */
    public function getOneInfo($uid, $field = 'mobile')
    {
        $result = $this->getModel()->where(['id' => $uid])->field($field)->find();
        return $result ? $result->getData($field) : '';
    }

    /**
     * 验证uid 与psw 是否对应
     * @param integer $uid 用戶id
     * @param string $psw 明文密碼
     * @return bool
     */
    public function auth($uid, $psw)
    {
        $result = $this->getInfo(['id' => $uid]);
        if ($result instanceof UcenterMemberEntity) {
            $salt = $result->getSalt();
            return $result->getPassword() == PasswordHelper::md5Sha1String($psw, $salt);
        }

        return false;
    }

    /**
     * 檢查用戶名是否存在
     * 1. 用戶名
     * 2. 手機號
     * @param string $username 用戶名
     * @param string $countryNo 手機區號
     * @return \by\infrastructure\base\CallResult
     */
    public function checkUsername($username, $countryNo = '+86')
    {
        $result = $this->getInfo(['username' => $username]);
        if ($result instanceof UcenterMemberEntity) {
            return CallResultHelper::success($result);
        } else {
            $result = $this->getInfo(['mobile' => $username, 'country_no'=>$countryNo]);
            if ($result instanceof UcenterMemberEntity) {
                return CallResultHelper::success($result);
            }
        }
        return CallResultHelper::fail();
    }

}