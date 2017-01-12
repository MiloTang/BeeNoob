<?php

    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp); 
    if (($_FILES["file"]["type"] == "image/gif")
        && ($_FILES["file"]["size"] < 800000))
    {
        if ($_FILES["file"]["error"] > 0)
        {
            echo "错误：: " . $_FILES["file"]["error"] . "<br>";
        }
        else
        {
            $image = new Imagick($_FILES['file']['tmp_name']);
            $image = $image->coalesceImages();
            foreach ($image as $item => $frame) {
                $frame->thumbnailImage(120,120,true);
            }
            $image = $frame->optimizeImageLayers();
            header( "Content-Type: image/gif" );
            echo ($image->getImagesBlob());
          //  unlink($filename);
        }
    }
    else
    {
        echo "非法的文件格式或者文件太大";
    }
