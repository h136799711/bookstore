<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-27 18:11
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\picture\config;


use app\component\qiniu\interfaces\QiniuConfigInterface;

class QiniuDefaultConfig implements QiniuConfigInterface
{
    // qiniu ak-sk by hebidu

    public function getAppKey()
    {
        return "DAC3vYkwngqgnInHoStopnFDPZtHwj65nABpXJEV";
    }

    public function getSecretKey()
    {
        return "0J3ctvv0k9vUPh9swe3g0i7MXQW6plEd2pumYWHL";
    }

    public function getDefaultBucket()
    {
        return "img1-qqav-club";
    }

}