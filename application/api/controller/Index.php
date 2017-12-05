<?php

namespace by\api\controller;

use by\api\config\ApiConfigHelper;
use by\api\constants\ErrorCode;
use by\component\base\exception\BusinessException;
use by\component\helper\ExceptionHelper;
use by\component\hook\LoginAuthHook;
use by\infrastructure\base\CallResult;
use think\Exception;

class Index extends Base
{

    /**
     * 接口入口
     */
    public function index()
    {
        try {

            //1. 配置信息读取 并缓存8小时
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

            if (!$result->isSuccess()) {
                $this->apiReturnErr($result->getMsg(), ErrorCode::Api_Need_Login);
            }

            //2.  TODO 授权判定
//            $domainAuthHook = new DomainAuthHook();
//            $result = $domainAuthHook->auth($auth_value, $uid);
//            if (!$result['status']) {
//                $this->apiReturnErr($result['info'], ErrorCode::Api_No_Auth);
//            }

            //3. 初始化业务类
            $class = new $cls_name($this->transport, $this->allData);

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
     * 获取登录会话id
     * @return string
     */
    private function getLoginSId()
    {
        $data = $this->allData->getData();
        $login_s_id = isset($data['s_id']) ? ($data['s_id']) : "";
        return $login_s_id;
    }

    /**
     * 获取接口模块名称
     * 1. 用于未来对接口进行业务拆分、按使用场景进行拆分  比如针对第三方的接口、针对PC的接口
     * @return string
     */
    private function getModuleName()
    {
        $data = $this->allData->getData();
        $module_name = isset($data['by_m']) ? ($data['by_m']) : "";
        if (empty($module_name)) $module_name = "default";
        return $module_name;
    }

    /**
     *  获取用户UID
     *
     */
    private function getUid()
    {
        $data = $this->allData->getData();
        $uid = array_key_exists('uid', $data) ? $data['uid'] : 0;
        return intval($uid);
    }
}
