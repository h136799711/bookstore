<?php

namespace app\index\controller;


use app\component\bs\logic\BsBookCategoryLogic;
use app\component\bs\logic\BsBookLogic;
use app\component\bs\params\BsBookSearchParams;
use app\component\spider\xia_shu\repo\XiaShuAuthorRepo;
use app\component\spider\xia_shu\repo\XiaShuBookRepo;
use app\component\tp5\controller\BaseController;
use think\paginator\driver\Bootstrap;

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
        $result = $logic->queryWithPagingHtml($map, $pagingParams, "id asc");
        if ($result instanceof Bootstrap) {
            $this->assign('bs_book_list', $result);
        }

        return $this->fetch();
    }

    public function category()
    {
        $logic = new BsBookCategoryLogic();
        $result = $logic->queryNoPaging([], 'type desc, sort desc');
        $this->assign('bs_cate', $result);
    }
}
