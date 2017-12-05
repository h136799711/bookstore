<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-21 10:13
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\bs\logic;


use by\component\tp5\logic\BaseLogic;
use think\Db;

class BsBookPageLogic extends BaseLogic
{
    /**
     * 获取有书页的书籍数目
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function getValidBookCount()
    {
        $subQuery = $this->getModel()->group('book_id')->field('book_id')->buildSql();
        $count = Db::connect('book_page_db')->table($subQuery . ' a')->count();

        return $count;
    }

    /**
     * @param $pageInfoData
     * @return array|false|int|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function addIfNotExist($pageInfoData)
    {
        $map = [
            'book_id' => $pageInfoData['book_id'],
            'page_no' => $pageInfoData['page_no'],
            'source_type' => $pageInfoData['source_type']
        ];

        $result = $this->getModel()->where($map)->find();
        if (empty($result)) {
            return $this->getModel()->insert($pageInfoData);
        }
        return true;
    }
}