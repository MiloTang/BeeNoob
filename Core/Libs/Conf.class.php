<?php
namespace Core\libs;
defined('CORE_PATH') or exit();
class Conf
{
    private static $_conf;
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
    static public function conf():array
    {
        if (isset(self::$_conf['conf']))
        {
            return self::$_conf['conf'];
        }
        else
        {
            $file = WEB_PATH . 'Common/Config/Config.php';
            if (is_file($file)) {
                $conf = require_once $file . '';
                self::$_conf['conf'] = $conf;
                return self::$_conf['conf'];
            }
            else
            {
                $file = CORE_PATH . 'Common/Config/Config.php';
                if (is_file($file)) {
                    $conf = require_once $file . '';
                    self::$_conf['conf'] = $conf;
                    return self::$_conf['conf'];
                }
                else
                {
                    GetError('配置文件不存在' . 'conf');
                }
            }
        }
        return null;
    }
}