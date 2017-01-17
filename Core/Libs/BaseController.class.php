<?php
namespace Core\libs;
defined('CORE_PATH') or exit();
class BaseController
{
    private $_data;
    /**
     * @param string $name
     * @param $data
     */
    public final function assign(string $name,$data)
    {
        $this->_data[$name]=$data;
    }
    /**
     * @param string $view
     */
    public final function display(string $view)
    {
        $this->templatePHP($view);
    }

    /**
     * @param string $view
     */
    private function templatePHP(string $view)
    {
        $file=WEB_PATH.'View/Cache/PHP/'.md5($view).'.php';
        $tp=Template::getInstance();
        if ($this->_data!=null)
        {
            extract($this->_data);
        }
        if(CACHE_PHP)
        {
            if (!file_exists($file))
            {
                $tp->template($view);
            }
            else
            {
                $this->cachePHPCheck($view);
                if (!file_exists($file))
                {
                    $tp->template($view);
                }
            }
            $this->templateHtml($view);
        }
        else
        {
            $tp->template($view);
            $this->templateHtml($view);
        }
    }

    /**
     * @param string $view
     */
    private function templateHtml(string $view)
    {
        if (CACHE_HTML)
        {
            $this->cacheHTMLCheck();
            $this->createHtml($view);
        }
        else
        {
            if ($this->_data!=null)
            {
                extract($this->_data);
            }
            $file=WEB_PATH.'View/Cache/PHP/'.md5($view).'.php';
            if(file_exists($file))
            {
                require_once $file.'';
            }
            else
            {
                GetError($view.' 模板不存在'.__FUNCTION__);
            }
        }
    }

    /**
     * @return array
     */

    final function params():array
    {
        $params=array();
        if($_SERVER['REQUEST_METHOD']=='GET')
        {
            $route=Route::getInstance();
            $params=$route->getParams();

        }
        elseif($_SERVER['REQUEST_METHOD']=='POST')
        {
            $params=$_POST;
        }
        if(MAGIC_GPC)
        {
            return $this->filterGPCParams($params);
        }
        else
        {
            return $this->filterParams($params);
        }
    }

    /**
     * @param array $params
     * @return array
     */
    private function filterParams(array $params):array
    {
        foreach ($params as $key=>$value)
        {
            if(is_string($value))
            {
                $params[$key]=addslashes(strip_tags(htmlentities($value,ENT_QUOTES)));

            }
            elseif(is_array($value))
            {
                $params[$key]=$this->filterParams($value);
            }
        }
        return $params;
    }

    /**
     * @param array $params
     * @return array
     */
    private function filterGPCParams(array $params):array
    {
        foreach ($params as $key=>$value)
        {
            if(is_string($value))
            {
                $params[$key]=strip_tags(htmlentities($value,ENT_QUOTES));
            }
            elseif(is_array($value))
            {
                $params[$key]=$this->filterGPCParams($value);
            }
        }
        return $params;
    }

    /**
     * @param string $view
     */
    private function createHtml(string $view)
    {
        $FName=md5(Route::getInstance()->getControl().Route::getInstance()->getAction());
        $cacheFile=WEB_PATH.'View/Cache/HTML/'.$FName.'.html';
        $file=WEB_PATH.'View/Cache/PHP/'.$view;
        if ($this->_data!=null)
        {
            extract($this->_data);
        }
        if(file_exists($file))
        {
            ob_start();
            require_once $file.'';
            $contents = ob_get_contents();
            file_put_contents($cacheFile,$contents);
            ob_end_flush();
        }
        else
        {
            GetError($view.' 模板不存在'.__FUNCTION__);
        }
    }

    /**
     *
     */
    private function cacheHTMLCheck()
    {
        $FName=md5(Route::getInstance()->getControl().Route::getInstance()->getAction());
        $cacheFile=WEB_PATH.'View/Cache/HTML/'.$FName.'.html';
        if (is_file($cacheFile))
        {
            $aTime=fileatime($cacheFile);
            if ((time()-$aTime)>CACHE_TIME)
            {
                unlink($cacheFile);
            }
            else
            {
                require_once $cacheFile.'';
                exit();
            }
        }
    }

    /**
     * @param string $view
     * @param int $cacheTime
     */
    private function cachePHPCheck(string $view,int $cacheTime=200)
    {
        if(CACHE_PHP)
        {
            $view=md5($view).'.php';
            $cacheFile=WEB_PATH.'View/Cache/PHP/'.$view;
            if (is_file($cacheFile))
            {
                $aTime=fileatime($cacheFile);
                if ((time()-$aTime)>$cacheTime)
                {
                    unlink($cacheFile);
                }
                else
                {
                    require_once $cacheFile.'';
                    exit();
                }
            }
        }
    }
}