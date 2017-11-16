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
use app\component\spider\xia_shu\repo\XiaShuSpiderUrlRepo;
use app\component\spider\xia_shu\XiaShuBookSpider;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Exception;

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
        if ($c == 9) {
            $repo = new XiaShuSpiderUrlRepo();
            $count = $repo->count();
            $everyChildProcessSize = ceil($count / $threads);
            var_dump($everyChildProcessSize);
            exit(0);
        }

        if ($c == 1) {
            XiaShuSpiderBookUrlHelper::create();
        } elseif ($c == 2) {
            // 启动书籍爬虫
            if (function_exists('pcntl_fork')) {
                $output->info('multiple threads');
                while (true) {
                    $this->runThreads($threads, $interval, $limit);
                    sleep(600);
                }
            } else {
                $output->info('single threads');
            }
        } else {
            $output->error('c= ' . $c);
        }
    }

    protected function getUniqueId($pid = 0)
    {
        return strtolower('p' . $pid . '_' . md5(uniqid('xiashu_', true)));
    }

    protected function runThreads($threads = 10, $interval = 3, $limit = 10)
    {
        echo "\n", 'threads' . $threads;
//        $repo = new XiaShuSpiderUrlRepo();
//        $count = $repo->count();
        $count = 10;
        // TODO: 获取当前总共待处理数量 n ,目前不大于20万
        // TODO: 分配给最多10个进程进行处理 n / 10 <= 20000
        // TODO: 每个子进程处理不大于2万个链接
        $everyChildProcessSize = ceil($count / $threads);
        $children = array();
        $spiders = [];
        $offset = 171;
        // 如果存在 pcntl 则采用多进程进行处理
        for ($j = 0; $j < $threads; $j++) {
            $children[$j] = pcntl_fork();
            $pid = $children[$j];
            if ($pid == -1) {
                // 创建失败咱就退出呗,没啥好说的
                die('could not fork');
            } elseif ($pid == 0) {
                // 子进程处理data数组
                $pid = posix_getpid();
                echo "\n", $j . 'children sleep start' . $pid;
                $name = $this->getUniqueId($pid);
                try {
                    $spiders[$j] = new XiaShuBookSpider($name, $offset + $j * $everyChildProcessSize, $offset + ($j + 1) * $everyChildProcessSize);
                    $spiders[$j]->mark();
                    sleep(10);
//                $spiders[$j]->start();
                    $spiders[$j]->clearMark();
                } catch (Exception $ex) {
                    var_dump($ex->getTraceAsString());
                }
                exit(0);
            } else {
                echo 'father';
            }

        }


        while (count($children) > 0) {
            foreach ($children as $key => $pid) {
                $res = pcntl_waitpid($pid, $status, WNOHANG);

                //-1代表error, 大于0代表子进程已退出,返回的是子进程的pid,非阻塞时0代表没取到退出子进程
                if ($res == -1 || $res > 0) {
                    unset($children[$key]);
                }
            }

            sleep(1);
        }

        echo "\n", "all process exited", "\n";
    }

}