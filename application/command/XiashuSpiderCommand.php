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
use app\component\spider\xia_shu\XiaShuBookPageSpider;
use app\component\spider\xia_shu\XiaShuBookSpider;
use app\component\spider\xia_shu\XiaShuNewBookSpider;
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
            $spider = new XiaShuNewBookSpider('');
//            $ret = $spider->curl_request("https://www.xiashu.cc/175930");
//            var_dump($ret);
            $spider->start();
//            $parse = new XiaShuBookPageParser();
//            $bookId = 1;
//            $pageNo = 1;
//            $url = "https://www.xiashu.cc/1/read_1.html";
//            $result = $parse->parse($bookId, $pageNo, $url);
//            var_dump($result);
//            $repo = new XiaShuSpiderUrlRepo();
//            $repo->mark('test', 10);
//            sleep(10);
//            $repo->clearMark('test');
//            $parse = new XiaShuBookParser("https://www.xiashu.cc/100");
//            $result = $parse->parse();
//            var_dump($result);
            exit(0);
        }
        $startTime = microtime(true);
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
            // TODO 获取所有书籍
            // TODO 书籍状态不等于已爬取完成状态
            // TODO 最新爬取时间不是当天的书
            // TODO 创建一个视图
//            $bookRepo = new XiaShuBookRepo();
//            while (true){

            $bookId = 1;
            $spider = new XiaShuBookPageSpider($bookId);
            $spider->start();
//            }
        } else {
            $output->error('c= ' . $c);
        }
        $startTime = microtime(true) - $startTime;
        $output->info('cost time=' . $startTime);
    }

    protected function getUniqueId($pid = 0)
    {
        return strtolower('p' . $pid . '_' . md5(uniqid('xiashu_', true)));
    }

    protected function logException(Exception $exception)
    {

    }

    /**
     * 开启书籍页面下载-每次一本书
     */
    protected function startBookSpider()
    {
        $latestBookId = $this->getLatest();

    }

}