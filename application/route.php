<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    ':id$' => [
        'book/detail',
        ['method' => 'get'],
        ['id' => '\d+']
    ],
    ':id/[:page_no]$' => [
        'book/read',
        ['method' => 'get', 'ext' => 'html'],
        ['id' => '\d+', 'page_no' => '\d+']
    ],
    ':id/x/[:page_no]$' => [
        'book/read',
        ['method' => 'post', 'ext' => 'html'],
        ['id' => '\d+', 'page_no' => '\d+']
    ]

];
