<?php
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
    private $_dbConf;
    private $_sql;
    private $_params;
    private $_stmt;
    private function __clone()
    {

    }
    private function __construct()
    {
        $this->_initDBCConf();
        $this->_pdo=$this->_link();
        $this->_setCharset();
        $this->_selectDB();
    }
    private function _initDBCConf()
    {
        $conf=Conf::getInstance()->conf();
        $this->_dbConf=isset($conf['DB']['Mysql'])?$conf['DB']['Mysql']:GetError('请检查配置文件');
        $this->_dsn = isset($this->_dbConf['dsn'])?$this->_dbConf['dsn']:GetError('请配置dsn');
        $this->_dbName = isset($this->_dbConf['dbName'])?$this->_dbConf['dbName']:GetError('请配置数据库');
        $this->_username = isset($this->_dbConf['username'])?$this->_dbConf['username']:GetError('请配置用户名');
        $this->_password = isset($this->_dbConf['password'])?$this->_dbConf['password']:GetError('请配置用户密码');
        $this->_charset = isset($this->_dbConf['charset'])?$this->_dbConf['charset']:GetError('请配置字符集');
        $this->_port = isset($this->_dbConf['port'])?$this->_dbConf['port']:GetError('请配置端口');
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
               GetError($e->getMessage());
        }
        return $this->_pdo;
    }
    static public function getInstance()
    {
        if (!(self::$_instance instanceof static))
        {
            self::$_instance = new self();
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
            $this->debug();
        }
        try
        {
            $stmt = $this->_pdo->prepare($sql);
            $stmt->execute($bindParams);
            return $stmt;
        }
        catch (\PDOException $e)
        {
                GetError($e->getMessage());
        }
        return null;
    }
    /**
     * @param $table
     * @return bool
     */
    public function checkTable($table):bool
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
    public function checkField($table,$field):bool
    {
        $rst = $this->_pdo->query("desc $table")->fetchAll();
        foreach ($rst as $keys => $values)
        {
            if ($field==$values['Field'])
            {
                return true;
                break;
            }
        }
        return false;
    }

    public function counts($table) : int
    {
        $sql=''.'select count(*) from '.$table;
        $rowCount=$this->_pdo->query($sql)->fetchColumn();
        return $rowCount;
    }

    public function select(string $table, $fields = '*')
    {
        $this->_sql=null;
        $str = is_array( $fields ) ? implode( ',', $fields ) : $fields;
        $this->_sql = 'SELECT ' . $str . ' FROM ' .  $table . ' ';
        return $this;
    }
    public function where($str,$params=null)
    {
        foreach ($params as $item=>$value)
        {
            $this->_params[$item]=$value;
        }
        $this->_sql=$this->_sql.'where '.$str .' ';
        return $this;
    }
    function order($str)
    {
        $this->_sql = $this->_sql." ORDER BY $str";
        return $this;
    }
    function limit($len = 10, $start = 0)
    {
        $this->_sql = $this->_sql." LIMIT $start,$len";
        return $this;
    }
    public function execute()
    {
        try
        {
            $stmt = $this->_pdo->prepare($this->_sql);
            $stmt->execute($this->_params);
            $this->_stmt=$stmt;
        }
        catch (\PDOException $e)
        {
            GetError($e->getMessage());
        }
        return true;
    }
    function fetchAll()
    {
        if ($this->execute())
        {
            return $this->_stmt->fetchAll();
        }
        return null;
    }
    function fetchRow()
    {
        if ($this->execute())
        {
            return $this->_stmt->fetch();
        }
        return null;
    }
    public function insert(string $table,array $fields)
    {
        $this->_sql=null;
        $field1 = current($fields);
        if (is_array( $field1 ))
        {
            $str=null;
            $i=0;
            $tempValue=array();
            $lowNames=array();
            foreach ($fields as $field)
            {
                $lowNames = array_keys($field);
                $temp = array();
                foreach ($field as $item => $value) {
                    $temp[] = ':'. $item.$i;
                    $tempValue[':' . $item.$i] = $value;

                }
                $i++;
                $lowValues = implode(',', $temp);
                if($str!=null)
                {
                    $str=$str.'union all select '.$lowValues.' ';
                }
                else
                {
                    $str='select '.$lowValues.' ';
                }
            }
            $this->_params=$tempValue;
            $this->_sql ='INSERT'.' INTO '.$table . '(' . implode(',', $lowNames) . ') '.$str;
        }
        else
        {
            $lowNames  = array_keys( $fields);
            $temp=array();
            foreach ($fields as $item=>$value)
            {
                 $temp[]=':'.$item;
                 $this->_params[':'.$item]=$value;
            }
            $lowValues=implode(',',$temp);
            $this->_sql = 'INSERT'.' INTO ' .$table . '(' . implode( ',', $lowNames ) . ') VALUES(' . $lowValues . ')';
        }
        return $this;
    }
    public function lastId()
    {
        if ($this->execute())
        {
            return $this->_pdo->lastInsertId();
        }
        else
        {
            return false;
        }
    }
    public function update(string $table,array $fields)
    {
        $this->_sql=null;
        $arr=null;
        foreach ($fields as $item=>$value)
        {
            $arr[]=$item.'='.':'.$item.' ';
            $this->_params[':'.$item]=$value;
        }
        $str=implode(',',$arr);
        $this->_sql = 'UPDATE ' .$table . ' SET '.$str;
        return $this;
    }
    function affectedRows()
    {
        if ($this->execute())
        {
            return $this->_stmt->rowCount();
        }
        else
        {
            return false;
        }
    }
    function delete(string $table)
    {
        $this->_sql=null;
        $this->_sql = 'DELETE'.' FROM ' . $table . ' ';
        return $this;
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
    public function debug()
    {
        die($this->_sql);
    }
    public function close()
    {
        $this->_pdo = null;
    }

}