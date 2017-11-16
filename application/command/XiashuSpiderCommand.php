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


use app\component\spider\xia_shu\helper\XiaShuSpiderBookUrlHelper;
use by\component\xia_shu\XiaShuBookSpider;
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
            ->addOption('cmd', 'c', Option::VALUE_OPTIONAL, 'command type -c 1: url_creator 2: bookSpider', 1)
            ->addOption('threads', 't', Option::VALUE_OPTIONAL, 'multiple threads enable, when pcntl enable', 3)
            ->addOption('interval', 'i', Option::VALUE_OPTIONAL, 'i loop process sleep {interval} seconds', 5)
            ->addOption('limit', 'l', Option::VALUE_OPTIONAL, '-l limit', 2)
            ->addOption('duration', 'd', Option::VALUE_OPTIONAL, '-d duration {interval} seconds', 300)
            ->setDescription('xiashu.cc spider');
    }

    protected function execute(Input $input, Output $output)
    {
        set_time_limit(0);
        $threads = $input->getOption('threads');
        $interval = $input->getOption('interval');
        $c = $input->getOption('cmd');
        $limit = $input->getOption('limit');
        $duration = $input->getOption('duration');
//        EchoSpider::newSpider()->init($interval, $limit, $duration)->start();
        if ($c == 1) {
            XiaShuSpiderBookUrlHelper::create();
        } elseif ($c == 2) {
            // 启动书籍爬虫
            if (function_exists('pcntl_fork')) {
                $output->info('multiple threads');
                $this->runThreads($threads, $interval, $limit, $duration);
            } else {
                $output->info('single threads');
                XiaShuBookSpider::newSpider()->init($interval, $limit, $duration)->start();
            }
        } else {
            $output->error('c= ' . $c);
        }
    }

    protected function runThreads($threads = 3, $interval = 3, $limit = 10, $duration = 600)
    {
        // 如果存在 pcntl 则采用多进程进行处理
        for ($j = 0; $j < $threads; $j++) {
            $pid = pcntl_fork();
            if ($pid == -1) {
                //创建失败咱就退出呗,没啥好说的
                die('could not fork');
            } else {
                if ($pid) {
                    // 从这里开始写的代码是父进程的,因为写的是系统程序,记得退出的时候给个返回值
                    echo "father";
                    $i = 3;
                    while ($i--) {
                        echo 'father' . $i;
                        usleep(50);
                    }
                    exit(0);
                } else {
                    echo "children";
                    $i = 3;
                    while ($i--) {
                        echo 'children' . $i;
                        usleep(50);
                    }
                    // 从这里开始写的代码都是在新的进程里执行的,同样正常退出的话,最好也给一个返回值
                    exit(0);
                }
            }
        }
    }

}