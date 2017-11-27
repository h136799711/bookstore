<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-27 13:51
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\picture\helper;


use app\component\picture\entity\BsPictureEntity;
use app\component\picture\logic\BsPictureLogic;
use by\infrastructure\base\CallResult;
use by\infrastructure\constants\StatusEnum;
use by\infrastructure\helper\CallResultHelper;

class PictureDownloadHelper
{
    /**
     * 下载文件并保存到指定目录
     * @param $url
     * @param string $saveDir
     * @return CallResult
     */
    public static function downloadAndSaveTo($url, $saveDir = "./public/upload/r_pic")
    {
        $ext = strrchr($url, '.');
        $month = date("ymdH");
        $saveDir = $saveDir . '/' . $month;
        $saveFilename = md5($url) . $ext;
        $result = self::getImage($url, $saveDir, $saveFilename, 1);
        if (!$result->isSuccess()) {
            return $result;
        }
        $data = $result->getData();
        $size = filesize($data['save_path']);
        $md5 = md5_file($data['save_path']);
        $sha1 = sha1_file($data['save_path']);
        $path = ltrim($data['save_path'], ".");
        $path = str_replace('/public', '', $path);
        $entity = new BsPictureEntity();
        $entity->setPrimaryFileUri($path);
        $entity->setSaveName($saveFilename);
        $entity->setOriName($url);
        $entity->setSize($size);
        $entity->setUrl($path);
        $entity->setMd5($md5);
        $entity->setSha1($sha1);
        $entity->setStatus(StatusEnum::ENABLE);
        $entity->setExt($ext);
        $result = (new BsPictureLogic())->add($entity);
        $entity->setId($result);
        return CallResultHelper::success($entity);
    }

    /**
     * @param $url
     * @param string $save_dir
     * @param string $filename
     * @param int $type
     * @return \by\infrastructure\base\CallResult
     */
    public static function getImage($url, $save_dir = '', $filename = '', $type = 0)
    {
        if (trim($url) == '') {
            return CallResultHelper::fail('图片地址为空');
        }
        if (trim($save_dir) == '') {
            $save_dir = './';
        }
        $ext = strrchr($url, '.');
        if (trim($filename) == '') {//保存文件名
            if (!in_array($ext, ['.gif', ".jpg", ".png"])) {
                return CallResultHelper::fail('图片后缀非法(支持jpg,gif,png)');
            }
            $filename = time() . $ext;
        }
        if (0 !== strrpos($save_dir, '/')) {
            $save_dir .= '/';
        }
        //创建保存目录
        if (!file_exists($save_dir) && !mkdir($save_dir, 0777, true)) {
            return CallResultHelper::fail('图片保存路径不存在或无写入权限');
        }
        //获取远程文件所采用的方法
        if ($type) {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            $img = curl_exec($ch);
            curl_close($ch);
        } else {
            ob_start();
            readfile($url);
            $img = ob_get_contents();
            ob_end_clean();
        }
        // $size=strlen($img);
        $path = $save_dir . $filename;
        if (file_exists($path)) {
            return CallResultHelper::fail('图片已存在');
        }
        //文件大小
        $fp2 = @fopen($path, 'a');
        if ($fp2 === false) {
            return CallResultHelper::fail('图片保存到本地失败');
        }
        fwrite($fp2, $img);
        fclose($fp2);
        unset($img, $url);
        return CallResultHelper::success(['file_name' => $filename, 'save_path' => $save_dir . $filename]);
    }
}