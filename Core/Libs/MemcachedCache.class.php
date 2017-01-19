<?php
namespace Core\Libs;
defined('CORE_PATH') or exit();
class MemCachedCache implements MemoryCache
{
    private static $_instance;
    private $_ip;
    private $_port;
    private $_memcached;
    private function __construct()
    {
        if (isset(Conf::conf()['Redis']['ip'])&&isset(Conf::conf()['Redis']['port']))
        {
            $this->_ip=Conf::conf()['Redis']['ip'];
            $this->_port=Conf::conf()['Redis']['port'];
            $this->_memcached = new \Memcached();
            try
            {
                $this->_memcached->addServer($this->_ip,$this->_port);
            }
            catch (\MemcachedException $e)
            {
                GetError($e->getMessage());
            }

        }
        else
        {
            GetError('请检查配置文件Memcached');
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
        if($this->getCache($sql)==null)
        {
            $this->_memcached->set($sql,$data);
            return true;
        }
        return false;
    }
    public function getCache(string $sql):array
    {
        $data=$this->_memcached->get($sql);
        return $data;
    }
}