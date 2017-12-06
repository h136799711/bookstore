<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-06 14:45
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\tp5\taglib;

use think\template\TagLib;

/**
 * Class Demo
 * tp5 手册的demo 略微改造
 * @package by\component\tp5\taglib
 */
class Datetime extends TagLib
{

    /**
     * 定义标签列表
     */
    protected $tags = [
        '_local' => ['attr' => 'time,format', 'close' => 0], //闭合标签
    ];

    /**
     * 本地时间, 传入的time必须是UTC时间
     * @param $tag
     * @return string
     */
    public function tag_local($tag)
    {
        $format = empty($tag['format']) ? 'Y-m-d H:i:s' : $tag['format'];
        $time = empty($tag['time']) ? time() : $tag['time'];

        $parse = '<?php ';
        $parse .= 'echo toDatetime(' . $time . ',"' . $format . '");';
        $parse .= ' ?>';
        return $parse;
    }

}