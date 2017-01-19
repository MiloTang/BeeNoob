<?php
namespace Core\Libs;
defined('CORE_PATH') or exit();
class RedisCache implements MemoryCache
{
    private static $_instance;
    private $_ip;
    private $_port;
    private $_redis;
    private function __construct()
    {
        if (isset(Conf::conf()['Redis']['ip'])&&isset(Conf::conf()['Redis']['port']))
        {
            $this->_ip=Conf::conf()['Redis']['ip'];
            $this->_port=Conf::conf()['Redis']['port'];
            $this->_redis=new \Redis();
            try
            {
                $this->_redis->connect($this->_ip,$this->_port);
            }
            catch (\RedisException $e)
            {
                GetError($e->getMessage());
            }

        }
        else
        {
            GetError('请检查配置文件Redis');
        }
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
    public function setCache(string $sql,array $data):bool
    {
        if($this->_redis->getKeys($sql)==null)
        {
            $this->_redis->set($sql,json_encode($data));
            return true;
        }
        return false;
    }
    public function getCache(string $sql):array
    {
        if ($this->_redis->getKeys($sql)[0]==$sql)
        {
            return json_decode($this->_redis->get($sql));
        }
        return null;
    }
}