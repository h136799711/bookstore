<?php

namespace app\index\controller;


use app\component\bs\logic\BsBookCategoryLogic;
use app\component\bs\logic\BsBookLogic;
use app\component\bs\logic\BsBookPageLogic;
use app\component\bs\logic\BsStaticsLogic;
use app\component\bs\params\BsBookSearchParams;
use app\component\spider\xia_shu\repo\XiaShuAuthorRepo;
use app\component\spider\xia_shu\repo\XiaShuBookRepo;
use app\component\tp5\controller\BaseController;
use think\Cache;
use think\paginator\driver\Bootstrap;

class Index extends BaseController
{

    public function index()
    {
        return $this->fetch();
    }

    public function info()
    {
        $bookCount = Cache::get('book_count');
        $authorCount  = Cache::get('author_count');
        if (empty($bookCount) || empty($authorCount)) {
            $bookCount = (new XiaShuBookRepo())->count();
            $authorCount = (new XiaShuAuthorRepo())->count();

            Cache::set('author_count', $authorCount, 1800);
            Cache::set('book_count', $bookCount, 1800);
        }

        $this->assign('authorCount', $authorCount);
        $this->assign('bookCount', $bookCount);
        $bookCount = (new BsBookPageLogic())->getValidBookCount();
        $this->assign('validBookCount', $bookCount);

        $logic = new BsStaticsLogic();
        $map['create_time'] = ['gt', time() - 30*24*3600];

        $result = $logic->queryNoPaging($map, "create_time desc");
        $this->assign('list', $result);
        return $this->fetch();
    }

    /**
     * 搜索
     */
    public function search()
    {

        $bookCount = (new BsBookPageLogic())->getValidBookCount();
        $this->assign('book_count', $bookCount);

        // 类目信息
        $this->category();
        // 查询参数
        $params = new BsBookSearchParams();
        $this->setParamsEntity($params);
        // 分页信息
        $pagingParams = $this->getPagingParams();
        $pagingParams->setPageSize(20);
        $map = $params->getMap();
        $logic = new BsBookLogic();
        $result = $logic->queryWithPagingHtml($map, $pagingParams, "id asc", $params->toArray());
        if ($result instanceof Bootstrap) {
            $this->assign('bs_book_list', $result);
        }

        $this->assign('book_category_id', $params->getBookCategoryId());
        return $this->fetch();
    }

    public function category()
    {
        $logic = new BsBookCategoryLogic();
        $result = $logic->queryNoPaging([], 'type desc, sort desc');
        $this->assign('bs_cate', $result);
    }

}
