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
    if($info!=NULL)
    {      
      parent::__construct($info['id'],$info['caption'],$info['path']);    
    }
    $this->rating = Database::getRating($this->id);
    if(!$this->rating) $this->rating = 0;
    $this->comments = Database::getComments($this->id);
  }   

  /**
   * 
   *
   * @return Exif
   * @access public
   */
  public function getExifData( ) {
  } // end of member function getExifData





} // end of Photo
?>
