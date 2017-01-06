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
        echo $string;
        $log->log($string);
        $url='http://'.$_SERVER['SERVER_NAME'].'/'.InUrl('index/not');
        JumpUrl($url);
    }
}
function Encrypt($password)
{
    return md5(sha1(crypt($password,'MiloCore')));
}
function CreateEmptyIndexHtml(string $dir)
{
    $it=new  \RecursiveDirectoryIterator($dir);
    $it->rewind();
    while ($it->valid())
    {
        if ($it->hasChildren()&&substr($it->getFilename(),0,1)!='.')
        {
            file_put_contents($it->getRealPath().'/index.html','');
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