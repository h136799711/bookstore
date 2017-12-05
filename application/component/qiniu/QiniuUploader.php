<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-27 18:12
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\qiniu;

use by\component\qiniu\interfaces\QiniuConfigInterface;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;
use Qiniu\Auth;
use Qiniu\Http\Error;
use Qiniu\Storage\UploadManager;

/**
 * Class QiniuUploader
 * 七牛上传类
 * @package by\component\qiniu
 */
class QiniuUploader
{
    private $auth;
    /**
     * @var QiniuConfigInterface
     */
    private $config;

    // construct
    public function __construct(QiniuConfigInterface $qiniuConfig)
    {
        $this->config = $qiniuConfig;
        $this->auth = new Auth($qiniuConfig->getAppKey(), $qiniuConfig->getSecretKey());
    }

    /**
     * 获取 token
     * @see https://developer.qiniu.com/kodo/sdk/1241/php#4
     * @param string $bucket bucket名称
     * @param string|null $key 上传的文件名称
     * @param int|null $expires
     * @param array|null $policy
     * @param bool $strictPolicy
     * @return CallResult
     */
    public function uploadToken($bucket, $key = null, $expires = 7200, $policy = null, $strictPolicy = true)
    {
        if (empty($policy)) {
            $domainName = $this->config->getBindDomainName();
            $returnBody = '{"key":"' . $domainName . '/$(key)","hash":"$(etag)","fsize":$(fsize),"bucket":"$(bucket)"}';
            $policy = array(
                'returnBody' => $returnBody
            );
        }
        $result = $this->auth->uploadToken($bucket, $key, $expires, $policy, $strictPolicy);

        if ($result instanceof Error) {
            return CallResultHelper::fail($result->message(), $result->getResponse());
        }

        return CallResultHelper::success($result);
    }

    /**
     * 二进制文件流上传
     * @param string $token 通过 uploadToken 获得的字符串
     * @param string $key 七牛上文件的名称 支持  cover/0/2.jpg 这种格式
     * @param $data
     * @return \by\infrastructure\base\CallResult
     */
    public function put($token, $key, $data)
    {
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->put($token, $key, $data);
        if ($err !== null) {
            if ($err instanceof Error) {
                return CallResultHelper::fail($err->message(), $err->getResponse());
            }

            return CallResultHelper::fail($err);
        } else {
            return CallResultHelper::success($ret);
        }
    }
}