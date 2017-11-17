<?php

namespace app\index\controller;


use app\component\spider\xia_shu\repo\XiaShuAuthorRepo;
use app\component\spider\xia_shu\repo\XiaShuBookRepo;
use think\Controller;

class Index extends Controller
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
}
