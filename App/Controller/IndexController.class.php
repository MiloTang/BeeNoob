<?php
namespace App\Controller;
use Core\Libs\BaseController;
use Core\libs\Log;
use Core\libs\Model;
use Core\Libs\UploadFile;

class IndexController extends BaseController
{
   public function index()
   {
      $redis = new \Redis();
      $redis->connect('127.0.0.1', 6379);
      $arr=array(2,3,'4');
     // $redis->lpush("tutorial-list", $arr);
      // 获取存储的数据并输出
    //  $arList = $redis->keys('tutorial-name');
     // $arList = $redis->hMset('sql', $arr);

      //$this->assign('token',SetToken());
     //  $this->display('index.html');
      $rst=Model::getInstance()->select('user')->fetchAll();

      $memcached = new \Memcached();
      $memcached->addServer('127.0.0.1',11211);
      PrintFm($redis->getKeys('mysql')[0]);


   }

   public function upload()
   {
      if(CheckToken())
      {
         $up=UploadFile::getInstance();
         if ($up->uploads('Text'))
         {
            var_dump($up->getFileName());
         }
      }
      else
      {
         GetError('请勿重复提交');
      }
   }
}