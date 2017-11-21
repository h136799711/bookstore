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


use think\Db;
use think\Model;

/**
 * Class SpiderUrlRepo
 * 爬虫爬取的 url
 * @package by\component\xia_shu\repo
 */
class XiaShuSpiderUrlRepo extends Model
{

    protected $connection = 'cli_database';

    protected $table = 'xiashu_book_url';
    private $view = 'v_xiashu_book_url_not_over';

    public function clearMark($name)
    {
        $result = Db::table($this->view)->where('spider_id', 'eq', $name)
            ->update(['spider_id' => '', 'update_time' => time()]);
        return $result;
    }

    public function mark($name, $startId, $endId)
    {
        $startId = $startId - 1 > 0 ? $startId - 1 : 0;
        $result = Db::table($this->view)->where('id', '<', $endId)->where('id', '>', $startId)
            ->update(['spider_id' => $name, 'update_time' => time()]);
        return $result;
    }

    public function queryBetween($name, $startPage = 0, $page = 10)
    {
        $result = Db::table($this->view)->limit($startPage * $page, $page)->where('spider_id', 'eq', $name)->fetchSql(false)
            ->select();
        return $result;
    }
}