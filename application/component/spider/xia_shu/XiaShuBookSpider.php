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
use by\component\spider\xia_shu\repo\XiaShuSpiderUrlRepo;

class XiaShuBookSpider extends AbstractSpider
{
    // 总共处理多少条记录
    private $size = 0;
    // 当前索引
    private $curPage = 0;
    // 每次处理
    private $perPage = 1000;
    private $name;
    /**
     * @var XiaShuSpiderUrlRepo
     */
    public $repo;

    public function __construct($name, $size = 1000, $perPage = 1000)
    {
        $this->name = $name;
        $this->repo = new XiaShuSpiderUrlRepo();
        $this->size = $size;
        $this->curPage = 0;
        $this->perPage = $perPage;
    }

    public function clearMark()
    {
        $this->repo->clearMark($this->name);
    }

    /**
     * 标记
     */
    public function mark()
    {
        $this->repo->mark($this->name, $this->size);
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function start()
    {
        $pageIndex = 0;
        while ($pageIndex < $this->size) {

            $now = time();

            // 读取指定个数
            $batchUrlData = $this->nextBatchUrls($this->perPage);
            $list = [];
            foreach ($batchUrlData as $data) {
                $result = $this->parseUrl($data);
                $tmp = ['id' => $data['id']];
                $tmp['spider_status'] = XiaShuSpiderBookUrlEntity::SPIDER_STATUS_SUCCESS;
                $tmp['spider_active_time'] = $now;
                $tmp['spider_info'] = $result->getMsg();
                $tmp['fail_cnt'] = 0;

                if (!$result->isSuccess()) {
                    // 失败次数增加一次
                    $this->repo->where('id', 'eq', $data['id'])->inc('fail_cnt', 1);
                    $tmp['spider_status'] = XiaShuSpiderBookUrlEntity::SPIDER_STATUS_FAIL;
                }

                array_push($list, $tmp);
            }
            $this->repo->isUpdate(true)->saveAll($list);
            $pageIndex += $this->perPage;
            $this->curPage++;
            sleep(3);
        }

        return $this;
    }

    function nextBatchUrls($limit = 10)
    {
        return $this->repo->queryBetween($this->name, $this->curPage, $this->perPage);
    }

    function parseUrl($data)
    {
        echo 'process ' . $data['url'], "\n";
        $parser = new XiaShuBookParser($data['url']);
        return $parser->parse();
    }

}