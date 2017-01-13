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
    private static $_filename;

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
    public function uploads(int $size=102400)
    {
        foreach ($_FILES as $fileInfo)
        {
            if(isset($fileInfo['name']))
            {

            }
            $this->upload($fileInfo,$size);
        }

    }
    private function upload(array $fileInfo,int $size = 102400)
    {
        if ($fileInfo['error'] > 0) {
            switch ($fileInfo['error']) {
                case 1:
                    $msg = '上传的文件超过服务器限制';
                    break;
                case 3:
                    $msg = '文件部分上传';
                    break;
                case 4:
                    $msg = '没有选择上传文件';
                    break;
                case 7:
                case 8:
                    $msg = '系统错误';
                    break;
                default:
                    $msg = '其他错误';
            }
            GetError($fileInfo['name'].$msg);
        } else {
            $conf = Conf::getInstance()->conf();
            $ext = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
            //$ext = strtolower(end(explode('.', $fileInfo['name'])));
            $allowed = false;
            $type = '';
            if ($ext!='txt')
            {
                $file = fopen($fileInfo['tmp_name'], 'rb');
                if (!$file) {
                    GetError($fileInfo['name'].'读取文件失败');
                }
                $bin = fread($file, 15);
                fclose($file);
                $real = false;
                $realType = '';
                foreach (self::_realTypeList() as $v) {
                    $len = strlen(pack('H*', $v[0]));
                    $rst = substr($bin, 0, intval($len));
                    if (strtolower($v[0]) == strtolower(array_shift(unpack('H*', $rst))))
                    {
                        if ($v[1] == $ext) {
                            $real = true;
                        }
                        $realType = $v[1];
                    }
                }
                if (!$real)
                {
                    GetError($fileInfo['name'].'文件禁止上传其真实格式:'.$realType);
                }
            }
            foreach ($conf['UP_TYPE'] as $item => $value) {
                $types[$item] = $conf['UP_TYPE'][$item];
                if (in_array($ext, $types[$item])) {
                    $allowed = true;
                    $type = $item;
                }
            }
            if ($fileInfo['size'] > $size) {
                GetError($fileInfo['name'].'文件上传过大');
            }
            if (!$allowed) {
                GetError($fileInfo['name'].'不允许此格式');
            }
            
            if (!is_uploaded_file($fileInfo['tmp_name'])) {
                GetError($fileInfo['name'].'不是POST方式传递过来的');
            }
            $dir = WEB_PATH . 'Public/Upload/' . $type;
            $filename = md5(uniqid(microtime(true), true)) . '.' . $ext;
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            if (file_exists($dir . '/' . $filename)) {
                GetError($fileInfo['name'] . ' 文件已经存在。 ');
            } else {
                if (@move_uploaded_file($fileInfo['tmp_name'], $dir . '/' . $filename)) {
                    self::$_filename = WEB_NAME . '/Public/Upload/' . $type . '/' . $filename;
                    GetError($fileInfo['name'].'上传成功');
                    return true;
                } else {
                    GetError($fileInfo['name'].'上传失败');
                    
                }

            }
        }
        return false;
    }

    /**
     * @return mixed
     * 当upload 返回true时　可以调用
     */
    public function getFilename()
    {
        return self::$_filename;
    }

    private function _realTypeList()
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
