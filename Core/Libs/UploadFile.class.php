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
    public function upload(string $type='image',int $size=102400)
    {
        $conf=Conf::getInstance()->conf();
        $types=isset($conf['UP_TYPE']['type'])?$conf['UP_TYPE']['type']:null;
        $temp = explode('.', $_FILES['file']['name']);
        $ext=end($temp);
        if (($_FILES['file']['size'] < $size) && in_array($ext,$types))
        {
            if ($_FILES['file']['error'] > 0)
            {
                echo '错误：: ' . $_FILES['file']['error'] . '<br>';
            }
            else
            {
                if ($type=='image')
                {
                    if (file_exists(WEB_PATH.'Public/Upload/Image/'.$_FILES['file']['name']))
                    {
                        echo $_FILES['file']['name'] . ' 文件已经存在。 ';
                    }
                    else
                    {
                        move_uploaded_file($_FILES['file']['tmp_name'], WEB_PATH.'Public/Upload/Image/'.$_FILES['file']['name']);
                        self::$_filename='/'.WEB_NAME.'/Public/Upload/Image/'.$_FILES['file']['name'];
                    }
                }
                elseif($type=='text')
                {
                    if (file_exists(WEB_PATH.'Public/Upload/Text/'.$_FILES['file']['name']))
                    {
                        echo $_FILES['file']['name'] . ' 文件已经存在。 ';
                    }
                    else
                    {
                        move_uploaded_file($_FILES['file']['tmp_name'], WEB_PATH.'Public/Upload/Text/'.$_FILES['file']['name']);
                        self::$_filename='/'.WEB_NAME.'/Public/Upload/Image/'.$_FILES['file']['name'];
                    }
                }
                else
                {
                    PrintFm('文件类型有误');
                }
            }
        }
        else
        {
            PrintFm('文件格式有误或者尺寸太大');
        }
    }

    public function getFilename()
    {
        if (self::$_filename!=null)
        {
            return self::$_filename;
        }
        else
        {
            exit('文件名不存在');
        }
    }
}
