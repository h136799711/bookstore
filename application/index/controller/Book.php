<?php

namespace app\index\controller;


use app\component\tp5\controller\BaseController;

class Book extends BaseController
{
    public function detail()
    {
        return $this->fetch();
    }

    public function read()
    {
        $id = $this->post('id', 0);
        
        return $this->fetch();
    }

}
