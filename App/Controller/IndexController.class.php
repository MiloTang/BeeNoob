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
      $arr=array(
          array('uname'=>'阿一','usex'=>'男','udate'=>date('Y-m-d h:s:m')),
          array('uname'=>'阿二','usex'=>'男','udate'=>date('Y-m-d h:s:m')),
          array('uname'=>'阿五','usex'=>'男','udate'=>date('Y-m-d h:s:m'))
      );
      echo $model->delete('user')->where('uid=:uid',array(':uid'=>'72'))->affectedRows();
      $model->close();
      //$this->assign('token',SetToken());
    //   $this->display('index.html');
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