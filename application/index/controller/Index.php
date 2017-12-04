<?php

namespace app\index\controller;


use app\component\bs\logic\BsBookPageLogic;
use app\component\bs\logic\BsStaticsLogic;
use app\component\spider\xia_shu\repo\XiaShuAuthorRepo;
use app\component\spider\xia_shu\repo\XiaShuBookRepo;
use app\component\tp5\controller\BaseController;
use think\Cache;

class Index extends BaseController
{

    public function index()
    {
        if ($this->request->isPost()) {
            $username = $this->param('username', '');
            $password = $this->param('password', '');
            if ($username == 'hebidu' && $password == '136799711') {
                session('user', $username);
                $this->success('登录成功','index/book/search');
            } else {
                $this->error('登录失败');
            }
        } else {
            if (session('?user') && session('user') == 'hebidu') {
                $this->redirect('index/book/search');
            }
            return $this->fetch();
        }
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


}
