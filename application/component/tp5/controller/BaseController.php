<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-01 10:35
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\tp5\controller;


use by\component\paging\vo\PagingParams;
use by\component\tp5\helper\RequestHelper;
use by\infrastructure\helper\Object2DataArrayHelper;
use think\Controller;
use think\Request;

class BaseController extends Controller
{

    public function getPagingParams()
    {
        $p = $this->param('page', 0);
        $pagingParams = new PagingParams();
        $pagingParams->setPageIndex($p);
        return $pagingParams;
    }

    public function setParamsEntity($params)
    {
        Object2DataArrayHelper::setData($params, $this->param());
    }

    public function param($key = '', $default = '', $emptyErrMsg = '')
    {
        if ($key === '') {
            return Request::instance()->param();
        }

        $callResult = RequestHelper::param($key, $default, $emptyErrMsg);
        if (!$callResult->isSuccess()) {
            $this->error($callResult->getMsg());
        }
        return $callResult->getData();
    }

    public function _empty()
    {
        return $this->fetch(APP_PATH . 'html/404/web/index.html');
    }
}