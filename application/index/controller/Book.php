<?php

namespace app\index\controller;


use app\component\bs\entity\BsBookEntity;
use app\component\bs\entity\BsBookPageEntity;
use app\component\bs\logic\BsBookLogic;
use app\component\bs\logic\BsBookPageLogic;
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
        $bookEntity = (new BsBookLogic())->getInfo(['id' => $id]);
        if ($bookEntity instanceof BsBookEntity) {
            $this->assign('book', $bookEntity);
        } else {
            $this->error('没有该书籍信息', url('index/index/index'));
        }


        $prePageNo = $page_no - 1 > 0 ? $page_no - 1 : $page_no;
        $nextPageNo = $page_no + 1;

        $bookPageEntity = (new BsBookPageLogic())->getInfo(['book_id' => $id, 'page_no' => $page_no]);
        if ($bookPageEntity instanceof BsBookPageEntity) {
            $this->assign('page', $bookPageEntity);
        } else {
            $this->error('没有该章节信息', url($id));
        }


        $this->assign('book_id', $id);
        $this->assign('pre_page_no', $prePageNo);
        $this->assign('next_page_no', $nextPageNo);

        return $this->fetch();
    }

}
