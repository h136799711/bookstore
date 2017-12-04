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


use app\component\bs\entity\BsBookPageContentEntity;
use app\component\bs\logic\BsBookPageContentLogic;
use app\component\spider\constants\BookSiteIntegerType;
use app\component\spider\constants\BookSiteType;
use app\component\spider\xia_shu\entity\XiaShuBookPageEntity;
use app\component\spider\xia_shu\helper\CurlHelper;
use app\component\spider\xia_shu\repo\XiaShuBookPageRepo;
use by\infrastructure\helper\CallResultHelper;
use Sunra\PhpSimple\HtmlDomParser;
use think\exception\ErrorException;

class XiaShuBookPageParser
{

    private $repo;
    private $logic;
    private $shouldCreateText;

    public function __construct()
    {
        $this->repo = new XiaShuBookPageRepo();
        $this->logic = new BsBookPageContentLogic(BookSiteIntegerType::XIA_SHU_BOOK_SITE);
        $this->shouldCreateText = false;
    }

    /**
     * 1. 保存书页内容
     * 3. 更新当前最新书页地址
     * @param $bookId
     * @param $pageNo
     * @param $url
     * @return \by\infrastructure\base\CallResult
     * @throws \think\Exception
     */
    public function parse($bookId, $pageNo, $url)
    {
        try {
            $html = CurlHelper::getHtml($url, BookSiteType::XIA_SHU_BOOK_SITE);
            $dom = HtmlDomParser::str_get_html($html);
            $pageContent = new BsBookPageContentEntity();
            $bookPageEntity = new XiaShuBookPageEntity();
            $bookPageEntity->setBookId($bookId);
            $bookPageEntity->setPageNo($pageNo);
            $bookPageEntity->setSourceType(BookSiteIntegerType::XIA_SHU_BOOK_SITE);


            $items = $dom->find("div.info span", 2);
            if ($items) {
                $updateTime = $items->innertext();
                $updateTime = str_replace('更新时间：', '', $updateTime);
                $updateTime = strtotime($updateTime);
                $bookPageEntity->setUpdateTime($updateTime);
            }

            $items = $dom->find("div.title h1 a", 0);
            if ($items) {
                $bookPageEntity->setPageTitle($items->innertext());
            } else {
                $bookPageEntity->setPageTitle('未知标题');
            }
            $contentSelector = "div#chaptercontent";
            $items = $dom->find($contentSelector, 0);
            if ($items) {
                $pageContent->setBookId($bookId);
                $pageContent->setPageNo($pageNo);
                $pageContent->setPageContent($items->innertext());
            } else {
                $pageContent->setPageContent('--');
                return CallResultHelper::fail('page content empty');
            }

            if ($this->isShouldCreateText() && !empty($pageContent->getPageContent())) {
                $filePath = ROOT_PATH . "txt/b" . $bookPageEntity->getBookId() . '/';
                $fileName = $bookPageEntity->getBookId() . '_' . $pageNo . '.txt';
                $this->file_write($filePath, $fileName, $pageContent->getPageContent());
            }
            // 插入书页信息
            $callResult = $this->repo->add($bookPageEntity);
            if ($callResult->isSuccess()) {
                // 插入书籍内容
//                $this->logic->add($pageContent, '');
                return CallResultHelper::success('0');
            }
            return $callResult;
        } catch (ErrorException $exception) {
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

    function mkdirs($dir, $mode = 0777)
    {
        if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
        return @mkdir($dir, $mode);
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

}