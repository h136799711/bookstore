<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-27 18:13
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\qiniu\interfaces;

/**
 * qiniu 配置接口
 * Interface QiniuConfigInterface
 * @package by\component\qiniu\interfaces
 */
interface QiniuConfigInterface
{
    /**
     * 获取应用key
     * @return string
     */
    public function getAppKey();

    /**
     * 获取密钥
     * @return string
     */
    public function getSecretKey();

    /**
     * 获取默认绑定的域名
     * @return string
     */
    public function getBindDomainName();

    /**
     * 获取默认的bucket空间名称
     * @return string
     */
    public function getDefaultBucket();
}