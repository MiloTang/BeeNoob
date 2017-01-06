<?php
/**
 * Created by PhpStorm.
 * User: milo
 * Date: 12/15/2016
 * Time: 2:03 PM
 */
namespace Core\libs;
defined('CORE_PATH') or exit();
class Template
{
    private static $_instance;
    private function __construct()
    {
    }
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    private function __clone()
    {

    }
    public function template(string $view)
    {

        $file=WEB_PATH.'/View/Templates/'.$view;
        if (file_exists($file))
        {
            $contents=file_get_contents($file);
            $pattern='/\{ *\$([a-zA-Z_].*) *\}/U';
            $contents=preg_replace($pattern,'<?php echo $$1; ?>',$contents);
            $pattern='/\{ *(while *\(.*\)|switch *\(.*\)|case .*|foreach *\(.*\)|for *\(.*\)|else|elseif *\(.*\)|if *\(.*\)) *\}/';
            $contents=preg_replace($pattern,'<?php $1: ?>',$contents);
            $pattern='/\{\/ *(endwhile|endfor|break|endswitch|endif|endforeach) *\}/';
            $contents=preg_replace($pattern,'<?php $1; ?>',$contents);
            $template=WEB_PATH.'/View/Cache/PHP/'.md5($view).'.php';
            file_put_contents($template,$contents);
        }
    }
}