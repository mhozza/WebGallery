<?php

class PhotoResizer
{
    function resizejpg($imagefile, $w, $h, $q, $path=null)
    {
        $ext = substr($imagefile, -3);
        if(strtolower($ext) == "gif") {
          if (!$src_img = imagecreatefromgif($imagefile)) {
            echo "Error opening Image file!";exit;
          }
        } else if(strtolower($ext) == "jpg") {
          if (!$src_img = imagecreatefromjpeg($imagefile)) {
            echo "Error opening Image file!";exit;
          }
        } else {
          echo "Error file type not supported!";exit;
        }


        $hw = getimagesize($imagefile);
        $old_h = $hw["1"];
        $old_w = $hw["0"];
        $zoom = max($old_h/$h, $old_w/$w);
        
        $new_w = $old_w/$zoom;
        $new_h = $old_h/$zoom;
        
        // truecolor supported only in GD 2.0 or later
        $dst_img = @imagecreatetruecolor($new_w, $new_h);
        if(!$dst_img) {
          $dst_img = imageCreate($new_w, $new_h);
        }

        imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, imagesx($src_img), imagesy($src_img));
        if(!imagejpeg($dst_img, $path, $q))
        {
            die("Failed to save image $path");
        }
        ImageDestroy($src_img);
        ImageDestroy($dst_img);
    }

    public function resizeImage($photoPath, $thumbnailPath, $w, $h, $q)
    {
      // if(substr($albumPath, 0, 1) != '.' || strstr($albumPath, "..")) {
      //   die("Illegal access!");
      // }
      $this->resizejpg($photoPath, $w, $h, $q, $thumbnailPath);
    }
}
