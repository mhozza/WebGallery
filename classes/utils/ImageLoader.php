<?php
require_once 'classes/utils/Database.php';
require_once 'classes/utils/albumthumbnail.php';
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
  private $cache_widths = array(900,1200,1800);
  private $cache_heights = array(600,800,1200);

  private $albumThumbnailGenerator;

  public function __construct()
  {
    $this->albumThumbnailGenerator = new AlbumThumbnailGenerator();
  }

  private function checkAlbumActuality($album)
  {
    return false;
  }

  private function checkPhotoActuality($photo)
  {
    return false;
  }

  private function updateAlbumThumbnail($album)
  {
    $this->albumThumbnailGenerator->generateThumbnail($album->path, $this->cache_path.'/'.$this->album_dir.'/'.$album->id.'_'.$this->album_thumbnail_name, 300, 200);
  }

  private function updatePhotoImages($photo)
  {

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
      $thumb = $this->getImageFromFile($this->cache_path.'/'.$this->album_dir.'/'.$galleryItem->id.'_'.$this->album_thumbnail_name);
      return $thumb;
    }
    else
    {
      if(!$this->checkPhotoActuality($galleryItem))
      {
        $this->updatePhotoImages($galleryItem);
      }
      $thumb = $this->getImageFromFile($this->cache_path.'/'.$this->photo_dir.'/'.$galleryItem->id.'_'.$this->photo_thumbnail_name);
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

  }

  public function clearAll()
  {

  }
}
?>
