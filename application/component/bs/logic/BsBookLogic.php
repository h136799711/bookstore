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


use app\component\bs\params\BsBookSearchParams;
use app\component\tp5\logic\BaseLogic;
use by\component\paging\vo\PagingParams;

class BsBookLogic extends BaseLogic
{
    /**
     * 搜索书籍
     * @param BsBookSearchParams $searchParams
     * @param PagingParams $pagingParams
     * @return array
     */
    public function search(BsBookSearchParams $searchParams, PagingParams $pagingParams)
    {
        $map = [];
        if (!empty($searchParams->getBookName())) {
            $map['title'] = ['like', '%' . $searchParams->getBookName() . '%'];
        }
        return $this->query($map, $pagingParams, 'update_time desc');
    }
}