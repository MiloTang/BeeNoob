<?php
namespace Core\Libs;
interface Image
{
    public function openImage($src);
    public function operateImage($src);
    public function outputImage($outMethod);
    public function destroyImage();
}