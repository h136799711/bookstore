<?php

namespace app\index\controller;


use app\component\bs\logic\BsBookCategoryLogic;
use app\component\bs\logic\BsBookLogic;
use app\component\spider\xia_shu\repo\XiaShuAuthorRepo;
use app\component\spider\xia_shu\repo\XiaShuBookRepo;
use app\component\tp5\controller\BaseController;
use app\component\tp5\helper\RequestHelper;
use by\component\paging\vo\PagingParams;

class Index extends BaseController
{
    public function info()
    {
        $bookCount = (new XiaShuBookRepo())->count();
        $authorCount = (new XiaShuAuthorRepo())->count();

        $this->assign('authorCount', $authorCount);
        $this->assign('bookCount', $bookCount);
        return $this->fetch();
    }

    public function index()
    {
        $books = (new XiaShuBookRepo())->limit(0, 100)->select();

        $this->assign('books', $books);
        return $this->fetch();
    }

    /**
     * 搜索
     */
    public function search()
    {
        $p = RequestHelper::post('p', 0);
        $map = [];
        $logic = new BsBookLogic();
        $pagingParams = new PagingParams();
        $pagingParams->setPageIndex($p);
        $pagingParams->setPageSize(20);
        $result = $logic->query($map, $pagingParams, "id asc");
        var_dump($result);

        return $this->fetch();
    }

    private function getCategory()
    {
        $logic = new BsBookCategoryLogic();
        $result = $logic->queryNoPaging([], 'id desc,type desc');
        $this->assign('bs_cate', $result);
    }
}
