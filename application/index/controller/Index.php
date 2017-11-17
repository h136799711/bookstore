<?php

namespace app\index\controller;


use app\component\spider\xia_shu\repo\XiaShuBookRepo;
use think\Controller;

class Index extends Controller
{

    public function index()
    {
        $books = (new XiaShuBookRepo())->limit(0, 100)->select();

        $this->assign('books', $books);
        return $this->fetch();
    }
}
