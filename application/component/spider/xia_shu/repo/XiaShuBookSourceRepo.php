<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-17 14:43
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\spider\xia_shu\repo;


use app\component\spider\xia_shu\entity\XiaShuBookSourceEntity;
use by\infrastructure\helper\CallResultHelper;
use think\Db;
use think\Model;

class XiaShuBookSourceRepo extends Model
{
    protected $table = "bs_book_source";

    protected $connection = 'cli_database';

    /**
     * 向数据库添加数据如果不存在的话
     * @param XiaShuBookSourceEntity $bookSourceEntity
     * @return \by\infrastructure\base\CallResult
     */
    public function addIfNotExist(XiaShuBookSourceEntity $bookSourceEntity)
    {
        $map = [
            'book_id' => $bookSourceEntity->getBookId(),
            'book_address' => $bookSourceEntity->getBookAddress()
        ];

        $result = Db::table($this->table)->where($map)->find();

        if (empty($result)) {
            $result = Db::table($this->table)->insert($bookSourceEntity->toArray());
            if ($result == 1) {
                return CallResultHelper::success(Db::table($this->table)->getLastInsID());
            }
        } else {
            $id = $result['id'];
            return CallResultHelper::success($id);
        }

        return CallResultHelper::fail('fail');
    }
}