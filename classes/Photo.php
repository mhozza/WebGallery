<?php
require_once 'GalleryItem.php';
require_once 'ImageResizer.php';
require_once 'Exif.php';


/**
 * class Photo
 * 
 */
class Photo extends GalleryItem
{

 
  /**
   * 
   * @access private
   */
  private $exifData = NULL;

  function __construct($info) {    
    if(is_array($info))
    {      
      parent::__construct($info['id'],$info['caption'],$info['path']);    
      $this->reloadrating();
      $this->reloadComments();
    }    
    else
    {
      throw new RuntimeException('Info is not an array');
    }
  }   

  /**
   * 
   *
   * @return Exif
   * @access public
   */
  public function getExifData( ) {
  } // end of member function getExifData

  public function reloadComments()
  {
    $this->comments = Database::getComments($this->id);
  }

  public function reloadRating()
  {
    $this->rating = Database::getRating($this->id);
    if(!$this->rating) $this->rating = 0;
  }

} // end of Photo
?>
