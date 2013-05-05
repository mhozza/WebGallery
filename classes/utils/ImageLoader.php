<?php
require_once 'classes/utils/Database.php';
require_once 'classes/model/GalleryItem.php';
require_once 'classes/model/Photo.php';

class ImageLoader
{
  private $cache_path = "";
  private $cache_widths = array(900,1200,1800);
  private $cache_heights = array(600,800,1200);
  private $cache_names = array("small.jpg", "medium.jpg", "large.jpg");

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

  }

  private function updatePhotoImages($photo)
  {

  }

  public function getThumbnail($galleryItem)
  {
    if($galleryItem.class=="Album")
    {
      if(!checkAlbumActuality($galleryItem))
      {
        updateAlbumThumbnail($galleryItem);
      }
      $thumb = Database::getAlbumThumbnail($galleryItem.id);
      return $thumb;
    }
    else
    {
      if(!checkPhotoActuality($galleryItem))
      {
        updatePhotoImages($galleryItem);
      }
      $thumb = Database::getPhotoThumbnail($galleryItem.id);
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
