<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-01 09:48
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace appTest\tp5;

use app\component\tp5\helper\BaseConfigHelper;
use appTest\base\BaseTestCase;

class BaseConfigHelperTest extends BaseTestCase
{

    public function testConfig()
    {
        $config = BaseConfigHelper::initGlobalConfig(3);
        $app_debug = BaseConfigHelper::getConfig('app_debug');
        $this->assertFalse($app_debug);
        $test = BaseConfigHelper::getConfig('test');
        $this->assertEquals('test', $test);
//        $config = Cache::get('by_tp5_g_config');
//        var_dump($config);
//        $this->assertEmpty($config);

    }

}