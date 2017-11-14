<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-14 14:54
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\command;


use app\component\spider\EchoSpider;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class XiashuSpiderCommand extends Command
{
    protected function initialize(Input $input, Output $output)
    {
        parent::initialize($input, $output);
    }

    protected function configure()
    {
        $this->setName('spider:xia_shu')
            ->addOption('interval', 'i', Option::VALUE_OPTIONAL, 'loop process sleep {interval} seconds', 5)
            ->addOption('limit', 'l', Option::VALUE_OPTIONAL, 'limit', 2)
            ->addOption('duration', 'd', Option::VALUE_OPTIONAL, 'duration {interval} seconds', 300)
            ->setDescription('xiashu.cc spider');
    }

    protected function execute(Input $input, Output $output)
    {
        $interval = $input->getOption('interval');
        $limit = $input->getOption('limit');
        $duration = $input->getOption('duration');
        EchoSpider::newSpider()->init($interval, $limit, $duration)->start();
    }

}