<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-16 11:45
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\spider\xia_shu\helper;


use by\component\spider\xia_shu\entity\XiaShuSpiderBookUrlEntity;
use by\component\spider\xia_shu\repo\XiaShuSpiderUrlRepo;
use by\infrastructure\helper\Object2DataArrayHelper;

class XiaShuSpiderBookUrlHelper
{
    public static function getBookPageId($url)
    {
        $regex = "/https:\/\/www.xiashu.cc\/(\d*)/i";
        $match = [];
        preg_match($regex, $url, $match);
        if (is_array($match) && count($match) == 2) {
            return intval($match[1]);
        }

        return 0;
    }

    public static function getBookPageNo($url)
    {
        $regex = "/https:\/\/www.xiashu.cc\/(\d*)\/read_(\d*)/i";
        $match = [];
        preg_match($regex, $url, $match);
        if (is_array($match) && count($match) == 3) {
            var_dump($match);
            return intval($match[2]);
        }

        return 0;
    }

    /**
     * 创建书籍链接
     * @param int $size
     */
    public static function create($size = 100000)
    {
        $tpl = "https://www.xiashu.cc/";

        $repo = new XiaShuSpiderUrlRepo();
        $maxId = $repo->order('id', 'desc')->find();
        $start = 0;
        if ($maxId) {
            $url = str_replace($tpl, '', $maxId->getAttr('url'));
            $start = intval($url);
        }
        echo 'start= ' . $start;
        $list = [];
        $now = time();
        for ($i = $start + 1; $i <= $size + $start; $i++) {
            $url = $tpl . $i;
            $entity = new XiaShuSpiderBookUrlEntity($url);
            $entity->setCreateTime($now);
            $entity->setUpdateTime($now);
            array_push($list, Object2DataArrayHelper::getDataArrayFrom($entity));
            if (($i % 500) == 0) {
                $repo->insertAll($list);
                $list = [];
                $now = time();
            }
        }

        if (count($list) > 0) {
            $repo->insertAll($list);
        }

    }
}