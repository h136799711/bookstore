<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-14 13:50
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\spider\base\entity;

use by\infrastructure\base\BaseEntity;

/**
 * Class SpiderUrl
 * 待爬的URL
 * @package app\component\spider\base\entity
 */
class SpiderUrlEntity extends BaseEntity
{

    private $url;

    public function __construct($url)
    {
        parent::__construct();
        $this->url = rtrim($url, '/');
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}