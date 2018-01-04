<?php

namespace by\index\controller;


use by\component\bs\entity\BsBookEntity;
use by\component\bs\entity\BsBookPageEntity;
use by\component\bs\entity\BsBookSourceEntity;
use by\component\bs\factory\PageContentParserFactory;
use by\component\bs\logic\BsBookCategoryLogic;
use by\component\bs\logic\BsBookLogic;
use by\component\bs\logic\BsBookPageLogic;
use by\component\bs\logic\BsBookSourceLogic;
use by\component\bs\params\BsBookSearchParams;
use by\component\spider\constants\BookSiteIntegerType;
use by\component\spider\xia_shu\repo\XiaShuSpiderBookPageUrlRepo;
use by\component\tp5\helper\StaticHtmlHelper;
use think\Exception;
use think\paginator\driver\Bootstrap;

class Book extends BaseIndexController
{
    /**
     * 页面版本
     */
    const READ_PAGE_VERSION = 3;

    /**
     * @return mixed
     * @throws Exception
     * @throws \think\exception\DbException
     */
    public function search()
    {

        $bookCount = (new BsBookPageLogic())->getValidBookCount();
        $this->assign('book_count', $bookCount);

        // 类目信息
        $this->category();
        // 查询参数
        $params = new BsBookSearchParams();
        $this->setParamsEntity($params);
        // 分页信息
        $pagingParams = $this->getPagingParams();
        $pagingParams->setPageSize(20);
        $map = $params->getMap();
        $logic = new BsBookLogic();
        $result = $logic->queryWithPagingHtml($map, $pagingParams, "id asc", $params->toArray());
        if ($result instanceof Bootstrap) {
            $this->assign('bs_book_list', $result);
        }

        $this->assign('book_category_id', $params->getBookCategoryId());
        return $this->fetch();
    }

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function category()
    {
        $logic = new BsBookCategoryLogic();
        $result = $logic->queryNoPaging([], 'type desc, sort desc');
        $this->assign('bs_cate', $result);
    }

    /**
     * 书籍详情页面
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detail($id)
    {

        $bookEntity = (new BsBookLogic())->getInfo(['id' => $id]);
        if ($bookEntity instanceof BsBookEntity) {
            $this->assign('book', $bookEntity);
        } else {
            $this->error('没有该书籍信息', url('index/index/index'));
        }

        $result = (new BsBookPageLogic())->queryNoPaging(['book_id' => $id, 'source_type' => BookSiteIntegerType::XIA_SHU_BOOK_SITE], 'page_no asc', 'book_id, page_no,page_title,update_time');

        if (is_array($result)) {
            $this->assign('page_count', count($result));
            $this->assign('book_page_list', $result);
        } else {
            $this->assign('page_count', 0);
            $this->assign('book_page_list', []);
        }

        return $this->fetch();
    }

    /**
     * 检查静态页面版本
     * @param $bookId
     * @param $page_no
     */
    private function checkStaticHtmlVersion($bookId, $page_no)
    {
        $pageVersion = $this->param('page_version', 0);

        if ($pageVersion < self::READ_PAGE_VERSION) {
//            echo '当前版本不对';
            // 当前的缓存版本
            $fileName = $bookId.'/'.$page_no.'.html';
            if (file_exists($fileName)) {
//                echo('存在文件'.$fileName);
                @unlink($fileName);
//                var_dump('删除文件'.$fileName);
            } else {
//                echo('不存在文件'.$fileName);
            }
        }
    }

