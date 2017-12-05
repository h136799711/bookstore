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

namespace by\component\spider\xia_shu;


use by\component\spider\base\AbstractSpider;
use by\component\spider\xia_shu\entity\XiaShuSpiderBookUrlEntity;
use by\component\spider\xia_shu\parser\XiaShuBookParser;
use by\component\spider\xia_shu\repo\XiaShuBookSourceRepo;
use by\component\spider\xia_shu\repo\XiaShuSpiderUrlRepo;

/**
 * Class XiaShuSecondBookSpider
 * 二次爬取
 * @package by\component\spider\xia_shu
 */
class XiaShuSecondBookSpider extends AbstractSpider
{
    // 当前书籍id
    /**
     * @var XiaShuBookSourceRepo
     */
    public $repo;
    private $size;

    public function __construct($size = 10)
    {
        $this->repo = new XiaShuBookSourceRepo();
        $this->size = $size;
    }

    /**
     *
     * @return $this
     */
    public function start()
    {
        $array = $this->getLatest();
        echo "\n", 'start spider count  = ' . count($array), "\n";
        $repo = new XiaShuSpiderUrlRepo();

        foreach ($array as $vo) {
            $url = $vo->getData('url');
            $data = [
                'url' => $url
            ];

            $result = $this->parseUrl($data);

            if (!$result->isSuccess()) {
                echo "spider fail".$result->getMsg(), "\n";
                // 记录到爬取记录
                $entity = new XiaShuSpiderBookUrlEntity($url);
                $entity->setSpiderStatus(XiaShuSpiderBookUrlEntity::SPIDER_STATUS_FAIL);
                $entity->setSpiderInfo($result->getMsg());
                $repo->addOrUpdate($entity);
            } else {
                echo "spider success", "\n";
                $repo->where(['url'=>$url])->delete();
            }

        }
        return $this;
    }

    protected function getLatest()
    {
        $result = (new XiaShuSpiderUrlRepo())->order('update_time', 'asc')->where(['spider_status'=>4])->limit(0, $this->size)->select();

        if (!empty($result)) {
            return $result;
        }

        return [];
    }

    function parseUrl($data)
    {
        echo 'process ' . $data['url'], "\n";
        $parser = new XiaShuBookParser($data['url']);
        return $parser->parse();
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

    function nextBatchUrls($limit = 10)
    {

    }
}