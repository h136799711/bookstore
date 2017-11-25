<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-16 11:32
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\spider\xia_shu;


use app\component\spider\base\AbstractSpider;
use app\component\spider\constants\BookSiteType;
use app\component\spider\xia_shu\entity\XiaShuSpiderBookUrlEntity;
use app\component\spider\xia_shu\parser\XiaShuBookParser;
use app\component\spider\xia_shu\repo\XiaShuBookSourceRepo;
use app\component\spider\xia_shu\repo\XiaShuSpiderUrlRepo;

class XiaShuNewBookSpider extends AbstractSpider
{
    // 当前书籍id
    /**
     * @var XiaShuBookSourceRepo
     */
    public $repo;
    private $curBookId = 0;
    private $name;

    public function __construct($name, $curBookId = 1)
    {
        $this->name = $name;
        $this->repo = new XiaShuBookSourceRepo();
        $this->curBookId = $curBookId;
    }

    /**
     *
     * @return $this
     */
    public function start()
    {
        $this->curBookId = $this->getLatest() + 1;
        echo "\n", 'start from' . $this->getBookUrl($this->curBookId), "\n";
        $repo = new XiaShuSpiderUrlRepo();
        echo "\n", 'is valid start?', $this->isValidBookId($this->curBookId) ? "yes" : 'no';
        $failTimes = 0;
        while ($failTimes < 10 && $this->isValidBookId($this->curBookId)) {
            $url = $this->getBookUrl($this->curBookId);
            $data = [
                'url' => $url
            ];
            $result = $this->parseUrl($data);

            if (!$result->isSuccess()) {
                $failTimes++;
                // 记录到爬取记录
                $entity = new XiaShuSpiderBookUrlEntity($url);
                $entity->setSpiderStatus(XiaShuSpiderBookUrlEntity::SPIDER_STATUS_FAIL);
                $entity->setSpiderInfo($result->getMsg());
                $repo->addOrUpdate($entity);
            }

            $this->nextBatchUrls(1);
            sleep(3);
        }

        return $this;
    }

    protected function getLatest()
    {
        $result = (new XiaShuBookSourceRepo())->order('source_book_id', 'desc')->find();

        if (!empty($result)) {
            return $result['source_book_id'];
        }

        return 0;
    }

    public function getBookUrl($bookId)
    {
        return BookSiteType::XIA_SHU_BOOK_SITE . '/' . $bookId;
    }

    public function isValidBookId($bookId)
    {
        $retCode = $this->curl_get_code($this->getBookUrl($bookId));
        echo $retCode;
        return $retCode == 200;
    }

    public function curl_get_code($url)
    {
        $ch = curl_init();
        $header = [];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_REFERER, "https://www.xiashu.cc");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_COOKIE, '');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36');
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return $httpCode;
    }

    function parseUrl($data)
    {
        echo 'process ' . $data['url'], "\n";
        $parser = new XiaShuBookParser($data['url']);
        return $parser->parse();
    }

    function nextBatchUrls($limit = 10)
    {
        $this->curBookId++;
    }

    public function curl_request($url, $post = '', $cookie = '', $returnCookie = 0)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
        if ($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        if ($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);

        if (curl_errno($curl)) {
            return curl_error($curl);
        }

        curl_close($curl);
        if ($returnCookie) {
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie'] = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        } else {
            return $data;
        }
    }
}