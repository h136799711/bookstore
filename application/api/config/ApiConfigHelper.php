<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-05 16:04
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\api\config;


use by\component\tp5\entity\ConfigEntity;
use by\component\tp5\helper\BaseConfigHelper;
use by\component\tp5\logic\ConfigLogic;
use think\Cache;

class ApiConfigHelper extends BaseConfigHelper
{
    const API_CONFIG_GROUP_ID = 6;

    /**
     * @param int $cacheTime
     * @return array|bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static public function initApiConfig($cacheTime = 86400)
    {
        $config = Cache::get('by_tp5_g_api_config');
        if ($config === false) {
            $map = array('group'=>self::API_CONFIG_GROUP_ID);
            $fields = 'type,name,value';
            $api = new ConfigLogic();
            $result = $api->queryNoPaging($map, false, $fields);
            $config = array();

            if (is_array($result)) {
                foreach ($result as $cfg) {
                    if ($cfg instanceof ConfigEntity) {
                        $config[$cfg->getName()] = self::_parse($cfg->getType(), $cfg->getValue());
                    }
                }
            }

            // 缓存配置$cacheTime秒
            Cache::set("by_tp5_g_api_config", $config, $cacheTime);
        }

        return $config;
    }
}