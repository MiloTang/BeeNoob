<?php
namespace Core\Libs;
defined('CORE_PATH') or exit();
interface Image
{
    public function openImage($src);
    public function operateImage($src);
    public function outputImage($outMethod);
    public function destroyImage();
}