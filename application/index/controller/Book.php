<?php

namespace app\index\controller;


use app\component\bs\entity\BsBookEntity;
use app\component\bs\entity\BsBookPageContentEntity;
use app\component\bs\entity\BsBookPageEntity;
use app\component\bs\entity\BsBookSourceEntity;
use app\component\bs\factory\PageContentLogicFactory;
use app\component\bs\factory\PageContentParserFactory;
use app\component\bs\logic\BsBookLogic;
use app\component\bs\logic\BsBookPageLogic;
use app\component\bs\logic\BsBookSourceLogic;
use app\component\spider\constants\BookSiteIntegerType;
use app\component\spider\xia_shu\repo\XiaShuSpiderBookPageUrlRepo;
use app\component\tp5\controller\BaseController;
use app\component\tp5\helper\StaticHtmlHelper;
use think\Exception;

class Book extends BaseController
{
    /**
     * 书籍详情页面
     * @param $id
     * @return mixed
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
     * 阅读页面
     * @param $id
     * @param int $page_no
     * @param int $fail_read_cnt
     * @return mixed
     * @throws Exception
     */
    public function read($id, $page_no = 1, $fail_read_cnt = 0)
    {
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
        $logic = PageContentLogicFactory::create($sourceType);
        $bookPageContentEntity = $logic->getInfo(['book_id' => $id, 'page_no' => $page_no]);
        if ($bookPageContentEntity instanceof BsBookPageContentEntity) {
            $this->assign('bpc', $bookPageContentEntity->getPageContent());
        } else {
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
                    $pageContentData = ['book_id' => $id, 'page_no' => $page_no, 'page_content' => $data['page_content']];
                    // 插入到书籍内容
                    $logic->add($pageContentData, false);
                    // 插入到书籍内容
                    $pageInfoData = ['source_type' => $sourceType, 'page_title' => $data['page_title'], 'book_id' => $id, 'page_no' => $page_no, 'create_time' => time(), 'update_time' => $data['update_time']];
                    ((new BsBookPageLogic())->addIfNotExist($pageInfoData));
                } else {

                    if ($fail_read_cnt < 5) {
                        $fail_read_cnt++;
                        $page_no++;
                        return $this->read($id, $page_no, $fail_read_cnt);
                    }

                    $this->error('没有该章节内容信息', url('/' . $id));
                }
            } else {
                $this->error('没有该章节内容信息', url('/' . $id));
            }
        }

        $this->assign('book_id', $id);
        $this->assign('pre_page_no', $prePageNo);
        $this->assign('next_page_no', $nextPageNo);
        $pathinfo = $this->request->pathinfo();
        $fetch = $this->fetch();
        StaticHtmlHelper::write($pathinfo, $fetch);
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
        $this->success('操作成功', null, '操作成功');
    }

}
