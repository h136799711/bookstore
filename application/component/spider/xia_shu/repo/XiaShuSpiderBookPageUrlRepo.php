<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-16 11:33
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\spider\xia_shu\repo;


use app\component\spider\constants\BookSiteIntegerType;
use app\component\spider\xia_shu\entity\XiaShuSpiderBookPageUrlEntity;
use by\infrastructure\helper\CallResultHelper;
use think\Db;
use think\Model;

/**
 * Class XiaShuSpiderBookPageUrlRepo
 * 爬虫爬取的 url
 * @package by\component\xia_shu\repo
 */
class XiaShuSpiderBookPageUrlRepo extends Model
{

    protected $connection = 'cli_database';

    protected $table = 'xiashu_book_page_url';

    protected $view = "v_xiashu_book_page_url";

    public function saveIfUpdateUrl($map, $url)
    {
        $result = $this->where($map)->find();

        if ($result) {
            if ($url == $result->getData('url')) {
                return;
            }
            $this->where($map)->update(['update_time' => time(), 'url' => $url]);
        }
    }

    /**
     * 获取有效的
     * @param int $limit
     * @return \by\infrastructure\base\CallResult
     */
    public function getValidSpiderBookPageUrl($limit = 1)
    {
        $map = [
            'source_type' => BookSiteIntegerType::XIA_SHU_BOOK_SITE
        ];
        $limit = $limit > 100 ? 100 : $limit;
        $result = Db::table($this->view)->order('priority', 'desc')->where($map)->limit(0, $limit)->fetchSql(false)->select();
        if (empty($result)) {
            return CallResultHelper::fail('', 'no valid url');
        } else {
            return CallResultHelper::success($result);
        }
    }

    public function addIfNotExist(XiaShuSpiderBookPageUrlEntity $urlEntity)
    {
        $map = [
            'book_id' => $urlEntity->getBookId(),
            'source' => $urlEntity->getSource()
        ];

        $result = Db::table($this->table)->where($map)->find();

        if (empty($result)) {
            $result = Db::table($this->table)->insert($urlEntity->toArray());
            if ($result == 1) {
                return CallResultHelper::success(Db::table($this->table)->getLastInsID());
            }
        } else {
            $id = $result['id'];
            return CallResultHelper::success($id);
        }

        return CallResultHelper::fail('fail');
    }

    public function queryBetween($name, $startPage = 0, $page = 10)
    {
        $result = Db::table($this->view)->limit($startPage * $page, $page)->where('spider_id', 'eq', $name)->fetchSql(false)
            ->select();
        return $result;
    }
}