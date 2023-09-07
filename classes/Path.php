<?php

namespace QfsTest;
;

class Path
{
    static $rootPath = ".";

    private static function init(){
        self::$rootPath = realpath(__DIR__ . "/..");
    }


    public static function createOrGetFolderPath($folderName){
        self::init();
        $fullPath = self::$rootPath . '/' . $folderName;
        if (file_exists($fullPath)) {
            return $fullPath;
        }

        if (mkdir($fullPath, 0755, true)) {
            return $fullPath;
        }
        return false;
    }


    public static function createOrGetFilePath($fileName){
        self::init();
        $path = self::$rootPath . '/' . $fileName;
        if (file_exists($path)) {
            return $path;
        }
        $file = fopen($path, "w+");
        fclose($file);
        chmod($path, 0755);
        return $path;
    }

}