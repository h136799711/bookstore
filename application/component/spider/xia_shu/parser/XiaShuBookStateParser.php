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

namespace by\component\spider\xia_shu\parser;


use by\component\spider\xia_shu\entity\XiaShuBookEntity;
use by\infrastructure\helper\CallResultHelper;
use Sunra\PhpSimple\HtmlDomParser;
use think\exception\ErrorException;

class XiaShuBookStateParser
{
    private $errorInfo;
    private $url;

    // construct
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * 1. 读取状态，如果状态是已完结则返回true
     * @return \by\infrastructure\base\CallResult
     */
    public function parse()
    {
        try {
            $dom = HtmlDomParser::file_get_html($this->url);
            $ret = $dom->find('meta');

            if (count($ret) == 0) {
                $this->errorInfo = "该书页没有meta标签";
            } else {
                $this->errorInfo = "该书页没有有效的meta标签,无法解析";
            }

            $stateProperty = "og:novel:status";

            foreach ($ret as $domNode) {
                $property = trim($domNode->getAttribute('property'));
                $content = trim($domNode->getAttribute('content'));
                if (!$property) {
                    continue;
                }
                $this->errorInfo = "";

                // 连载状态
                if ($property == $stateProperty) {
                    $state = $this->getState($content);
                    if ($state == XiaShuBookEntity::STATE_Unknown) {
                        return CallResultHelper::fail('书籍状态无法识别');
                    }
                    return CallResultHelper::success($state);
                }
            }

            return CallResultHelper::fail($this->errorInfo);

        } catch (ErrorException $exception) {
            return CallResultHelper::fail($exception->getMessage());
        }
    }

    private function getState($content)
    {
        $content = trim($content);
        switch ($content) {
            case "连载中":
                return XiaShuBookEntity::STATE_Serialize;
            case "完结":
                return XiaShuBookEntity::STATE_END;
            default:
                return XiaShuBookEntity::STATE_Unknown;
        }
    }


}