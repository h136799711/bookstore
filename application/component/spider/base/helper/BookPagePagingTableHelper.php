<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-22 16:26
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\spider\base\helper;


use think\Db;

class BookPagePagingTableHelper
{
    public static function createTableIfNotExists($tableName, $id)
    {
        $trueTableName = self::getTableName($tableName, $id);
        $sql = "select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_SCHEMA='" . $trueTableName . "';";
        $result = Db::query($sql);

    }

    public static function getTableName($tableName, $id)
    {
        $postFix = $id / 10000;

        return $tableName . '_' . $postFix;
    }
}