<?php

if (!defined('ITBOYE_CDN')) {
    define("ITBOYE_CDN", "http://bs.cdn.qqav.club");
}

return [
    'site_url' => 'http://bs.qqav.club',
    // 默认输出类型
    'default_return_type' => 'html',
    'view_replace_str' => [
        '__PUBLIC__' => ROOT_PATH . '/static/' . request()->module() . '',
        '__JS__' => ROOT_PATH . '/static/' . request()->module() . '/js',
        '__CSS__' => ROOT_PATH . '/static/' . request()->module() . '/css',
        '__IMG__' => ROOT_PATH . '/static/' . request()->module() . '/img',
        '__SELF__' => request()->url(),
        '__CDN__' => ITBOYE_CDN,
        '__APP_VERSION__' => time()
    ],
    'session' => [
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => 'itboye_sid',
        // SESSION 前缀
        'prefix' => 'itboye_index',
        // 驱动方式 支持redis memcache memcached
        'type' => '',
        // 是否自动开启 SESSION
        'auto_start' => true
    ],
];