<?php
/**
 * Created by PhpStorm.
 * User: milo
 * Date: 11/4/2016
 * Time: 4:03 PM
 */
namespace Core\Libs;
defined('CORE_PATH') or exit();
class VerifyCode {
    private $_code;
    private $_codeLen = 6;
    private $_width = 150;
    private $_height = 50;
    private $_img;
    private $_fontSize = 18;
    private $_fontColor;
    private $_fontFace;
    private static $_instance;
    private function __construct()
    {
        $this->_fontFace="./Core/Libs/font/t1.ttf";
    }
    public static  function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    private function __clone()
    {

    }
    private function _createCode() {

        for ($i = 0; $i < $this->_codeLen; $i++)
        {
            $num=mt_rand(1,3);
            if ($num==1)
            {
                $this->_code .= chr(mt_rand(48,57));
            }
            else if ($num==2)
            {
                $this->_code .= chr(mt_rand(65,90));
            }
            else
            {
                $this->_code .= chr(mt_rand(97,122));
            }

        }
    }
    private function _createBg() {
        $this->_img = imagecreatetruecolor($this->_width, $this->_height);
        $color = imagecolorallocate($this->_img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
        imagefilledrectangle($this->_img,0,$this->_height,$this->_width,0,$color);
    }
    private function _createStr() {
        for ($i=0;$i<$this->_codeLen;$i++)
        {
            $this->_fontColor = imagecolorallocate($this->_img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
            imagettftext($this->_img,$this->_fontSize,mt_rand(-20,20),$i*25,$this->_height / 1.5,$this->_fontColor,$this->_fontFace,$this->_code[$i]);
        }

    }
    private function _createLine() {
        for ($i=0;$i<10;$i++) {
            $color = imagecolorallocate($this->_img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
            imageline($this->_img,mt_rand(0,$this->_width),mt_rand(0,$this->_height),mt_rand(0,$this->_width),mt_rand(0,$this->_height),$color);
        }
        for ($i=0;$i<100;$i++) {
            $color = imagecolorallocate($this->_img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
            imagestring($this->_img,mt_rand(1,5),mt_rand(0,$this->_width),mt_rand(0,$this->_height),'*',$color);
        }
    }
    private function _outPut() {
        header('Content-type:image/png');
        imagepng($this->_img);
        imagedestroy($this->_img);
    }
    public function imgEn(int $len) {
        if ($len<=10&&$len>=1)
        {
            $this->_codeLen=$len;
            $this->_width=$len*25;
            $this->_createBg();
            $this->_createCode();
            $this->_createLine();
            $this->_createStr();
            $this->_outPut();
        }
        else
        {
            exit();
        }

    }
    public function getCode()
    {
        return strtolower($this->_code);
    }

}
