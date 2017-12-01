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

namespace app\component\bs\logic;

use app\component\bs\constants\BsStaticsParam;
use app\component\bs\entity\BsStaticsEntity;
use app\component\tp5\logic\BaseLogic;

class BsStaticsLogic extends BaseLogic
{
    public function statics()
    {
        // 统计 每日更新书籍
        $logic = new BsBookPageLogic();
        $count = $logic->getValidBookCount();
        $map = ['st_key'=>BsStaticsParam::EVERY_DAY_NEW_BOOK_COUNT];
        $result = $this->getInfo($map);

        if ($result instanceof BsStaticsEntity) {
            $stValue = $result->getStValue();
            if ($stValue != $count) {
                $this->save($map, ['st_value'=>$count]);
            }
        }
    }
}