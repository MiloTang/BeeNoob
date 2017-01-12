<?php
/**
 * Created by PhpStorm.
 * User: milo
 * Date: 11/1/2016
 * Time: 6:21 PM
 */
namespace Core\libs;
defined('CORE_PATH') or exit();
use Core\libs\drive\log\FileLog;

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
    public static function log($message,$name='log')
    {
        self::$_class=FileLog::getInstance();
        self::$_class->log($message,$name);
    }
}