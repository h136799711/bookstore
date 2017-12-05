<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/3
 * Time: 20:22
 */

namespace by\api\index\controller;


use by\api\constants\ErrorCode;
use by\api\controller\entity\ApiCommonEntity;
use by\component\encrypt\factory\TransportFactory;
use by\component\encrypt\interfaces\TransportInterface;
use by\component\oauth2\entity\OauthClientsEntity;
use by\component\oauth2\logic\OauthClientsLogic;
use by\infrastructure\base\CallResult;
use think\controller\Rest;
use think\Request;


/**
 * 接口基类
 * Class Base
 *
 * @author 老胖子-何必都 <hebiduhebi@126.com>
 * @package by\index\Controller
 */
abstract class Base extends Rest{

    /**
     * 所有请求的数据
     * 以及可能的全局配置变量
     * @var ApiCommonEntity
     */
    protected $allData;

    /**
     *
     * @var TransportInterface
     */
    protected $transport;

    /**
     * Base constructor.
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function __construct(){
        $this->allData = new ApiCommonEntity();
        parent::__construct();
        $this->_initialize();
    }

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function _initialize(){
        // 1. 设置语言参数, 默认简体中文 zh-cn
        $lang = Request::instance()->get("lang","zh-cn");
        $this->allData->setLang(strtolower($lang));
        // 2. 获取应用id
        $client_id = $this->_param("client_id","", lang('lack_parameter',['param'=>'client_id']));
        $logic = new OauthClientsLogic();
        $result = $logic->getInfo(['client_id'=>$client_id]);
        if(!($result instanceof OauthClientsEntity)){
            $this->apiReturnErr(lang('err_client_id_not_exists', ['client_id'=>$client_id]),ErrorCode::Invalid_Parameter);
        }
        $alg = $result->getApiAlg();
        $data = Request::instance()->param();
        $data['by_client_id'] = $result->getClientId();
        $data['by_client_secret'] = $result->getClientSecret();
        $this->transport = TransportFactory::getAlg($alg, $data);
        $this->allData->setData($this->transport->decrypt());
    }

    public function _param($key, $default='',$emptyErrMsg=''){
        $value = Request::instance()->post($key,$default);

        if($value == $default || empty($value)){
            $value =  Request::instance()->get($key,$default);
        }

        if($default == $value && !empty($emptyErrMsg)){
            $this->apiReturnErr($emptyErrMsg);
        }
        return $value;
    }


    protected function apiReturnErr($obj, $code = -1, $data = [])
    {
        if ($obj instanceof CallResult) {
            $code = $obj->getCode();
            $data = $obj->getData();
            $obj = $obj->getMsg();
        }

        $this->ajaxReturn(['msg'=>$obj, 'code'=>$code,'data'=>$data,'notify_id'=>$this->allData->getId()]);
    }


    /**
     * 返回数据
     * @param $data
     */
    protected function ajaxReturn($data) {

        if($this->transport instanceof TransportInterface){
            $data = $this->transport->encrypt();
        }

        $response = $this->response($data, "json",200);
        $siteUrl = config('site_url');
        if (empty($siteUrl)) {
            $siteUrl = "www.itboye.com";
        }
        $response->header("X-Powered-By", $siteUrl)->header("X-BY-Notify-ID",$this->allData->getId())->send();
        exit(0);
    }

    /**
     * @param $key
     * @param string $default
     * @param string $emptyErrMsg  为空时的报错
     * @return mixed
     */
    public function _post($key,$default='',$emptyErrMsg=''){

        $value = Request::instance()->post($key,$default);

        if($default == $value && !empty($emptyErrMsg)){
            $this->apiReturnErr($emptyErrMsg);
        }
        return $value;
    }

    /**
     * 过滤末尾多余空白符 ASCII码等于7的奇怪符号
     * @param $post
     * @return string
     */
    protected function filter_post($post){
        $post = trim($post);
        for ($i=strlen($post)-1;$i>=0;$i--) {
            $ord = ord($post[$i]);
            if($ord > 31 && $ord != 127){
                $post = substr($post,0,$i+1);
                return $post;
            }
        }
        return $post;
    }

    /**
     * @param $key
     * @param string $default
     * @param string $emptyErrMsg  为空时的报错
     * @return mixed
     */
    public function _get($key,$default='',$emptyErrMsg=''){
        $value = Request::instance()->get($key,$default);

        if($default == $value && !empty($emptyErrMsg)){
            $this->apiReturnErr($emptyErrMsg);
        }
        return $value;
    }

    /**
     * 从请求头部获取参数
     * @param $key
     * @param string $default
     * @param string $emptyErrMsg
     * @return string
     */
    public function _header($key,$default='',$emptyErrMsg = ''){

        $value = Request::instance()->header($key);

        if($default == $value && !empty($emptyErrMsg)){
            $this->apiReturnErr($emptyErrMsg);
        }
        return $value;
    }

    protected function apiReturnSuc($data){
        $msg = 'success';
        $code = 0;
        if ($data instanceof CallResult) {
            $msg = $data->getMsg();
            $code = $data->getCode();
            $data = $data->getData();
        }

        $this->ajaxReturn(['code'=>$code, 'data'=>$data, 'msg'=>$msg, 'notify_id'=>$this->allData->getId()]);
    }

}