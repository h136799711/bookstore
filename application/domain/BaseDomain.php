<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-04 10:21
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\domain;

use by\api\constants\ErrorCode;
use by\api\controller\entity\ApiCommonEntity;
use by\component\base\exception\BusinessException;
use by\component\helper\ValidateHelper;
use by\component\paging\vo\PagingParams;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;


/**
 * 基础领域模型
 * Class BaseDomain
 * @package by\src\domain
 */
class BaseDomain
{

    protected $notify_id;
    protected $client_id;
    protected $client_secret;
    protected $time;

    //服务端允许的默认api版本
    protected $lang;
    protected $api_ver = 100;
    protected $allowType = ["json", "rss", "html"];
    protected $business_code = '';
    protected $cur_api_ver;  //服务端当前api_ver
    protected $origin_data;
    protected $request_api_ver;//请求的api_ver
    protected $algInstance;
    protected $apiVersionIsDeprecated;//接口已过期


    public function __construct($transport, ApiCommonEntity $apiCommonEntity)
    {
        $this->apiVersionIsDeprecated = false;
        $this->algInstance = $transport;
        $this->origin_data = $apiCommonEntity->getData();
        $this->client_secret = $apiCommonEntity->getClientSecret();
        $this->notify_id = $apiCommonEntity->getNotifyId();
        $this->time = $apiCommonEntity->getAppRequestTime();
        $this->client_id = $apiCommonEntity->getClientId();
        $this->request_api_ver = $apiCommonEntity->getAppVersion();
        $this->lang = $apiCommonEntity->getLang();
    }

    /**
     * @param $msg
     * @param int $code
     * @throws BusinessException
     */
    protected function apiReturnErr($msg, $code = -1)
    {
        throw new BusinessException($msg, $code);
    }

    /**
     * @param $param
     * @param $scope
     * @param string $default
     * @param string $emptyErrMsg
     * @throws BusinessException
     */
    public function getValueFromPost(&$param, $scope, $default = '', $emptyErrMsg = '')
    {
        if (NULL == $scope) {
            $scope = $GLOBALS;
        }

        $param = "tmp_exists_" . mt_rand();
        $name = array_search($param, $scope, TRUE);
        $param = self::_post($name, $default, $emptyErrMsg);
    }


    /**
     * @param $key
     * @param string $default
     * @param string $emptyErrMsg
     * @return int|mixed|string
     * @throws BusinessException
     */
    public function _post($key, $default = '', $emptyErrMsg = '')
    {

        $value = isset($this->origin_data[$key]) ? $this->origin_data[$key] : $default;

        if ($default == $value && !empty($emptyErrMsg)) {
            $emptyErrMsg = lang('lack_parameter', ['param' => $key]);
            $this->apiReturnErr($emptyErrMsg, ErrorCode::Lack_Parameter);
        }

        $value = $this->escapeEmoji($value);

        if ($default == $value && !empty($emptyErrMsg)) {
            $emptyErrMsg = lang('lack_parameter', ['param' => $key]);
            $this->apiReturnErr($emptyErrMsg, ErrorCode::Lack_Parameter);
        }

        return $value;
    }


    /**
     * @param $strText
     * @param bool $bool
     * @return int|mixed|string
     */
    protected function escapeEmoji($strText, $bool = false)
    {
        $preg = '/\\\ud([8-9a-f][0-9a-z]{2})/i';
        if ($bool == true) {
            $boolPregRes = (preg_match($preg, json_encode($strText, true)));
            return $boolPregRes;
        } else {
            $strPregRes = (preg_replace($preg, '', json_encode($strText, true)));
            $strRet = json_decode($strPregRes, JSON_OBJECT_AS_ARRAY);

            if (is_string($strRet) && strlen($strRet) == 0) {
                return "";
            }

            return $strRet;
        }
    }


    /**
     * @return PagingParams
     * @throws BusinessException
     */
    public function getPageParams()
    {
        $pagingParams = new PagingParams();
        $pagingParams->setPageSize($this->_post('page_size', 10));
        $pagingParams->setPageIndex($this->_post('page_index', 1));
        return $pagingParams;
    }


//    protected function cache($key, $data, $time = 300, $returnSuc = true) {
//        cache($key, json_encode($data), $time);
//        if ($returnSuc) $this->apiReturnSuc($data);
//    }


//    protected function checkCache($key, $page = false, $maxpage = false, $fresh = false)
//    {
//        $cache = cache($key);
//        if (false == $fresh && $page && $maxpage && $page <= $maxpage && $cache) {
//            $this->apiReturnSuc(json_decode($cache, true), true);
//        }
//    }

