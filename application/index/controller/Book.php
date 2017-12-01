<?php

namespace app\index\controller;


use app\component\tp5\controller\BaseController;

class Book extends BaseController
{
    /**
     * 书籍详情页面
     * @param $id
     * @return mixed
     */
    public function detail($id)
    {
        var_dump($id);
        return $this->fetch();
    }

    /**
     * 阅读页面
     * @param $id
     * @param $page_no
     * @return mixed
     */
    public function read($id, $page_no = 1)
    {
        var_dump($id);
        var_dump($page_no);

        return $this->fetch();
    }

}
