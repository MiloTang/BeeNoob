<?php
/**
 * Created by PhpStorm.
 * User: milo
 * Date: 11/1/2016
 * Time: 6:33 PM
 */
namespace Core\libs\Drive\Log;
use Core\libs\Model;

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
        $model=Model::getInstance();
        $sql='create table'.' IF NOT EXISTS log (log_id INT NOT NULL AUTO_INCREMENT,log_name VARCHAR(30) NOT NULL,log_msg VARCHAR(500) NOT NULL,PRIMARY KEY ( log_id ));';
        $model->doSql($sql);
        $totalTime=microtime(true)-$GLOBALS['StartTime'];
        if(MEMORY_LIMIT_ON)
        {
            $totalMemory=memory_get_usage()-$GLOBALS['StartUseMemory'];
        }
        else
        {
            $totalMemory=0;
        }
        $str=date('Y-m-d H:i:s') .' 耗时: '.$totalTime.' 耗内存: '.$totalMemory.'  '.$message;
        $fields=array('log_name'=>$name,'log_msg'=>$str);
        $model->insert('log',$fields)->lastId();
    }
}