<?php
require_once 'classes/utils/Database.php';
require_once 'classes/model/GalleryItem.php';
require_once 'classes/model/Photo.php';


/**
 * class ImageResizer
 * 
 */
class ImageResizer
{
  /**
   * @param GalleryItem galleryItem this could be used for both: album and photo thumbnail
   * @return 
   * @static
   * @access public
   */
  public static function getThumbnail( $galleryItem ) {
  } // end of member function getThumbnail

  /**
   * @param Photo photo 
   * @return 
   * @static
   * @access public
   */
  public static function getPicture( $photo ) {
  } // end of member function getPicture

} // end of ImageResizer
?>
