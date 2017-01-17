<?php
/**
 * Created by PhpStorm.
 * User: milo
 * Date: 1/11/2017
 * Time: 9:10 AM
 */
namespace Core\Libs;
class UploadFile
{
    private static $_instance;
    protected static $filename=array();
    protected $fileInfo;
    protected $msg;
    protected $allowedType;
    protected $maxSize;
    protected $ext;
    private function __construct()
    {

    }
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __clone()
    {
    }
    public function uploads($allowedType='Image',$maxSize=1046576)
    {

        $this->allowedType=$allowedType;
        $this->maxSize=$maxSize;
        foreach ($_FILES as $files)
        {
            if (is_array($files['name']))
            {
                $i=0;
                foreach ($files['name'] as $item => $value)
                {
                    $fileInfo['name']=$files['name'][$i];
                    $fileInfo['type']=$files['type'][$i];
                    $fileInfo['tmp_name']=$files['tmp_name'][$i];
                    $fileInfo['error']=$files['error'][$i];
                    $fileInfo['size']=$files['size'][$i];
                    $this->fileInfo=$fileInfo;
                    $this->uploadCheck();
                    $i++;
                }
            }
            else
            {
                $fileInfo=$files;
                $this->fileInfo=$fileInfo;
                $this->uploadCheck();
            }
        }
    }
    protected function uploadCheck()
    {
        if (!is_null($this->fileInfo))
        {
            if ($this->checkError()&&$this->checkSize()&&$this->checkType()&&$this->checkUploadType())
            {
               $this->upload();
            }
        }
        else
        {
            $this->msg='文件并不存在';
        }
        PrintFm($this->msg);
    }
    protected function checkError()
    {
        if ($this->fileInfo['error'] > 0) {
            switch ($this->fileInfo['error']) {
                case 1:
                    $this->msg= '上传的文件超过服务器限制';
                    break;
                case 2:
                    $this->msg='超过了表单中的最大值';
                    break;
                case 3:
                    $this->msg = '文件部分上传';
                    break;
                case 4:
                    $this->msg = '没有选择上传文件';
                    break;
                case 6:
                    $this->msg = '没有找到临时目录';
                    break;
                case 7:
                    $this->msg = '文件不可写';
                    break;
                case 8:
                    $this->msg = '由于PHP扩展程序中断文件上传';
                    break;
                default:
                    $this->msg = '其他错误';
            }
            return false;
        }
        return true;
    }
    protected function checkSize()
    {
        if ($this->fileInfo['size'] > $this->maxSize)
        {
           $this->msg=$this->fileInfo['name'].'文件上传过大';
            return false;
        }
        return true;
    }
    protected function checkType()
    {
        $this->ext = strtolower(pathinfo($this->fileInfo['name'], PATHINFO_EXTENSION));
        $conf = Conf::getInstance()->conf();
        if ($this->ext!='txt')
        {
            $file = fopen($this->fileInfo['tmp_name'], 'rb');
            if (!$file)
            {
                $this->msg=$this->fileInfo['name'].'读取文件失败';
                return false;
            }
            $bin = fread($file, 15);
            fclose($file);
            $real = false;
            $realType = '';
            $realTypeList=$this->realTypeList();
            foreach ($realTypeList as $v) {
                $len = strlen(pack('H*', $v[0]));
                $rst = substr($bin, 0, intval($len));
                if (strtolower($v[0]) == strtolower(array_shift(unpack('H*', $rst))))
                {
                    if ($v[1] == $this->ext) {
                        $real = true;
                    }
                    $realType = $v[1];
                }
            }
            if (!$real)
            {
                $this->msg=$this->fileInfo['name'].'文件禁止上传其真实格式:'.$realType;
                return false;
            }
        }
        if (isset($conf['UP_TYPE'][$this->allowedType]))
        {
            if (!in_array($this->ext, $conf['UP_TYPE'][$this->allowedType]))
            {
                $this->msg=$this->fileInfo['name'].'不允许此格式';
                return false;
            }
        }
        else
        {
            $this->msg='请检查配置文件';
            return false;
        }
        return true;
    }
    protected function checkUploadType()
    {
        if (!is_uploaded_file($this->fileInfo['tmp_name'])) {
            $this->msg=$this->fileInfo['name'].'不是POST方式传递过来的';
            return false;
        }
        return true;
    }
    protected function upload()
    {
        $dir = WEB_PATH . 'Public/Upload/'.$this->allowedType;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $filename = md5(uniqid(microtime(true), true)) . '.' . $this->ext;
        if (move_uploaded_file($this->fileInfo['tmp_name'],$dir.'/'.$filename)) {
            self::$filename[$filename] = WEB_NAME.'/Public/Upload/'.$this->allowedType.'/'.$filename;
            $this->msg=$this->fileInfo['name'].'上传成功';
        } else {
            $this->msg=$this->fileInfo['name'].'上传失败';
        }
    }

    /**
     * @return mixed
     * 当upload 返回true时　可以调用
     */
    public function getFileName()
    {
        return self::$filename;
    }
    protected function realTypeList()
    {
        return array(array('FFD8FFE000', 'jpg'),
            array('89504E47', 'png'),
            array('47494638', 'gif'),
            array('49492A00', 'tif'),
            array('424D', 'bmp'),
            array('41433130', 'dwg'),
            array('38425053', 'psd'),
            array('7B5C727466', 'rtf'),
            array('3C3F786D6C', 'xml'),
            array('68746D6C3E', 'html'),
            array('44656C69766572792D646174', 'eml'),
            array('CFAD12FEC5FD746F', 'dbx'),
            array('2142444E', 'pst'),
            array('D0CF11E0', 'doc'),
            array('D0CF11E0', 'xls'),
            array('5374616E64617264204A', 'mdb'),
            array('FF575043', 'wpd'),
            array('252150532D41646F6265', 'ps'),
            array('252150532D41646F6265', 'eps'),
            array('255044462D312E', 'pdf'),
            array('E3828596', 'pwl'),
            array('504B0304', 'zip'),
            array('504b0304140006000800000021','docx'),
            array('504b0304140006000800000021','xlsx'),
            array('52617221', 'rar'),
            array('57415645', 'wav'),
            array('41564920', 'avi'),
            array('2E7261FD', 'ram'),
            array('2E524D46', 'rm'),
            array('000001BA', 'mpg'),
            array('000001B3', 'mpg'),
            array('6D6F6F76', 'mov'),
            array('3026B2758E66CF11', 'asf'),
            array('4D546864', 'mid'));
    }
}
