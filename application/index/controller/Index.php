<?php

namespace by\index\controller;


use by\component\bs\logic\BsBookPageLogic;
use by\component\bs\logic\BsStaticsLogic;
use by\component\spider\xia_shu\repo\XiaShuAuthorRepo;
use by\component\spider\xia_shu\repo\XiaShuBookRepo;
use by\component\tp5\controller\BaseController;
use think\Cache;

class Index extends BaseController
{

    public function index()
    {
        //给模版给以一个当前时间戳的值
        $this->assign('demo_time', $this->request->time());
        if ($this->request->isPost()) {
            $username = $this->param('username', '');
            $password = $this->param('password', '');
            if ($username == 'hebidu' && $password == '136799711') {
                session('user', $username);
                cookie('user', $username);
                $this->success('登录成功','index/book/search');
            } else {
                $this->error('登录失败');
            }
        } else {
            if (cookie('?user') && cookie('user') == 'hebidu') {
                session('user', cookie('user'));
                $this->redirect('index/book/search');
            }
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
