<?php
/**
 * Created by PhpStorm.
 * User: milo
 * Date: 11/1/2016
 * Time: 3:06 PM
 */
namespace Core\libs;
defined('CORE_PATH') or exit();
class Model
{
    private $_dsn;
    private $_port;
    private $_username;
    private $_password;
    private $_charset;
    private $_dbName;
    private static $_instance;
    private $_pdo;
    private function __clone()
    {

    }
    private function __construct(array $dbConf)
    {
        $this->_initDBCConf($dbConf);
        $this->_pdo=$this->_link();
        $this->_setCharset();
        $this->_selectDB();
    }
    private function _initDBCConf(array $dbConf)
    {
        if (DEBUG)
        {
            $this->_dsn = isset($dbConf['dsn'])?$dbConf['dsn']:die('请配置dsn');
            $this->_dbName = isset($dbConf['dbName'])?$dbConf['dbName']:die('请配置数据库');
            $this->_username = isset($dbConf['username'])?$dbConf['username']:die('请配置用户名');
            $this->_password = isset($dbConf['password'])?$dbConf['password']:die('请配置用户密码');
            $this->_charset = isset($dbConf['charset'])?$dbConf['charset']:'utf8';
            $this->_port = isset($dbConf['port'])?$dbConf['port']:'3306';
        }
        else
        {
            $this->_dsn = isset($dbConf['dsn'])?$dbConf['dsn']:die();
            $this->_dbName = isset($dbConf['dbName'])?$dbConf['dbName']:die();
            $this->_username = isset($dbConf['username'])?$dbConf['username']:die();
            $this->_password = isset($dbConf['password'])?$dbConf['password']:die();
            $this->_charset = isset($dbConf['charset'])?$dbConf['charset']:'utf8';
            $this->_port = isset($dbConf['port'])?$dbConf['port']:'3306';
        }
    }
    private function _link():\PDO
    {
        try
        {
            $this->_dsn = $this->_dsn.$this->_port;
            $this->_pdo = new \PDO($this->_dsn,$this->_username, $this->_password);
            $this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->_pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $this->_pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
        } catch (\PDOException $e) {
            if (DEBUG)
            {
                exit($e->getMessage());
            }
            else
            {
                exit();
            }
        }
        return $this->_pdo;
    }
    static public function getInstance(array $dbConf=null)
    {
        if($dbConf==null)
        {
          $dbConf=isset(Conf::getInstance()->conf()['DB'])?Conf::getInstance()->conf()['DB']:null;
        }
        if (!(self::$_instance instanceof static))
        {
            self::$_instance = new static($dbConf);
        }
        return self::$_instance;
    }
    private function _setCharset()
    {
        $sql = 'SET NAMES '.$this->_charset;
        $this->_pdo->query($sql);
    }

    private function _selectDB()
    {
        $sql = 'USE '.$this->_dbName;
        $this->_pdo->query($sql);
    }
    /**
     * @param $sql
     * @param $bindParams
     * @param bool $debug
     * @return mixed
     */
    public function doSql($sql,$bindParams=null,$debug=false)
    {
        if($debug)
        {
            $this->debug($sql);
        }
        try
        {
            $stmt = $this->_pdo->prepare($sql);
            $stmt->execute($bindParams);
            if(substr(trim($sql),0,6)=='select')
            {
                $rst=$stmt->fetchAll();
            }
            else
            {
                $rst= $stmt->execute($bindParams);
            }
            return $rst;
        }
        catch (\PDOException $e)
        {
            if (DEBUG)
            {
                exit($e->getMessage());
            }
            else
            {
                exit();
            }
        }
    }


    /**
     * @param $table
     * @return bool
     */
    public function tableCheck($table):bool
    {
        $rst = $this->_pdo->query('show tables')->fetchAll();
        foreach ($rst as $keys => $values)
        {
             if ($table==$values["Tables_in_.$this->_dbName"])
             {
                 return true;
             }
        }
        return false;
    }

    /**
     * @param $table
     * @param $field
     * @return bool
     */
    public function fieldsCheck($table,$field):bool
    {
        $rst = $this->_pdo->query("desc $table")->fetchAll();
        foreach ($rst as $keys => $values)
        {
            if ($field==$values['Field'])
            {
                return true;
                break;
            }
            echo $values['Field'];
        }
        return false;
    }

    public function counts($table) : int
    {
        $sql=''.'select count(*) from '.$table;
        $rowCount=$this->_pdo->query($sql)->fetchColumn();
        return $rowCount;
    }
    public function startTrans()
    {
        $this->_pdo->beginTransaction();
    }
    public function commitTrans()
    {
        $this->_pdo->commit();
    }

    public function inTrans($sql,$bindParams)
    {

        if ($this->_pdo->inTransaction())
        {
            $this->doSql($sql,$bindParams,$debug=false);
        }
        else
        {
            GetError('没有开启事务');
        }
    }
    public function rollBackTrans()
    {
        $this->_pdo->rollBack();
    }

    /**
     * @param $sql
     */
    private function debug($sql)
    {
        die($sql);
    }
    public function close()
    {
        $this->_pdo = null;
    }

}