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
use app\component\spider\xia_shu\parser\XiaShuBookPageParser;
use app\component\spider\xia_shu\repo\XiaShuSpiderUrlRepo;
use app\component\spider\xia_shu\XiaShuBookPageSpider;
use app\component\spider\xia_shu\XiaShuBookSpider;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Db;
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
            ->addOption('size', 's', Option::VALUE_OPTIONAL, 'total size', 1000)
            ->addOption('page', 'p', Option::VALUE_OPTIONAL, 'page', 1000)
            ->addOption('cmd', 'c', Option::VALUE_OPTIONAL, 'command type -c 1: url_creator 2: bookSpider', 1)
            ->setDescription('xiashu.cc spider');
    }

    protected function execute(Input $input, Output $output)
    {
        set_time_limit(0);

        $size = $input->getOption('size');
        $page = $input->getOption('page');
        $c = $input->getOption('cmd');
        if ($c == 9) {
            $parse = new XiaShuBookPageParser();
            $bookId = 1;
            $pageNo = 1;
            $url = "https://www.xiashu.cc/1/read_1.html";
            $result = $parse->parse($bookId, $pageNo, $url);
            var_dump($result);
//            $repo = new XiaShuSpiderUrlRepo();
//            $repo->mark('test', 10);
//            sleep(10);
//            $repo->clearMark('test');
//            $parse = new XiaShuBookParser("https://www.xiashu.cc/100");
//            $result = $parse->parse();
//            var_dump($result);
            exit(0);
        }

        if ($c == 1) {
            XiaShuSpiderBookUrlHelper::create();
        } elseif ($c == 2) {
            // 启动书籍爬虫
            $output->info('single threads');
            if (function_exists("posix_getpid")) {
                $pid = posix_getpid();
            } else {
                $pid = rand(0, 999);
            }

            $spider = new XiaShuBookSpider($this->getUniqueId($pid), $size, $page);
            try {
                $spider->mark();
                $spider->start();
                $spider->clearMark();
            } catch (Exception $exception) {
                var_dump($exception->getMessage());
            }
        } elseif ($c == 3) {
            // 启动书页爬虫
            $bookId = 1;
            $spider = new XiaShuBookPageSpider($bookId);
            $spider->start();

        } else {
            $output->error('c= ' . $c);
        }
    }

    protected function logException(Exception $exception)
    {

    }

    protected function getUniqueId($pid = 0)
    {
        return strtolower('p' . $pid . '_' . md5(uniqid('xiashu_', true)));
    }

    protected function runThreads($threads = 10, $interval = 3, $limit = 10)
    {
        echo "\n", 'threads' . $threads;
        $repo = new XiaShuSpiderUrlRepo();
        $count = $repo->count();
        $count = 30;
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
                $name = $this->getUniqueId($pid);
//                try {
                $startId = $offset + $j * $everyChildProcessSize;
                $endId = $startId + $everyChildProcessSize;
                echo "\n", 'children start' . $pid, "startId= " . $startId, " endId=" . $endId, "\n";
                Db::clear();
                Db::connect(config('database'));
                $spiders[$j] = new XiaShuBookSpider($name, $startId, $endId);
                $spiders[$j]->mark();
                $spiders[$j]->start();
                $spiders[$j]->clearMark();
                sleep(3);
                echo "\n", "子线程(" . $pid . ")执行完成", "\n";
//                } catch (Exception $ex) {
//                    var_dump($ex->getTraceAsString());
//                }
                exit(0);
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