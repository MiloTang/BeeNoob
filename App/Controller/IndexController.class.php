<?php
namespace App\Controller;
use Core\Libs\BaseController;
use Core\Libs\UploadFile;

class IndexController extends BaseController
{
   public function index()
   {
      $this->assign('token',SetToken());
      $this->display('index.html');
   }
   public function upload()
   {
      $up=UploadFile::getInstance();
      if ($up->upload())
      {
         echo $up->getFilename();
      }


   }
}