<?php

function DateCmp($a, $b)
{
  return ($a[1] < $b[1]) ? 1 : 0;
}

function SortByDate(&$Files)
{
  usort($Files, 'DateCmp');
}

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
        if (in_array( $file, $this->ignore ) || ($filesonly && !is_file($file)))
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
      
  private function generate_album($album,$w,$h, $path = null)
  {
      $photos = $this->dirlist($album, false);
      $rot = array(0,10,350);
      $dst_img = array();
      $dst_img_w = array();
      $dst_img_h = array();
      $cnt = 0;
      foreach($photos as $imagefile)
      {
        $imagefile=$album.'/'.$imagefile;
        if($cnt>=3) break;
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

        $old_h = imagesy($src_img);
        $old_w = imagesx($src_img);
        $zoom = max($old_h/($h/2),$old_w/($w/2));

        $new_w = $old_w/$zoom;
        $new_h = $old_h/$zoom;
        // truecolor supported only in GD 2.0 or later
        $dst_img[$cnt] = @imagecreatetruecolor($new_w, $new_h);
        if(!$dst_img[$cnt]) {
          $dst_img[$cnt] = imageCreate($new_w, $new_h);
        }

        $border = 4;
        $transparent = imagecolorallocatealpha($dst_img[$cnt],0,0,0,127);
        $bgcolor = imagecolorallocate($dst_img[$cnt],255,255,255);

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
      
      if(!imagepng($final_img, $path))
      {
        die("Faild to save $path.");
      }
      ImageDestroy($final_img);
  }

  public function generateThumbnail($albumPath, $thumbnailPath, $w, $h)
  {
      // if(substr($albumPath, 0, 1) != '.' || strstr($albumPath, "..")) {
      //   die("Illegal access!");
      // }
      $this->generate_album($albumPath, $w, $h, $thumbnailPath);
  }
}
