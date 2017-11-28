<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-28 10:45
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\spider\xia_shu;

use app\component\bs\entity\BsBookEntity;
use app\component\bs\logic\BsBookLogic;
use app\component\picture\entity\BsPictureEntity;
use app\component\picture\helper\PictureDownloadHelper;
use app\component\string_extend\StringHelper;
use by\component\paging\vo\PagingParams;

/**
 * Class XiaShuCoverSpider
 * 图片下载爬虫
 * @package app\component\spider\xia_shu
 */
class XiaShuCoverSpider
{
    private $xiashuNocover1 = "https://pic.xiashu.cc/image/nocover.jpg";
    private $xiashuNocover2 = "https://img.xiashu.cc/image/nocover.jpg";

    private $nocover = "http://img1.8raw.com/cover/nocover.jpg";

    public function downloadThumbnail($size = 1)
    {
        $list = $this->queryThumbnial($size);
        $update = [];
        foreach ($list as $vo) {
            if ($vo instanceof BsBookEntity) {
                $id = $vo->getId();
                echo 'book id = ' . $id, "\n";
                $thumbnail = $vo->getThumbnail();
                if ($thumbnail == $this->xiashuNocover1 || $thumbnail == $this->xiashuNocover2) {
                    // 无需下载
                    $thumbnail = $this->nocover;
                } else {
                    //
                    $path = strval($id);
                    if (strlen($path) < 5) {
                        $path = "0";
                    } else {
                        $path = ceil($id / 10000);
                    }
                    $saveName = "cover/" . $path . '/' . $id;
                    $result = $this->downloadPic($thumbnail, $saveName);

                    if ($result->isSuccess()) {
                        $data = $result->getData();
                        if ($data instanceof BsPictureEntity) {
                            $thumbnail = $data->getUrl();
                        } else {
                            $thumbnail .= '?op=fail';
                        }
                    } else {
                        echo StringHelper::utf8ToGbk($result->getMsg()), "\n";
                        $thumbnail .= '?op=error';
                    }
                }
                array_push($update, ['id' => $id, 'thumbnail' => $thumbnail]);
            }
        }
        (new BsBookLogic())->saveAll($update);
    }

    public function queryThumbnial($size = 1)
    {
        $logic = new BsBookLogic();
        $map = [
            'thumbnail' => ['like', '%xiashu.cc%']
        ];
        $paging = new PagingParams();
        $paging->setPageSize($size);
        $paging->setPageIndex(1);
        $result = $logic->query($map, $paging, 'id asc', 'id,thumbnail');
        $list = $result['list'];
        return $list;
    }

    private function downloadPic($picUrl, $saveName)
    {
        echo 'download ' . $picUrl . ' and save as ' . $saveName, "\n";
        return PictureDownloadHelper::uploadToQiniu($picUrl, $saveName);
    }
}