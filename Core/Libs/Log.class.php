<?php
namespace Core\libs;
defined('CORE_PATH') or exit();
use Core\libs\drive\log\FileLog;
use Core\libs\drive\log\MysqlLog;

class Log
{
    private static $_class;
    private static $_instance;
    private function __construct()
    {
    }
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    private function __clone()
    {

    }
    public static function log(string $message,string $name='error_log')
    {
        $type=isset(Conf::getInstance()->conf()['LOG'])?Conf::getInstance()->conf()['LOG']:'file';
        if (strtolower($type)=='file')
        {
            self::$_class=FileLog::getInstance();
        }
        elseif(strtolower($type)=='mysql')
        {
            self::$_class=MysqlLog::getInstance();
        }
        else
        {
            exit('不支持'.$type.'类型log');
        }
        self::$_class->log($message,strtolower($name));
    }
}