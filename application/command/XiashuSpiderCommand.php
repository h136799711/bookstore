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
use app\component\spider\xia_shu\repo\XiaShuSpiderBookPageUrlRepo;
use app\component\spider\xia_shu\XiaShuBookPageSpider;
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
            ->addOption('size', 's', Option::VALUE_OPTIONAL, 'total size', 1)
            ->addOption('page', 'p', Option::VALUE_OPTIONAL, 'page', 1000)
            ->addOption('save_text', 't', Option::VALUE_OPTIONAL, 'should save the content to  file, default is no. 0 for no or 1 for yes', 0)
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
            $repo = new XiaShuSpiderBookPageUrlRepo();
            $repo->saveIfUpdateUrl(['book_id' => 173834], 'https://www.xiashu.cc/176010/read_1.html');

//            $parser = new XiaShuBookStateParser("https://www.xiashu.cc/1");
//            $ret = $parser->parse();
//            var_dump($ret);

//            $spider = new XiaShuNewBookSpider('');
//            $ret = $spider->curl_request("https://www.xiashu.cc/175930");
//            var_dump($ret);
//            $spider->start();
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

            $save_text = $input->getOption('save_text');
            // 启动书页爬虫
            $bookRepo = new XiaShuSpiderBookPageUrlRepo();
            $ret = $bookRepo->getValidSpiderBookPageUrl($size);
            if ($ret->isSuccess()) {
                foreach ($ret->getData() as $book) {
                    $bookId = $book['book_id'];
                    $sourceBookNo = XiaShuSpiderBookUrlHelper::getBookPageId($book['url']);
                    echo 'book url ' . $book['url'], "\n";
                    if ($sourceBookNo == 0) {
                        echo 'get source book no fail from ' . $book['url'], "\n";
                        continue;
                    }
                    echo 'read source_book_no ' . $sourceBookNo . ' book id ' . $bookId, "\n";
                    $spider = new XiaShuBookPageSpider($bookId, $sourceBookNo);
                    if ($save_text == 1) {
                        $spider->ifSaveText = true;
                    }
                    $spider->start();
                }
            } else {
                var_dump($ret->getMsg());
            }

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