<?php
namespace Core;
use Core\Libs\Route;
class MiloCore
{
    private function __construct()
    {
    }
    private function __clone()
    {
    }
    private static function _init()
    {
        if(version_compare(PHP_VERSION,'7.0.0','<'))die('require PHP > 7.0.0 !');
        DEBUG?ini_set('display_errors','On'):ini_set('display_errors','Off');
        session_start();
        self::_define();
        self::_sld();
        self::_path();
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
            file_put_contents(WEB_PATH.'/View'.'/Templates'.'/'.'demo.html',file_get_contents(CORE_PATH.'/'.'demo.html'));
            mkdir(WEB_PATH.'/Controller', 0777, true);
            mkdir(WEB_PATH.'/Public/Upload/Image', 0777, true);
            mkdir(WEB_PATH.'/Public/Upload/Text', 0777, true);
            mkdir(WEB_PATH.'/Public/Static/JS', 0777, true);
            mkdir(WEB_PATH.'/Public/Static/CSS', 0777, true);
            mkdir(WEB_PATH.'/Public/Static/Images', 0777, true);
            $string='<?php'.PHP_EOL.'namespace '.WEB_NAME.'\\Controller;'.PHP_EOL.'use Core\Libs\BaseController;'.PHP_EOL
            .'class IndexController extends BaseController'.PHP_EOL.'{'.PHP_EOL.'   public function index()'.PHP_EOL.
            '   {'.PHP_EOL.'      $this->display(\'demo.html\');'.PHP_EOL.'   }'.PHP_EOL.'}';
            file_put_contents(WEB_PATH.'Controller'.'/'.'IndexController.class.php',$string);
            mkdir(WEB_PATH.'Common', 0777, true);
            mkdir(WEB_PATH.'Common'.'/Config', 0777, true);
            mkdir(WEB_PATH.'Common'.'/Log', 0777, true);
            file_put_contents(WEB_PATH.'Common/Config/Config.php',file_get_contents(CORE_PATH.'Common/Config/Config.php'));
        }
    }
    public static function run()
    {
        self::_init();
        self::_autoload();
        self::_loadVendor();
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
    private static function _loadVendor()
    {
        if (file_exists('Vendor/autoload.php'))
        {
            require_once "Vendor/autoload.php";
        }
    }
    private static function _autoload()
    {
        spl_autoload_extensions('.class.php');
        set_include_path(get_include_path().PATH_SEPARATOR.'');
        spl_autoload_register();
    }
    private static function _define()
    {
        date_default_timezone_set('Asia/Chongqing');
        $GLOBALS['StartTime'] = microtime(TRUE);
        define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
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
        defined('CORE_PATH') or define('CORE_PATH',ROOT_DIR.'/Core/');
        define('VERSION','1.0.0');
        define('MAGIC_GPC',ini_get('magic_quotes_gpc')?true:false);
    }
    private static function _path()
    {
        if (IS_SLD)
        {
            define('WEB_PATH',ROOT_DIR.'/'.SLD.'/');
            defined('WEB_NAME') or define('WEB_NAME',SLD);
        }
        else
        {
            define('WEB_PATH',APP_PATH);
            defined('WEB_NAME') or define('WEB_NAME',APP_NAME);
        }
    }
    private static function _sld()
    {
        $SLD=explode('.',$_SERVER['HTTP_HOST'])[0];
        if($SLD!='localhost'&&$SLD!='127')
        {
            $conf=require_once CORE_PATH.'Common/Config/SLDConf.php'.'';
            if(isset($conf)&&$conf!=null)
            {
                if (is_array($conf))
                {
                    foreach ($conf as $key => $value)
                    {
                        if (strtolower($SLD)==strtolower($value))
                        {
                            defined('IS_SLD') or define('IS_SLD',true);
                            defined('SLD') or define('SLD',$value);
                            break;
                        }
                    }
                }
            }
        }
        defined('IS_SLD') or define('IS_SLD',false);
    }
}