<?php
namespace Core\Libs;
defined('CORE_PATH') or exit();

class Route
{
    private $_control = 'index';
    private $_action = 'index';
    private $_params = array();
    private static $_instance;

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

    private function __construct()
    {
        if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/') {
            $uri = OutUrl(trim($_SERVER['REQUEST_URI'], '/'));
            $uriArr = explode('/', trim($uri, '/'));
            if (isset($uriArr[0]) && $uriArr[0] != '') {
                $this->_control = $uriArr[0];
            } else {
                $this->_control = 'index';
            }
            if (isset($uriArr[1])) {
                $this->_action = $uriArr[1];
            } else {
                $this->_action = 'index';
            }
            if (isset($uriArr[2])) {
                $count = count($uriArr);
                for ($i = 2; $i < $count; $i++) {
                    if (isset($uriArr[$i + 1])) {
                        $this->_params[$uriArr[$i]] = $uriArr[$i + 1];
                    }
                    $i++;
                }
            } else {
                $this->_params = array();
            }
        }

    }

    public function getControl():string
    {
        return $this->_control;
    }

    public function getAction():string
    {
        return $this->_action;
    }

    public function getParams():array
    {
        return $this->_params;
    }


}