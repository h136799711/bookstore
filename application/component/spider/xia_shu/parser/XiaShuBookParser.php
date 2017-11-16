<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-16 18:40
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\spider\xia_shu\parser;


use app\component\spider\xia_shu\entity\XiaShuBookEntity;
use Sunra\PhpSimple\HtmlDomParser;

class XiaShuBookParser
{
    private $url;

    // construct
    public function __construct($url)
    {
        $this->url = $url;
    }

    public function parse()
    {
        $this->url = 'https://www.xiashu.cc/100';
        $dom = HtmlDomParser::file_get_html("https://www.xiashu.cc/100");
        $entity = new XiaShuBookEntity();
        $entity->setCreateTime(time());
        $entity->setUpdateTime(time());
    }

}