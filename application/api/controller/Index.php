<?php

namespace by\api\index\controller;

use app\api\config\ApiConfigHelper;
use by\api\constants\ErrorCode;
use by\component\base\exception\BusinessException;
use by\component\helper\ExceptionHelper;
use by\component\hook\LoginAuthHook;
use by\infrastructure\base\CallResult;
use think\Exception;

class Index extends Base
{

    private $appVersion;  //当前软件的版本
    private $appType;     //当前软件的类型 ，ios，android，pc ,by_test


    /**
     * 接口入口
     */
    public function index()
    {
        try {

            //1. 公共参数初始化
            $this->_initParameter();
            //3. 配置信息读取 并缓存8小时
            ApiConfigHelper::initApiConfig();
            //已登录会话ID
            $login_s_id = $this->getLoginSId();
            $module = $this->getModuleName();
            $serviceType = $this->allData->getServiceType();

            $api_type = preg_replace("/_/", "/", substr(trim($serviceType), 3), 1);
            $api_type = preg_split("/\//", $api_type);

            if (count($api_type) < 2) {
                $this->apiReturnErr("type参数不正确!", ErrorCode::Invalid_Parameter);
            }

            $action_name = $api_type[1];
            $controller_name = $api_type[0];
            $auth_value = $module . '_' . $controller_name . '_' . $action_name;

            if ($module == 'default') {
                $module = "domain";
            } else {
                $module = $module . "_domain";
            }

            $cls_name = "by\\$module\\" . $controller_name . 'Domain';
            if (!class_exists($cls_name, true)) {
                $this->apiReturnErr(lang('err_404'), ErrorCode::Not_Found_Resource);
            }

            $uid = $this->getUid();

            //1. 登录判定
            $LoginAuthHook = new LoginAuthHook();
            $result = $LoginAuthHook->check($uid, $login_s_id, $auth_value, "", ApiConfigHelper::getConfig('login_session_expire_time'));

            if (!$result['status']) {
                $this->apiReturnErr($result['info'], ErrorCode::Api_Need_Login);
            }

            //2.  TODO 授权判定
//            $domainAuthHook = new DomainAuthHook();
//            $result = $domainAuthHook->auth($auth_value, $uid);
//            if (!$result['status']) {
//                $this->apiReturnErr($result['info'], ErrorCode::Api_No_Auth);
//            }

            //3. 初始化业务类
            $class = new  $cls_name($this->transport, $this->allData);
            if (!method_exists($class, $action_name)) {
                $this->apiReturnErr('api-' . lang('err_404'), ErrorCode::Not_Found_Resource);
            }

            //4. 调用方法
            $callResult = $class->$action_name();

            if ($callResult instanceof CallResult) {
                if ($callResult->isSuccess()) {
                    $this->apiReturnSuc($callResult);
                } else {
                    $this->apiReturnErr($callResult);
                }
            }

            throw new BusinessException($class . $action_name . ' 必须返回CallResult对象');
        } catch (BusinessException $businessException) {
            $this->apiReturnErr($businessException->getMessage(), $businessException->getCode());
        } catch (Exception $ex) {
            $this->apiReturnErr(ExceptionHelper::getErrorString($ex), ErrorCode::Business_Error);
        }
    }

    /**
     *
    /**
     * 初始化公共参数
     * time:必须 | 请求时间戳
     * sign:必须 | 签名
     * data:必须 | 数据
     * type:必须 | 调用接口
     * notify_id:必须 | 通知id
     * api_ver:必须   |
     *
     * app_version: 否 | APP版本
     * app_type: 否    | ios or android
     * lang: 否 | 默认 zh-cn ，语言参数
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function _initParameter()
    {

        $this->appVersion = array_key_exists('by_app_version', $this->decryptData) ? $this->decryptData['by_app_version'] : -1;
        $this->appType = array_key_exists('by_app_type', $this->decryptData) ? $this->decryptData['by_app_type'] : -1;

        //检查语言是否支持
        $lang_support = ApiConfigHelper::getConfig('lang_support');
        $is_support = false;
        if (is_array($lang_support)) {
            $is_support = in_array($this->lang, $lang_support);
        }

        if (!$is_support) {
            //对于不支持的语言都使用zh-cn
            $this->lang = "zh-cn";
        }
    }

    /**
     * 获取登录会话id
     * @return string
     */
    private function getLoginSId()
    {
        $login_s_id = isset($this->decryptData['s_id']) ? ($this->decryptData['s_id']) : "";
        return $login_s_id;
    }

    /**
     * 获取接口模块名称
     * 1. 用于未来对接口进行业务拆分、按使用场景进行拆分  比如针对第三方的接口、针对PC的接口
     * @return string
     */
    private function getModuleName()
    {
        $module_name = isset($this->decryptData['by_m']) ? ($this->decryptData['by_m']) : "";
        if (empty($module_name)) $module_name = "default";
        return $module_name;
    }

    /**
     *  获取用户UID
     *
     */
    private function getUid()
    {
        $uid = array_key_exists('uid', $this->decryptData) ? $this->decryptData['uid'] : 0;
        return intval($uid);
    }
}
