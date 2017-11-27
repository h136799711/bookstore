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

namespace app\component\qiniu;

use app\component\qiniu\interfaces\QiniuConfigInterface;
use by\infrastructure\helper\CallResultHelper;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class QiniuUploader
{
    private $auth;

    // construct
    public function __construct(QiniuConfigInterface $qiniuConfig)
    {
        $this->auth = new Auth($qiniuConfig->getAppKey(), $qiniuConfig->getSecretKey());
    }

    /**
     *
     * @see https://developer.qiniu.com/kodo/sdk/1241/php#4
     * @param $bucket
     * @param null $key
     * @param int $expires
     * @param null $policy
     * @param bool $strictPolicy
     * @return string
     */
    public function uploadToken($bucket, $key = null, $expires = 7200, $policy = null, $strictPolicy = true)
    {
        if (empty($policy)) {
            $returnBody = '{"key":"$(key)","hash":"$(etag)","fsize":$(fsize),"bucket":"$(bucket)"}';
            $policy = array(
                'returnBody' => $returnBody
            );
        }
        $upToken = $this->auth->uploadToken($bucket, $key, $expires, $policy, $strictPolicy);
        return $upToken;
    }

    /**
     * 二进制文件流上传
     * @param $token
     * @param $key
     * @param $data
     * @return \by\infrastructure\base\CallResult
     */
    public function put($token, $key, $data)
    {
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->put($token, $key, $data);
        if ($err !== null) {
            return CallResultHelper::fail($err);
        } else {
            return CallResultHelper::success($ret);
        }
    }
}