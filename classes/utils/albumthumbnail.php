<?php

class AlbumThumbnailGenerator
{
  private $ignore = array( 'cgi-bin', '.', '..', '.svn','akcie','index.htm','.htaccess','.git' );

  private function dirList($directory, $filesonly=false)
  {
      $results = array();
      $handler = opendir($directory);

      global $ignore;
      while ($file = readdir($handler))
      {
        if (in_array( $file, $ignore ) || ($filesonly && !is_file($file)))
          continue;

        $fm = filemtime("$directory/$file");
        $results[] = array($file, $fm);
      }
      closedir($handler);

      SortByDate($results);
      $vysledok = array();
      foreach ($results as $result)
      {
        $vysledok[]=$result[0];
      }
      return $vysledok;
  }

  private function DateCmp($a, $b)
  {
    return ($a[1] < $b[1]) ? 1 : 0;
  }

  private function SortByDate(&$Files)
  {
    usort($Files, "DateCmp");
  }
      
  private function generate_album($album,$w,$h, $path = null)
  {
  //     echo $album;
      if(substr($album, 0, 1) != '.' || strstr($album, "..") || strstr($album,'akcie')) {
        die("Illegal access!");
      }
      $album=$album;
      $photos = dirlist($album, false);
      $rot = array(0,10,350);
      $dst_img = array();
      $dst_img_w = array();
      $dst_img_h = array();
      $cnt = 0;
      foreach($photos as $imagefile)
      {
        $imagefile=$album.'/'.$imagefile;
        if($cnt>=3) break;
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

        //$hw = getimagesize($imagefile);
        $old_h = imagesy($src_img);//$hw["1"];
        $old_w = imagesx($src_img);// $hw["0"];
        $zoom = max($old_h/($h/2),$old_w/($w/2));

        $new_w = $old_w/$zoom;
        $new_h = $old_h/$zoom;
        // truecolor supported only in GD 2.0 or later
        $dst_img[$cnt] = @imagecreatetruecolor($new_w, $new_h);
        if(!$dst_img[$cnt]) {
          $dst_img[$cnt] = imageCreate($new_w, $new_h);
        }

  //       imageantialias ( $dst_img[$cnt] ,true );
        $border = 4;
        $transparent = imagecolorallocatealpha($dst_img[$cnt],0,0,0,127);
        $bgcolor = imagecolorallocate($dst_img[$cnt],255,255,255);

        //imagecolortransparent($dst_img[$cnt], $bgcolor);
        //imagefilledrectangle($dst_img[$cnt],0,0,$new_w,$new_h,$bgcolor);
        //imagecopyresized($dst_img[$cnt],$src_img,$border,$border,0,0,$new_w-2*$border,$new_h-2*$border,imagesx($src_img),imagesy($src_img));
        imagecopyresized($dst_img[$cnt],$src_img,0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img));
        $dst_img[$cnt] = imagerotate($dst_img[$cnt],$rot[$cnt],$transparent);
        $old_h = imagesy($dst_img[$cnt]);
        $old_w = imagesx($dst_img[$cnt]);
        $zoom = max($old_h/($h/2),$old_w/($w/2));
        $dst_img_w[$cnt] = $old_w/$zoom;
        $dst_img_h[$cnt] = $old_h/$zoom;
        ImageDestroy($src_img);
        $cnt++;
      }
      $final_img = @imagecreatetruecolor($w, $h);
      if(!$final_img) {
        $final_img = imageCreate($w, $h);
      }
      imagealphablending($final_img,false);
      imagesavealpha($final_img,true);
      $bgcolor = imagecolorallocatealpha($final_img,0,0,0,127);
  //     imagecolortransparent($final_img, $bgcolor);

      imagefilledrectangle($final_img,0,0,$w,$h,$bgcolor);

      $start_x = array($w/4,0,$w/2);
      $start_y = array($h/8,3*$h/8,3*$h/8);
      for($i=2;$i>=0;$i--)
      {
        if(isset($dst_img[$i]))
        {
          imagecopyresized($final_img,$dst_img[$i],$start_x[$i]+($w/2-$dst_img_w[$i])/2,$start_y[$i]+($h/2-$dst_img_h[$i])/2,0,0,$dst_img_w[$i],$dst_img_h[$i],imagesx($dst_img[$i]),imagesy($dst_img[$i]));
          ImageDestroy($dst_img[$i]);
          }
      }
      
      imagepng($final_img, $thumbnailPath);//,NULL,$q);     
      ImageDestroy($final_img);
  }

  // header("Content-type: image/png");

  // if(isset($_GET['image'])) $image = $_GET['image'];
  // else $image = "";
  // if(isset($_GET['size'])) $size = $_GET['size'];
  // else $size = "";
  // if(isset($_GET['w'])) $w = $_GET['w'];
  // else $w = "";
  // if(isset($_GET['h'])) $h = $_GET['h'];
  // else $h = "";
  // if(isset($_GET['q'])) $q = $_GET['q'];
  // else $q = "";


  // //$small_resize = $_GET['small'];
  // if ($w == ""){$w = $size;}
  // if ($h == ""){$h = $size;}
  // if ($w == ""){$w = "200";}
  // if ($h == ""){$h = "200";}
  // if ($q == ""){$q = 100;}
  // generate_album("./".$image,$w,$h);

  public function generateThumbnail($albumPath, $thumbnailPath, $w, $h)
  {
    generate_album($albumPath, $w, $h, $thumbnailPath);
  }
}
