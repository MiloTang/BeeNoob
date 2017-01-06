<?php
namespace App\Controller;
use Core\Libs\BaseController;
class IndexController extends BaseController
{
   public function index()
   {
      $this->display('index.html');
   }
}