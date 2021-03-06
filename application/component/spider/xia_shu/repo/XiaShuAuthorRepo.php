<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-16 18:55
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\spider\xia_shu\repo;

use by\component\spider\xia_shu\entity\XiaShuAuthorEntity;
use by\infrastructure\helper\CallResultHelper;
use think\Db;
use think\Model;

/**
 * Class XiaShuAuthorRepo
 * 作者
 * @package by\component\spider\xia_shu\repo
 */
class XiaShuAuthorRepo extends Model
{
    protected $table = "bs_author";

    protected $connection = 'cli_database';

    /**
     * 如果不存在则添加到数据库
     * @param XiaShuAuthorEntity $authorEntity
     * @return \by\infrastructure\base\CallResult
     */
    public function addIfNotExist(XiaShuAuthorEntity $authorEntity)
    {
        $map = ['pen_name' => $authorEntity->getPenName()];
        $result = Db::table($this->table)->where($map)->find();
        if (empty($result)) {
            $result = Db::table($this->table)->insert($authorEntity->toArray());
            if ($result == 1) {
                return CallResultHelper::success(Db::table($this->table)->getLastInsID());
            }
        } else {
            return CallResultHelper::success($result['id']);
        }

        return CallResultHelper::fail('fail');
    }
}