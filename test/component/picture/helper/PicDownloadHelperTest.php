<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-27 15:55
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace byTest\component\picture\helper;

use by\component\picture\helper\PictureDownloadHelper;
use byTest\base\BaseTestCase;

/**
 * Class PicDownloadHelper
 * @covers PictureDownloadHelper
 * @package byTest\component\picture\helper
 */
class PicDownloadHelperTest extends BaseTestCase
{
//    /**
//     * @covers PictureDownloadHelper::getImage()
//     */
//    public function testGetImage()
//    {
//        $url = "https://img.xiashu.cc/cover/0/2.jpg";
//        $saveDir = "./public/cover";
//        $fileName = uniqid('file_');
//        // ['file_name' => $filename, 'save_path' => $save_dir . $filename]
//        $result = PictureDownloadHelper::getImage($url, $saveDir, $fileName, 1);
//        echo StringHelper::utf8ToGbk($result->getMsg());
//        $this->assertTrue($result->isSuccess());
//        if ($result->isSuccess()) {
//            $data = $result->getData();
//            $this->assertTrue(is_array($data));
//            $this->assertArrayHasKey('file_name', $data);
//            $this->assertArrayHasKey('save_path', $data);
//            var_dump($data);
//            $savePath = $data['save_path'];
//            $fileExists = file_exists($savePath);
//            $this->assertTrue($fileExists);
//            unlink($savePath);
//            $fileExists = file_exists($savePath);
//            $this->assertFalse($fileExists);
//        }
//
//    }

    /**
     * @covers PictureDownloadHelper::downloadAndSaveTo()
     */
//    public function testDownloadAndSaveTo()
//    {
//        $url = "https://img.xiashu.cc/cover/0/2.jpg";
//        $saveDir = "./public/cover";
//        // ['file_name' => $filename, 'save_path' => $save_dir . $filename]
//        $result = PictureDownloadHelper::downloadAndSaveTo($url, $saveDir);
//        echo StringHelper::utf8ToGbk($result->getMsg());
//        $this->assertTrue($result->isSuccess());
//        if ($result->isSuccess()) {
//            $data = $result->getData();
//            var_dump($data);
//        }
//    }

//    public function testQiniuUploader()
//    {
//        $url = "https://pic.xiashu.cc/image/nocover.jpg";
//        $saveName = "cover/test/" . rand(0, 1000);
//        // ['file_name' => $filename, 'save_path' => $save_dir . $filename]
//        $result = PictureDownloadHelper::uploadToQiniu($url, $saveName);
//        echo StringHelper::utf8ToGbk($result->getMsg());
//        $this->assertTrue($result->isSuccess());
//        if ($result->isSuccess()) {
//            $data = $result->getData();
//            var_dump($data);
//        }
//    }
}