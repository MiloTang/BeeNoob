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
        if (!(self::$_instance instanceof self))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    private function __clone()
    {
    }
    public function upload(int $size=102400)
    {

        if ($_FILES['file']['error'] > 0)
        {
            switch($_FILES['file']['error'])
            {
                case 1:
                    GetError('上传的文件超过服务器限制');
                    break;
                case 3:
                    GetError('文件部分上传');
                    break;
                case 4:
                    GetError('没有选择上传文件');
                    break;
                case 7:
                case 8:
                    GetError('系统错误');
                    break;
                default:
                    GetError('其他错误');
            }
        }
        else
        {
            $conf=Conf::getInstance()->conf();
            $ext=strtolower(pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION));
            //$ext = strtolower(end(explode('.', $_FILES['file']['name'])));
            $allowed=false;
            $type='';
            foreach ($conf['UP_TYPE'] as $item=>$value)
            {
                $types[$item]=$conf['UP_TYPE'][$item];
                if (in_array($ext,$types[$item]))
                {
                    $allowed=true;
                    $type=$item;
                }
            }
            if ($_FILES['file']['size'] > $size)
            {
                GetError('文件上传过大');
            }
            if (!$allowed)
            {
                GetError('不允许此格式');
            }
            if (!is_uploaded_file($_FILES['file']['tmp_name']))
            {
                GetError('不是POST方式传递过来的');
            }
            $dir=WEB_PATH.'Public/Upload/'.$type;
            $filename=md5(microtime(true)).'.'.$ext;
            if (!is_dir($dir))
            {
                mkdir($dir, 0777, true);
            }
            if (file_exists($dir.'/'.$filename))
            {
                GetError($_FILES['file']['name'] . ' 文件已经存在。 ');
            }
            else
            {
                if (move_uploaded_file($_FILES['file']['tmp_name'], $dir.'/'.$filename))
                {
                    self::$_filename=WEB_NAME.'/Public/Upload/'.$type.'/'.$filename;
                    return true;
                }
                else
                {
                    GetError('上传失败');
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
}
