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


use app\component\spider\xia_shu\entity\XiaShuBookPageEntity;
use by\infrastructure\helper\CallResultHelper;
use think\Db;
use think\Model;

class XiaShuBookPageRepo extends Model
{
    protected $table = "bs_book_page";

    protected $connection = 'cli_database';

    /**
     * 向数据库添加数据
     * @param XiaShuBookPageEntity $bookPageEntity
     * @return \by\infrastructure\base\CallResult
     */
    public function add(XiaShuBookPageEntity $bookPageEntity)
    {

        $result = Db::table($this->table)->insert($bookPageEntity->toArray());
        if ($result == 1) {
            return CallResultHelper::success(Db::table($this->table)->getLastInsID());
        }

        return CallResultHelper::fail('insert fail');
    }
}