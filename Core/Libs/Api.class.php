<?php
namespace Core\Libs;
defined('CORE_PATH') or exit();
class Api
{
    private static $_instance;
    private $mxl;
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
    public function getApi(int $code,$message='',$data=array(),string $type='json')
    {
        if (!is_numeric($code))
        {
            GetError('code must be int type');
        }
        $result=array(
            'code'=>$code,
            'message'=>$message,
            'data'=>$data
        );
        if ($type=='json')
        {
            $this->getJson($code,$message,$data);
        }
        elseif($type=='xml')
        {
            $this->getXml($code,$message,$data);
        }
        elseif($type=='array')
        {
            PrintFm($result);
        }
        else
        {
            GetError('格式类型有误,只支持json和xml类型');
        }
    }
    private  function getJson(int $code,$message='',$data=array())
    {
        $json=array(
            'code'=>$code,
            'message'=>$message,
            'data'=>$data
        );
        echo json_encode($json);
    }
    private  function getXml(int $code,string $message='',array $data)
    {
        $xmlArr=array(
            'code'=>$code,
            'massage'=>$message,
            'data'=>$data
        );
        header('Content-Type:text/xml');
        $xml='<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL;
        $xml.='<root>'.PHP_EOL;
        $xml.=$this->xmlArr($xmlArr);
        $xml.='</root>';
        echo $xml;
    }
    private  function xmlArr(array $data):string 
    {
        $xml='';
        $attr='';
        foreach ($data as $key=>$value)
        {

            if(is_numeric($key))
            {
                $attr="id='{$key}'";
                $key='item';
            }
            $xml.="<$key {$attr}>";
            $xml.=is_array($value)?$this->xmlArr($value):$value;
            $xml.='</'.$key.'>'.PHP_EOL;
        }
        return $xml;
    }
}