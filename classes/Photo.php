<?php
require_once 'GalleryItem.php';
require_once 'ImageResizer.php';
require_once 'Exif.php';

define('PP_PUBLIC',0);
define('PP_LOGGED',1);
define('PP_PRIVATE',2);


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
