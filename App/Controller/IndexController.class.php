<?php
namespace App\Controller;
use Core\Libs\BaseController;
use Core\libs\Model;
use Core\Libs\UploadFile;

class IndexController extends BaseController
{
   public function index()
   {


      $model=Model::getInstance();

      //$this->assign('token',SetToken());
       $this->display('index.html');
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