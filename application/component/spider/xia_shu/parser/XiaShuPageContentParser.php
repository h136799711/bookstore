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


use by\component\spider\constants\BookSiteType;
use by\component\spider\xia_shu\helper\CurlHelper;
use by\infrastructure\helper\CallResultHelper;
use Sunra\PhpSimple\HtmlDomParser;
use think\exception\ErrorException;

class XiaShuPageContentParser
{
    private $shouldCreateText;

    public function __construct()
    {
        $this->shouldCreateText = true;
    }

    /**
     * 1. 获取书页内容并保存到本地
     * @param $bookId
     * @param $pageNo
     * @return \by\infrastructure\base\CallResult
     */
    public function parse($bookId, $pageNo)
    {
        try {
            $url = BookSiteType::XIA_SHU_BOOK_SITE . '/' . $bookId . '/read_' . $pageNo . '.html';
            $html = CurlHelper::getHtml($url, BookSiteType::XIA_SHU_BOOK_SITE);
            $dom = HtmlDomParser::str_get_html($html);
            $contentSelector = "div#chaptercontent";
            $items = $dom->find($contentSelector, 0);
            if ($items) {
                $content = $items->innertext();
            } else {
                return CallResultHelper::fail('page content empty');
            }

//            if ($this->isShouldCreateText() && !empty($pageContent->getPageContent())) {
//                $filePath = ROOT_PATH . "txt/b" . $bookPageEntity->getBookId() . '/';
//                $fileName = $bookPageEntity->getBookId() . '_' . $pageNo . '.txt';
//                $this->file_write($filePath, $fileName, $pageContent->getPageContent());
//            }
            // 插入书页信息
            $title = "未知标题";
            $updateTime = 0;
            $items = $dom->find("div.info span", 2);
            if ($items) {
                $updateTime = $items->innertext();
                $updateTime = str_replace('更新时间：', '', $updateTime);
                $updateTime = strtotime($updateTime);
            }

            $items = $dom->find("div.title h1 a", 0);
            if ($items) {
                $title = $items->innertext();
            }

            return CallResultHelper::success(['update_time' => $updateTime, 'page_title' => $title, 'page_content' => $content]);
        } catch (ErrorException $exception) {
            return CallResultHelper::fail($exception->getMessage());
        } catch (\Exception $exception) {
            return CallResultHelper::fail($exception->getMessage());
        }
    }

    /**
     * @return bool
     */
    public function isShouldCreateText()
    {
        return $this->shouldCreateText;
    }

    /**
     * @param bool $shouldCreateText
     */
    public function setShouldCreateText($shouldCreateText)
    {
        $this->shouldCreateText = $shouldCreateText;
    }

    private function file_write($path, $filename, $content)
    {
        echo 'save text to' . $path;
        if (!$this->mkdirs($path)) {
            echo 'save fail';
            return;
        }

        $file = fopen($path . $filename, 'w+');

        fwrite($file, $content);

        fclose($file);
    }

    function mkdirs($dir, $mode = 0777)
    {
        if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
        return @mkdir($dir, $mode);
    }

}