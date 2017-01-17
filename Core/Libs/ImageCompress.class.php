<?php
namespace Core\Libs;
class ImageCompress implements Image
{
    protected $path;
    protected $image;
    protected $imageInfo;
    protected $imageType;
    protected $imageSuffix;
    protected $imageX;
    protected $imageY;
    protected $image_thumb;
    public function openImage($src)
    {
        // TODO: Implement openImage() method.
        $this->path=substr($src,0,strlen($src)-strlen(strstr($src, '.')));
        $this->imageSuffix=substr(strstr($src, '.'),1);
        $info=getimagesize($src);
        $this->imageX=$info[0];
        $this->imageY=$info[1];
        $this->imageInfo=$info;
        $type=image_type_to_extension($info[2],false);
        $this->imageType=$type;
        $createImageType='imagecreatefrom'.$type;
        $this->image=$createImageType($src);
    }
    public function operateImage($src=null)
    {
        // TODO: Implement operateImage() method.
        $this->image_thumb=imagecreatetruecolor($this->imageX/2,$this->imageY/2);
        imagecopyresampled($this->image_thumb,$this->image,0,0,0,0,$this->imageX/2,$this->imageY/2,$this->imageX,$this->imageY);
    }
    public function outputImage($outMethod)
    {
        // TODO: Implement outputImage() method.
        $imageType='image'.$this->imageType;
        if ($outMethod=='web')
        {
            header('Content-type:',$this->imageInfo['mime']);
            $imageType='image'.$this->imageType;
            $imageType($this->image_thumb);
        }
        elseif ($outMethod=='save')
        {
            $imageType($this->image_thumb,$this->path.'ImageCompress.'.$this->imageSuffix);
        }
        else
        {
            GetError('你想干啥');
        }
    }
    public function destroyImage()
    {
        // TODO: Implement destroyImage() method.
        imagedestroy($this->image);
        imagedestroy($this->image_thumb);
    }
}