    /**
     * @param $data
     * @param bool $cache
     * @return CallResult
     */
    protected function apiReturnSuc($data, $cache = false)
    {
        if ($this->apiVersionIsDeprecated) {
            // TODO 缓存
            return CallResultHelper::success($data, 'success',ErrorCode::Api_Service_Is_Deprecated);
        } else {
            return CallResultHelper::success($data);
        }
    }

    /**
     * @param string $version
     * @param string $updateMsg
     * @throws BusinessException
     */
    protected function checkVersion($version = '', $updateMsg = '')
    {
        if (!$version) $version = $this->api_ver;
        if (is_array($version)) {
            $legal = false;

            foreach ($version as $item) {
                if (is_array($item)) {
                    $ver = $item[0];
                    if ($ver == intval($this->request_api_ver)) {
                        $this->apiVersionIsDeprecated = true;
                    }
                } elseif ($item == intval($this->request_api_ver)) {
                    $legal = true;
                    break;
                }
            }

            if ($legal == false) {


                if (count($version) > 0) {
                    $updateMsg .= lang('tip_update_api_version', ['version' => $version[0]]);
                } else {
                    $updateMsg .= lang('tip_update_api_version', ['version' => '-1']);
                }

                $this->apiReturnErr($updateMsg, ErrorCode::Api_Need_Update);
            }

        } else {

            if ($version != $this->request_api_ver) {
                $updateMsg .= lang('tip_update_api_version', ['version' => $version]);

                $this->apiReturnErr($updateMsg, ErrorCode::Api_Need_Update);
            }
        }

    }

    /**
     * 退出应用
     * @param $result
     * @throws BusinessException
     */
    protected function returnResult($result)
    {
        $this->exitWhenError($result, true);
    }

    /**
     * @param $result
     * @param bool $retSuc
     * @throws BusinessException
     */
    protected function exitWhenError($result, $retSuc = false)
    {
        if ($result instanceof CallResult) {
            if (!$result->isSuccess()) {
                $this->apiReturnErr($result->getMsg());
            } elseif ($retSuc) {
                $data = $result->getData();
                if (!is_int($data) && !ValidateHelper::isNumberStr($data)) {
                    $this->apiReturnSuc($data);
                }

                $id = intval($data);
                //如果是数字，则应该是添加或修改操作
                //对于这种情况，如果大于0 则默认成功 否则 失败
                if ($id > 0) {
                    $this->apiReturnSuc(lang("success"));
                } else {
                    $this->apiReturnErr(lang("fail"));
                }
            }
        }

    }

    /**
     * @param $keys
     * @return array
     * @throws BusinessException
     */
    protected function getParams($keys)
    {
        $params = [];
        foreach ($keys as $key) {
            $params[$key] = $this->_post($key, '');
        }
        return $params;
    }

    /**
     * 合并必选和可选post参数并返回
     * @param string $str 需要检查的post参数
     * @param string $oth_str 不需检查的post参数
     * @return array
     * @throws BusinessException
     */
    protected function parsePost($str = '', $oth_str = '')
    {
        return array_merge($this->getPost($str, true), $this->getPost($oth_str, false));
    }

    /**
     *
     * 获取post参数并返回
     * $need:是否必选
     * a|0|int  默认0
     * a        默认''
     * a|p      默认'p'
     * @DateTime 2016-12-13T10:25:17+0800
     * @param $str
     * @param bool $need
     * @return array
     * @throws BusinessException
     */
    protected function getPost($str, $need = false)
    {
        if (empty($str) || !is_string($str)) return [];
        $r = [];
        $arr = explode(',', $str);
        $data = $this->origin_data;
        foreach ($arr as $v) {
            $p = explode('|', $v);
            if (!isset($p[1])) $p[1] = '';   //默认值空字符串
            if (!isset($p[2])) $p[2] = 'str';//默认类型字符串

            $data['' . $p[0]] = isset($data['' . $p[0]]) ? $data['' . $p[0]] : '';
            $post = $data['' . $p[0]] === '' ? $p[1] : $data['' . $p[0]];
            if ($need && $post === '') {
                $this->apiReturnErr(lang('lack_parameter', ["param" => $p[0]]), ErrorCode::Lack_Parameter);
            }
            if ($p[2] === 'int') {
                $post = intval($post);
            } elseif ($p[2] === 'float') {
                $post = floatval($post);
            } elseif ($p[2] === 'mulint') {
                $post = array_unique(explode(',', $post));
                $temp = [];
                foreach ($post as $v1) {
                    if (is_numeric($v1)) $temp[] = $v1;
                }
                $post = implode(',', $temp);
            }
            $r[$p[0]] = $post;
        }
        return $r;
    }

    /**
     * 获取原始数据
     * @return array
     */
    protected function getOriginData()
    {
        return $this->origin_data;
    }

}