<?php

namespace QfsTest;
class Input
{

    static $postDatas = [];

    public static function init()
    {
        if (count($_POST) > 0) {
            $result = $_POST;
        } else {
            $data = file_get_contents('php://input');
            $result = json_decode($data, true);
        }
        self::$postDatas = $result;
        return true;
    }

    public static function Post($key, $default = null)
    {
        return isset(self::$postDatas[$key]) ? self::$postDatas[$key] : $default;
    }

    public static function Get($key, $default = null)
    {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }

}