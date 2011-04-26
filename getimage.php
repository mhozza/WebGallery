<?php

function resizejpg($imagefile,$w,$h,$q)
{
    if(substr($imagefile, 0, 1) != '.' || strstr($imagefile, "..")) {
        echo "Illegal access!";exit;
    }

    // seems gif not supported by GD now
    $ext = substr($imagefile, -3);
    if(strtolower($ext) == "gif") {
      if (!$src_img = imagecreatefromgif($imagefile)) {
        echo "Error opening Image file!";exit;
      }
    } else if(strtolower($ext) == "jpg") {
      if (!$src_img = imagecreatefromjpeg($imagefile)) {
        //echo "Error opening Image file!";exit;
      }
    } else {
      echo "Error file type not supported!";exit;
    }

    $hw = getimagesize($imagefile);
    $old_h = $hw["1"];
    $old_w = $hw["0"];
	$zoom = max($old_h/$h,$old_w/$w);
	
    $new_w = $old_w/$zoom;
    $new_h = $old_h/$zoom;
    
    // truecolor supported only in GD 2.0 or later
    $dst_img = @imagecreatetruecolor($new_w, $new_h);
    if(!$dst_img) {
      $dst_img = imageCreate($new_w, $new_h);
    }

    imagecopyresized($dst_img,$src_img,0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img));
    imagejpeg($dst_img,NULL,$q);
    ImageDestroy($src_img);
    ImageDestroy($dst_img);
}

header("Content-type: image/jpeg");

if(isset($_GET['image'])) $image = $_GET['image'];
else $image = "";
if(isset($_GET['size'])) $size = $_GET['size'];
else $size = "";
if(isset($_GET['w'])) $w = $_GET['w'];
else $w = "";
if(isset($_GET['h'])) $h = $_GET['h'];
else $h = "";
if(isset($_GET['q'])) $q = $_GET['q'];
else $q = "";


//$small_resize = $_GET['small'];
if ($w == ""){$w = $size;}
if ($h == ""){$h = $size;}
if ($w == ""){$w = "200";}
if ($h == ""){$h = "200";}
if ($q == ""){$q = 100;}
resizejpg("./".$image,$w,$h,$q);

?>
