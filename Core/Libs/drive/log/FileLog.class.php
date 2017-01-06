<?php
/**
 * Created by PhpStorm.
 * User: milo
 * Date: 11/1/2016
 * Time: 6:33 PM
 */
namespace Core\libs\drive\log;
class FileLog
{
    private $path;
    private static $_instance;
    private function __construct()
    {
        $this->path=ROOT_DIR.'/Running/log/';
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
       if(!is_dir($this->path))
       {
           mkdir($this->path,'0777',true);
       }
        $totalTime=microtime(true)-$GLOBALS['StartTime'];
        if(MEMORY_LIMIT_ON)
        {
            $totalMemory=memory_get_usage()-$GLOBALS['StartUseMemory'];
        }
        else
        {
            $totalMemory=0;
        }
       return file_put_contents($this->path.date('Ymd').$name,date('Y-m-d H:i:s') .' 耗时: '.$totalTime.' 耗内存: '.$totalMemory.'  '.$message.PHP_EOL,FILE_APPEND);

    }
}