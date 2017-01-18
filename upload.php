<?php
if(isset($_FILES['file']['tmp_name']))
{
  //  header('Content-type:image/gif');
    $image = new Imagick($_FILES['file']['tmp_name']);
    $image = $image->coalesceImages();
    foreach ($image as $frame) {
        $frame->thumbnailImage(120, 120,true);
    }
    $image = $image->optimizeImageLayers();
    header( "Content-Type: image/gif" );
    echo( $image->getImagesBlob() );
    //  $image->writeImages('new.gif', true);

}

