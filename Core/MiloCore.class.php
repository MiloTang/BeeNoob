<?php
/**
 * Created by PhpStorm.
 * User: Milo
 * Date: 2016/11/12
 * Time: 20:39
 */
namespace Core;
use Core\Libs\Route;
class MiloCore
{
    private static $_classMap= array();
    private function __construct()
    {

    }
    private function __clone()
    {

    }
    private static function _init()
    {
        if(version_compare(PHP_VERSION,'7.0.0','<'))die('require PHP > 7.0.0 !');
        self::_define();
        self::_path();
        DEBUG?ini_set('display_errors','On'):ini_set('display_errors','Off');
        date_default_timezone_set('Asia/Chongqing');
        $GLOBALS['StartTime'] = microtime(TRUE);
        define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
        require CORE_PATH.'Common/Function.php'.'';
        require CORE_PATH.'Common/Common.php'.'';
        if(MEMORY_LIMIT_ON)
        {
            $GLOBALS['StartUseMemory'] = memory_get_usage();
        }
        if(!is_dir(WEB_PATH))
        {
            mkdir(WEB_PATH, 0777, true);
            mkdir(WEB_PATH.'/Model', 0777, true);
            mkdir(WEB_PATH.'/View', 0777, true);
            mkdir(WEB_PATH.'/View'.'/Cache', 0777, true);
            mkdir(WEB_PATH.'/View'.'/Cache/PHP/', 0777, true);
            mkdir(WEB_PATH.'/View'.'/Cache/HTML/', 0777, true);
            mkdir(WEB_PATH.'/View'.'/Templates', 0777, true);
            file_put_contents(WEB_PATH.'/View'.'/Templates'.'/'.'index.html',file_get_contents(CORE_PATH.'/'.'index.html'));
            mkdir(WEB_PATH.'/Controller', 0777, true);
            mkdir(WEB_PATH.'/Public/Static/JS', 0777, true);
            mkdir(WEB_PATH.'/Public/Static/CSS', 0777, true);
            mkdir(WEB_PATH.'/Public/Static/Images', 0777, true);
            $string='<?php'.PHP_EOL.'namespace '.APP_NAME.'\\Controller;'.PHP_EOL.'use Core\Libs\BaseController;'.PHP_EOL
            .'class IndexController extends BaseController'.PHP_EOL.'{'.PHP_EOL.'   public function index()'.PHP_EOL.
            '   {'.PHP_EOL.'      $this->display(\'index.html\');'.PHP_EOL.'   }'.PHP_EOL.'}';
            file_put_contents(WEB_PATH.'Controller'.'/'.'IndexController.class.php',$string);
            mkdir(WEB_PATH.'Common', 0777, true);
            mkdir(WEB_PATH.'Common'.'/Config', 0777, true);
            $string='<?php'.PHP_EOL.'return array('.PHP_EOL.');';
            file_put_contents(WEB_PATH.'Common'.'/Config'.'/'.'Config.php',$string);
        }
    }
    public static function run()
    {
        self::_init();
        spl_autoload_register('self::_load');
        $route = Route::getInstance();
        $control=$route->getControl();
        $action=$route->getAction();
        $CtrlFile=WEB_PATH.'controller/'.$control.'Controller'.EXT;
        $CtrlClass=trim(ltrim(WEB_PATH,ROOT_DIR),'/').'\Controller\\'.$control.'Controller';
        if (is_file($CtrlFile))
        {
            $ctrl = new $CtrlClass();
            if (method_exists($ctrl,$action))
            {
                $ctrl->$action();
            }
            else
            {
                GetError($action.' 方法不存在');
            }
        }
        else
        {
            GetError($control.' 控制器不存在');
        }
    }
    private static function _load(string $class) : bool
    {
        if(isset($_classMap[$class]))
        {
            return true;
        }
        else
        {
			$class=ROOT_DIR.'/'.$class;
			$class=str_replace('\\','/',$class);
            $file=$class.EXT;
            if(is_file($file))
            {
                require_once $file.'';
                self::$_classMap[$class]=$class;
                return true;
            }
            else
            {
                GetError('文件不存在 '.$file);
            }
            return false;
        }
    }
    private static function _define()
    {
        defined('ROOT_DIR') or define('ROOT_DIR',str_replace('\\','/',getcwd()));
        defined('APP_NAME') or define('APP_NAME','Home');
        defined('APP_PATH') or define('APP_PATH',ROOT_DIR.'/'.APP_NAME.'/');
        defined('DEBUG') or define('DEBUG',false);
        defined('URL_SECRET') or define('URL_SECRET',false);
        defined('PUBLIC') or define('PUBLIC',ROOT_DIR.'/Public/');
        defined('VENDOR') or define('VENDOR',ROOT_DIR.'/Vendor/');
        defined('EXT') or define('EXT','.class.php');
        defined('CACHE_PHP') or define('CACHE_PHP',false);
        defined('CACHE_HTML') or define('CACHE_HTML',false);
        defined('CACHE_TIME') or define('CACHE_TIME',200);
        defined('ADMIN_SLD_NAME') or define('ADMIN_SLD_NAME',NULL);
        defined('CORE_PATH') or define('CORE_PATH',ROOT_DIR.'/Core/');
        define('VERSION','1.0.0');
        define('MAGIC_GPC',ini_get('magic_quotes_gpc')?true:false);
        $SLD=explode('.',$_SERVER['HTTP_HOST'])[0];
        if($SLD!='localhost'&&$SLD!='127'&&strtolower($SLD)==strtolower(ADMIN_SLD_NAME)&&ADMIN_SLD_NAME!=NULL)
        {
            defined('IS_ADMIN') or define('IS_ADMIN',true);
        }
        else
        {
            defined('IS_ADMIN') or define('IS_ADMIN',false);
        }

    }
    private static function _path()
    {
        if (IS_ADMIN)
        {
            define('WEB_PATH',ROOT_DIR.'/'.ADMIN_SLD_NAME.'/');
        }
        else
        {
            define('WEB_PATH',APP_PATH);
        }
    }
}