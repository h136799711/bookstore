<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 时间戳转日期
 * @param $time
 * @param string $format
 * @return false|string
 */
function toDatetime($time, $format = 'Y-m-d H:i:s')
{

    $timezone = intval(cookie('timezone'));
    if ($timezone >= -12 && $timezone <= 12) {
        $time = $timezone * 3600 + $time;
    }
    return date($format, $time);
}


/**
 * 日期转时间戳
 * @param string $strTime
 * @return false|string
 */
function toUnixTimestamp($strTime)
{
    $unixTimestamp = strtotime($strTime);
    $timezone = intval(cookie('timezone'));

    if ($timezone >= -12 && $timezone <= 12) {
        $unixTimestamp = $unixTimestamp - $timezone * 3600;
    }

    return $unixTimestamp;
}