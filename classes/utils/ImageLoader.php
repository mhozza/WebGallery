<?php
require_once 'classes/utils/Database.php';
require_once 'classes/utils/albumthumbnail.php';
require_once 'classes/utils/getimage.php';
require_once 'classes/model/GalleryItem.php';
require_once 'classes/model/Photo.php';


class ImageLoader
{
  private $cache_path = "cache";
  private $album_dir = "albums";
  private $photo_dir = "photos";
  private $album_thumbnail_name = "thumbnail.png";
  private $photo_thumbnail_name = "thumbnail.jpg";
  private $cache_names = array("small.jpg", "medium.jpg", "large.jpg");
  private $cache_widths = array(300,900,1200,1800);
  private $cache_heights = array(200,600,800,1200);
  private $cache_qualities = array(60,70,80,90);

  private $albumThumbnailGenerator;
  private $photoResizer;


  public function __construct()
  {
    $this->albumThumbnailGenerator = new AlbumThumbnailGenerator();
    $this->photoResizer = new PhotoResizer();
  }

  private function getAlbumCachePath($id)
  {
    return $this->cache_path.'/'.$this->album_dir.'/'.$id.'_'.$this->album_thumbnail_name;
  }

  private function getPhotoCachePath($id, $type=0)
  {
    if($type==0)
      return $this->cache_path.'/'.$this->photo_dir.'/'.$id.'_'.$this->photo_thumbnail_name;
  }

  private function checkAlbumActuality($album)
  {
    if (!file_exists($this->getAlbumCachePath($album->id))) {
      return false;
    }
    return true;
  }

  private function checkPhotoActuality($photo)
  {
    if (!file_exists($this->getPhotoCachePath($photo->id,0))) {
      return false;
    }  
    return true;
  }

  private function updateAlbumThumbnail($album)
  {
    $this->albumThumbnailGenerator->generateThumbnail($album->path, $this->getAlbumCachePath($album->id), $this->cache_widths[0], $this->cache_heights[0]);
  }

  private function updatePhotoImages($photo)
  {
    $this->photoResizer->resizeImage($photo->path, $this->getPhotoCachePath($photo->id, 0), $this->cache_widths[0], $this->cache_heights[0], $this->cache_qualities[0]);
  }

  private function getImageFromFile($path)
  {
    return file_get_contents($path);
  } 

  public function getThumbnail($galleryItem)
  {
    if($galleryItem->class=="Album")
    {
      if(!$this->checkAlbumActuality($galleryItem))
      {
        $this->updateAlbumThumbnail($galleryItem);
      }
      $thumb = $this->getImageFromFile($this->getAlbumCachePath($galleryItem->id));
      return $thumb;
    }
    else
    {
      if(!$this->checkPhotoActuality($galleryItem))
      {
        $this->updatePhotoImages($galleryItem);
      }
      $thumb = $this->getImageFromFile($this->getPhotoCachePath($galleryItem->id, 0));
      return $thumb;
    }
  }

  public function getPicture($photo, $w, $h)
  {

  }

  public function cleaPhotoThumbnailAndCache($photo)
  {

  }

  public function cleaAlbumThumbnail($album)
  {
    unlink($this->getAlbumCachePath($album->id));
  }

  //clears all caches in album including album self thumbnail
  public function clearAll($album)
  {
    $this->clearAlbumThumbnail($album);
    /*
    $this->clearAll($subalbum);
    $this->clearPhotoThumbnailAndCache($photo);
    */
  }
}
?>
