<?php
/**
 * Created by PhpStorm.
 * User: milo
 * Date: 11/1/2016
 * Time: 6:33 PM
 */
namespace Core\libs\Drive\Log;
class MysqlLog implements Log
{
    private static $_instance;
    private function __construct()
    {
    }
    public static  function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    private function __clone()
    {

    }
    public function log($message,$name)
    {

    }
}