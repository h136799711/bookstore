<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-05 16:39
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace byTest\encrypt;


use by\component\encrypt\md5v3\DataStructEntity;
use by\component\encrypt\md5v3\Md5V3Transport;
use byTest\base\BaseTestCase;

class Md5V3TransportTest extends BaseTestCase
{

    public function testEntity()
    {
        $entity = new DataStructEntity();
        $entity->setApiVer(101);
        $entity->setNotifyId(1);

        $data = $entity->toArray();
        var_dump($data);

        $transport = new Md5V3Transport($data);

        $this->assertEquals($data, $transport->getEntity()->toArray());

    }

}