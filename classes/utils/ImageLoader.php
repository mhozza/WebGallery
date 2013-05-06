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
  private $cache_widths = array(300,900,1200,1800);
  private $cache_heights = array(200,600,800,1200);

  private $albumThumbnailGenerator;

  public function __construct()
  {
    $this->albumThumbnailGenerator = new AlbumThumbnailGenerator();
  }

  private function getAlbumCachePath($id)
  {
    return $this->cache_path.'/'.$this->album_dir.'/'.$id.'_'.$this->album_thumbnail_name;
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
    return false;
  }

  private function updateAlbumThumbnail($album)
  {
    $this->albumThumbnailGenerator->generateThumbnail($album->path, $this->getAlbumCachePath($album->id), $cache_widths[0], $cache_heights[0]);
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
      $thumb = $this->getImageFromFile($this->getAlbumCachePath($galleryItem->id));
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
