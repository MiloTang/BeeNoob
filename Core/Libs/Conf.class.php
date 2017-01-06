<?php
/**
 * Created by PhpStorm.
 * User: milo
 * Date: 11/1/2016
 * Time: 5:27 PM
 */
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
    static public function conf(string $name,string $index=null):array
    {
        if (isset(self::$_conf[$name]))
        {
            if ($index!= null)
            {
                if(isset(self::$_conf[$name][$index]))
                {
                    return self::$_conf[$name][$index];
                }
            }
            else
            {
                return self::$_conf[$name];
            }
        }
        else
        {
            $file = WEB_PATH . '/Common/Config/' . $name . '.php';
            if (is_file($file)) {
                $conf = require_once $file . '';
                self::$_conf[$name] = $conf;
                if ($index !== null)
                {
                    if(isset($conf[$index]))
                    {
                        return $conf[$index];
                    }
                    else
                    {
                        GetError('次索引不存在' . $name.__LINE__);
                    }
                }
                else
                {
                    return $conf;
                }

            }
            else
            {
                $file = CORE_PATH . '/Common/Config/' . $name . '.php';
                if (is_file($file))
                {
                    $conf = require_once $file . '';
                    self::$_conf[$name] = $conf;
                    if ($index !== null)
                    {
                        if(isset($conf[$index]))
                        {
                            return $conf[$index];
                        }
                        else
                        {
                            GetError('次索引不存在' . $name.__LINE__);
                        }
                    }
                    else
                    {
                        return $conf;
                    }
                }
                else
                {
                    GetError('配置文件不存在' . $name);
                }
            }
        }
        return null;
    }
}