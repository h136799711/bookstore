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
use think\Db;

class BsStaticsLogic extends BaseLogic
{
    public function statics()
    {
        // 统计 每日更新书籍
        $this->countEveryDayNewBooks();

        // 统计 每日更新书页
        $this->countEveryDayNewPages();
    }

    private function countEveryDayNewBooks()
    {

        $logic = new BsBookPageLogic();
        $count = $logic->getValidBookCount();
        $time = strtotime(date("Y-m-d", time() - 24*3600));
        $map = ['st_key'=>BsStaticsParam::EVERY_DAY_NEW_BOOK_COUNT, 'create_time'=> $time];
        $result = $this->getInfo($map);
        if ($result instanceof BsStaticsEntity) {
            echo 'update';
            $stValue = $result->getStValue();
            $dayAdd = $count - $stValue;
        } else {
            echo 'insert';
            $dayAdd = $count;
        }

        $time = strtotime(date("Y-m-d", time()));
        $map = ['st_key'=>BsStaticsParam::EVERY_DAY_NEW_BOOK_COUNT, 'create_time'=> $time];

        $result = $this->getInfo($map);
        if ($result instanceof BsStaticsEntity) {
            $this->save($map, ['st_value' => $dayAdd, 'update_time' => time()]);
        } else {
            $entity = new BsStaticsEntity();
            $entity->setStKey(BsStaticsParam::EVERY_DAY_ADD_BOOK_PAGE_COUNT);
            $entity->setStValue($dayAdd);
            $entity->setCreateTime($time);
            $entity->setUpdateTime(time());
            $this->add($entity);
        }

    }

    private function countEveryDayNewPages()
    {

        $result = Db::connect('book_page_db')->table('v_day_add_pages')->field('pages')->find();
        $count = 0;
        if (is_array($result)) {
            $count = $result['pages'];
        }
        $map['st_key'] = BsStaticsParam::EVERY_DAY_ADD_BOOK_PAGE_COUNT;
        $logTime = strtotime(date('Y-m-d', time()));
        $map['create_time'] = $logTime;
        $result = $this->getInfo($map);
        if ($result instanceof BsStaticsEntity) {
            $this->save($map, ['st_value' => $count, 'update_time' => time()]);
        } else {
            // 插入
            $entity = new BsStaticsEntity();
            $entity->setStKey(BsStaticsParam::EVERY_DAY_ADD_BOOK_PAGE_COUNT);
            $entity->setStValue($count);
            $entity->setCreateTime($logTime);
            $entity->setUpdateTime($logTime);
            $this->add($entity);
        }

    }

}