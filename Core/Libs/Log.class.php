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
    public static function log($message,string $type='file',string $name='Log')
    {
        if ($type=='file')
        {
            self::$_class=FileLog::getInstance();
        }
        elseif($type='Mysql')
        {
            self::$_class=MysqlLog::getInstance();
        }
        else
        {
            GetError('不支持'.$type.'类log');
        }
        self::$_class->log($message,$name);
    }
}