<?php

namespace Ghlin\DingtalkEapp\Util;

class Log
{
    public static function i($msg)
    {
        self::write('I', $msg);
    }
    
    public static function e($msg)
    {
        self::write('E', $msg);
    }

    private static function write($level, $msg)
    {
        $filename = config('dingtalkeapp.dir_root') . "isv.log";
        $logFile = fopen($filename, "aw");
        fwrite($logFile, $level . "/" . date(" Y-m-d h:i:s") . "  " . $msg . "\n");
        fclose($logFile);
    }
}
