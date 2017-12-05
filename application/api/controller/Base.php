<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/3
 * Time: 20:22
 */

namespace by\index\controller;


use by\api\constants\ErrorCode;
use by\infrastructure\base\CallResult;
use by\src\encrypt\algorithm\AlgFactory;
use by\src\encrypt\algorithm\AlgParams;
use by\src\encrypt\algorithm\IAlgorithm;
use by\src\encrypt\exception\CryptException;
use by\src\encrypt\response\ResponseHelper;
use by\src\helper\ExceptionHelper;
use by\src\oauth2\logic\OauthClientsLogic;
use think\controller\Rest;
use think\Exception;
use think\Request;


/**
 * 接口基类
 * Class Base
 *
 * @author 老胖子-何必都 <hebiduhebi@126.com>
 * @package by\index\Controller
 */
abstract class Base extends Rest{

    protected $lang;//当前请求的语言版本
//    protected $alg;//当前请求通信算法
    protected $algInstance;//当前请求通信算法
    protected $encrypt_key = "";
    protected $alParams ;

    protected $allow_controller = array( );

    /**
     * 构造函数
     */
    public function __construct(){
        $this->alParams = new AlgParams();
        try{
            parent::__construct();

            if(method_exists($this,"_initialize")){
                $this->_initialize();
            }
        }catch (CryptException $ex){
            $this->apiReturnErr($ex->getMessage());
        }catch (Exception $ex){
            $this->apiReturnErr(ExceptionHelper::getErrorString($ex));
        }
    }

    public function _initialize(){
        $this->_decodePost();
        $this->lang = Request::instance()->get("lang","zh-cn");
        $this->lang = strtolower($this->lang);
//        $this->lang = "en";
    }

    protected function _decodePost(){
        $client_id = $this->_param("client_id","", lang('lack_parameter',['param'=>'client_id']));
        //1. 获取client_id参数
        $this->alParams->setClientId($client_id);

        $api = new OauthClientsLogic();

        $result = $api->getInfo(['client_id'=>$this->alParams->getClientId()]);
        
        if(!$result['status']){
            $this->apiReturnErr($result['info'],ErrorCode::Invalid_Parameter);
        }
        $clientInfo = $result['info'];
        $alg = $clientInfo['api_alg'];

        $this->alParams->setClientSecret($clientInfo['client_secret']);

        //读取传输过来的加密参数
        $post = $this->_post('itboye','');

        $algFactory = new AlgFactory();
        $this->algInstance = $algFactory->getAlg($alg);
        $data = $this->algInstance->decryptTransmissionData($post,$this->alParams->getClientSecret());
        $data = $this->filter_post($data);
        $obj = json_decode($data,JSON_OBJECT_AS_ARRAY);

        $data = empty($obj) ? [] : $obj;
        $data = array_merge($data,empty($_GET) ? [] : $_GET);

        $this->alParams->initFromArray($data);

        $this->alParams->isValid();
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

    /**
     * ajax返回，并自动写入notify_id返回
     * @param string|CallResult $obj
     * @param int $code
     * @param array $data
     */
    protected function apiReturnErr($obj, $code = -1, $data = [])
    {
        if ($obj instanceof CallResult) {
            $code = $obj->getCode();
            $data = $obj->getData();
            $obj = $obj->getMsg();
        }

        $this->ajaxReturn(['msg'=>$obj, 'code'=>$code,'data'=>$data,'notify_id'=>$this->alParams->getNotifyId()]);
    }

    /**
     * 返回加密后的数据
     * @access protected
     * @param mixed $data 要返回的数据，未加密
     * @return array
     */
    protected function ajaxReturn($data) {

        if($this->algInstance instanceof IAlgorithm){
            $data = ResponseHelper::getResponseParams($this->algInstance,$data,$this->alParams->getClientId(),$this->alParams->getClientSecret(),$this->alParams->getNotifyId());
        }

        $response = $this->response($data, "json",200);
        $siteUrl = config('site_url');
        if (empty($siteUrl)) {
            $siteUrl = "www.itboye.com";
        }
        $response->header("X-Powered-By", $siteUrl)->header("X-BY-Notify-ID",$this->alParams->getNotifyId())->send();
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

    /**
     * ajax返回
     * @param $data
     * @internal param $i
     */
    protected function apiReturnSuc($data){
        $msg = 'success';
        $code = 0;
        if ($data instanceof CallResult) {
            $msg = $data->getMsg();
            $code = $data->getCode();
            $data = $data->getData();
        }

        $this->ajaxReturn(['code'=>$code, 'data'=>$data, 'msg'=>$msg, 'notify_id'=>$this->alParams->getNotifyId()]);
    }



}