<?php
/**
 * Created by PhpStorm.
 * User: milo
 * Date: 11/15/2016
 * Time: 11:29 AM
 */
defined('CORE_PATH') or exit();
function PrintFm($var=null)
{
    if(!is_null($var))
    {
        echo '<pre style="background-color: #bbbbbb;color:brown;font-size: x-large"><b>'.print_r($var,true).'</b></pre>';
    }
}
function JumpUrl(string $url)
{
    header('Location:'.$url);
    exit();
}
function InUrl(string $url):string
{
    if (substr($url,strlen($url)-5)=='.html')
    {
        $url=substr($url,0,strlen($url)-5);
    }
    if (URL_SECRET)
    {
        return base64_encode(urlencode($url)).'.html';
    }
    else
    {
        return $url.'.html';
    }

}
function OutUrl(string $url):string
{

    if (substr($url,strlen($url)-5)=='.html')
    {
        $url=substr($url,0,strlen($url)-5);
    }
    if (URL_SECRET)
    {

        return urldecode(base64_decode($url));
    }
    else
    {
        return $url;
    }
}
function GetError(string $string)
{
    if (DEBUG)
    {
        PrintFm($string);
        exit();
    }
    else
    {
        $log=\Core\libs\Log::getInstance();
        $log->log('浏览器信息: '.$_SERVER['HTTP_USER_AGENT'].' IP信息:'.$_SERVER['REMOTE_ADDR'].' 错误提示:'.$string);
        exit('<h3 style="text-align: center">站长正在加紧处理请稍候再试</h3>');
    }
}
function Encrypt($password)
{
    return md5(sha1(crypt($password,'MiloCore')));
}
function CreateEmptyIndexHtml(string $dir=null)
{
    if ($dir==null)
    {
        $dir=getcwd();
    }
    $it=new  \RecursiveDirectoryIterator($dir);
    $it->rewind();
    while ($it->valid())
    {
        if ($it->hasChildren()&&substr($it->getFilename(),0,1)!='.')
        {
            if (!file_exists($it->getRealPath().'/demo.html'))
            {
                file_put_contents($it->getRealPath().'/demo.html','');
            }
            CreateEmptyIndexHtml($it->getRealPath());
        }
        $it->next();
    }
}
function DirList(string $dir)
{
    $it=new  \RecursiveDirectoryIterator($dir);
    $it->rewind();
    while ($it->valid())
    {

        if ($it->hasChildren()&&substr($it->getFilename(),0,1)!='.')
        {
            PrintFm($it->getRealPath());
            DirList($it->getRealPath());
        }
        $it->next();
    }
}
function UnicodeDecode($name)
{
    $json = '{"str":"'.$name.'"}';
    $arr = json_decode($json,true);
    return empty($arr)?'':$arr['str'];
}
function SetToken()
{
    $token=md5(microtime(true));
    $_SESSION['token']=$token;
    return $token;
}
function CheckToken()
{
    if(isset($_POST['token'])&&isset($_SESSION['token']))
    {
        if ($_POST['token']==$_SESSION['token'])
        {
            unset($_SESSION['token']);
            return true;
        }
    }
    return false;
}
function NumOfArray($arrays)
{
    $num=0;
    if(!is_array($arrays))
    {
        return 0;
    }
    {
        foreach ($arrays as $array)
        {
            if(is_array($array))
            {
                $t=NumOfArray($array);
                if ($t>$num)
                {
                    $num=$t;
                }
            }
        }
        return $num+1;
    }
}