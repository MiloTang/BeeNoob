<?php
namespace Core\Libs;
class ImageWatermark implements Image
{
    protected $path;
    protected $image;
    protected $imageInfo;
    protected $imageType;
    protected $imageSuffix;
    protected $imageX;
    protected $imageY;
    protected $waterImage;
    protected $waterImageX;
    protected $waterImageY;
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
    public function operateImage($src)
    {
        // TODO: Implement operateImage() method.
        $info=getimagesize($src);
        $type=image_type_to_extension($info[2],false);
        $this->waterImageX=$info[0];
        $this->waterImageY=$info[1];
        $createImageType='imagecreatefrom'.$type;
        $this->waterImage=$createImageType($src);
        imagecopymerge($this->image,$this->waterImage,$this->imageX/2,$this->imageY/2,0,0,$this->waterImageX,$this->waterImageY,100);
    }
    public function outputImage($outMethod='web')
    {
        // TODO: Implement outputImage() method.
        $imageType='image'.$this->imageType;
        if ($outMethod=='web')
        {
            header('Content-type:',$this->imageInfo['mime']);
            $imageType='image'.$this->imageType;
            $imageType($this->image);
        }
        elseif ($outMethod=='save')
        {
            $imageType($this->image,$this->path.'Watermark.'.$this->imageSuffix);
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
        imagedestroy($this->waterImage);
    }
}