    /**
     * 阅读页面
     * @param $id
     * @param int $page_no
     * @param int $fail_read_cnt
     * @return mixed
     * @throws Exception
     */
    public function read($id, $page_no = 1, $fail_read_cnt = 0)
    {
        if ($fail_read_cnt === 0) {
            // 只有第一次的时候进行检查
            $this->checkStaticHtmlVersion($id, $page_no - 1);
        }
        $noReturn = $this->param('no_return', 0);

        // 模板版本 涉及重新生成缓存页面
        $this->assign('page_version', self::READ_PAGE_VERSION);
        set_time_limit(6*10);
        $sourceType = $this->param('source_type', BookSiteIntegerType::XIA_SHU_BOOK_SITE);
        $bookEntity = (new BsBookLogic())->getInfo(['id' => $id]);
        if ($bookEntity instanceof BsBookEntity) {
            $this->assign('book', $bookEntity);
        } else {
            $this->error('没有该书籍信息', url('index/index/index'));
        }


        $prePageNo = $page_no - 1 > 0 ? $page_no - 1 : $page_no;
        $nextPageNo = $page_no + 1;


        $bookPageEntity = (new BsBookPageLogic())->getInfo(['book_id' => $id, 'page_no' => $page_no, 'source_type' => $sourceType]);
        if ($bookPageEntity instanceof BsBookPageEntity) {
            $this->assign('page', $bookPageEntity);
        } else {
            $this->assign('page', new BsBookPageEntity());
//            $this->error('没有该章节信息', url('/' . $id));
        }

        // 从已保存内容的数据表中取数据
//        $logic = PageContentLogicFactory::create($sourceType);
//        $bookPageContentEntity = $logic->getInfo(['book_id' => $id, 'page_no' => $page_no]);
//        if ($bookPageContentEntity instanceof BsBookPageContentEntity) {
//            $this->assign('bpc', $bookPageContentEntity->getPageContent());
//        } else {
            // 向源网站读取
            $bookSourceEntity = (new BsBookSourceLogic())->getInfo(['book_id' => $id, 'book_source_type' => $sourceType]);

            if ($bookSourceEntity instanceof BsBookSourceEntity) {
                $parser = PageContentParserFactory::create($sourceType);
                $sourceBookId = $bookSourceEntity->getSourceBookId();
                $this->assign('page_url', PageContentParserFactory::getBookPageReadUrl($sourceType, $sourceBookId, $page_no));
                $callResult = $parser->parse($sourceBookId, $page_no);
                if ($callResult->isSuccess()) {
                    $data = $callResult->getData();
                    $this->assign('bpc', $data['page_content']);
//                    $pageContentData = ['book_id' => $id, 'page_no' => $page_no, 'page_content' => $data['page_content']];
                    // 插入到书籍内容
//                    $logic->add($pageContentData, false);
                    // 插入到书籍内容
                    $pageInfoData = ['source_type' => $sourceType, 'page_title' => $data['page_title'], 'book_id' => $id, 'page_no' => $page_no, 'create_time' => time(), 'update_time' => $data['update_time']];
                    ((new BsBookPageLogic())->addIfNotExist($pageInfoData));
                } else {

                    if ($fail_read_cnt < 3) {
                        $fail_read_cnt++;
                        $page_no++;
                        return $this->read($id, $page_no, $fail_read_cnt);
                    }

                    $this->error('很抱歉！
此章节已经失效，系统无法匹配正确章节，稍后将自动跳到书籍信息页面。', url('/' . $id));
                }
            } else {
                $this->error('很抱歉！
此章节已经失效，系统无法匹配正确章节，稍后将自动跳到书籍信息页面。', url('/' . $id));
            }
//        }

        $this->assign('book_id', $id);
        $this->assign('pre_page_no', $prePageNo);
        $this->assign('next_page_no', $nextPageNo);
        $pathInfo = $this->request->pathinfo();
        $pathInfo = str_replace("x/","", $pathInfo);
        $fetch = $this->fetch();
        StaticHtmlHelper::write($pathInfo, '<!-- Version '.self::READ_PAGE_VERSION.' -->'.$fetch);
        if ($noReturn) {
            return $this->display(json_encode($this->param()));
        }
        return $fetch;
    }

    public function priority_up()
    {
        $id = $this->param('id', 0);
        $repo = (new XiaShuSpiderBookPageUrlRepo());
        try {
            $result = $repo->where(['book_id' => $id])->fetchSql(false)->setInc('priority', 1);
        } catch (Exception $e) {
            $this->error($e->getMessage(), null, '操作成功');
        }
        $this->success('操作成功', null, '操作失败');
    }

    public function set_over()
    {
        $id = $this->param('id', 0);
        $repo = (new XiaShuSpiderBookPageUrlRepo());
        try {
            $result = $repo->save(['is_spider_over'=>1], ['book_id' => $id]);
            (new BsBookLogic())->save(['id' => $id],['state'=>BsBookEntity::STATE_END, 'update_time'=>time()]);
        } catch (Exception $e) {
            $this->error($e->getMessage(), null, '操作失败');
        }
        $this->success('操作成功', null, '操作成功');
    }

}
