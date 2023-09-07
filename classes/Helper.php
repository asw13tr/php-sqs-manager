<?php
namespace QfsTest;

class Helper
{

    /*
     * INCLUDE HELPERS
     */
    public static function GetInclude($fileName = null)
    {
        if ($fileName) {
            $fileName = str_replace(["/", ".php"], "", $fileName) . ".php";
            require __DIR__ . "/../src/inc/" . $fileName;
        }
    }

    public static function GetHeader()
    {
        self::GetInclude("header");
    }

    public static function GetFooter()
    {
        self::GetInclude("footer");
    }

    /*
     * METHOD HELPER
     */
    public static function CheckMethod($methods = "GET")
    {
        $allowedMethods = is_array($methods) ? $methods : explode(",", str_replace(' ', "", strtoupper($methods)));
        return in_array($_SERVER["REQUEST_METHOD"], $allowedMethods);
    }


}

?>
