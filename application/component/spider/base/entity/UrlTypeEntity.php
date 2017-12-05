<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-14 14:00
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\spider\base\entity;

/**
 * Class UrlTypeEntity
 * 链接类型
 * @package by\component\spider\base\entity
 */
class UrlTypeEntity
{
    /**
     * 链接类型
     * @var string
     */
    private $url;

    /**
     * UrlTypeEntity constructor.
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * 获取类型
     * @return string
     */
    public function getTypeKey()
    {
        // TODO 根据url去除参数之后的链接
        return md5($this->url);
    }

}