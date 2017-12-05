<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 2017-12-03
 * Time: 21:37
 */

namespace by\component\tp5\helper;


class StaticHtmlHelper
{

    public static function write($path, $content)
    {

        $arrPath = explode("/", $path);
        $fileName = $arrPath[count($arrPath) - 1];
        $filePath = str_replace($fileName, '', $path);

        if (!is_dir($filePath)) {
            mkdir($filePath, 766, true);
        }

        if (!file_exists($path)) {
            $datetime = date('Y-m-d H:i:s', time());
            $timestamp = "<!-- STATIC CACHE " . $datetime . " -->";
            $file = fopen($path, "w");
            fwrite($file, $timestamp . $content);
            fclose($file);
        }

        return true;
    }

}