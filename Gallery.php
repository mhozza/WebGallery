require_once 'Database.php';
require_once 'Album.php';
require_once 'Photo.php';


/**
 * class Gallery
 * 
 */
class Gallery
{

  /** Aggregations: */

  /** Compositions: */

   /*** Attributes: ***/

  /**
   * 
   * @access private
   */
  private $currentIndex = 0;

  /**
   * 
   * @access private
   */
  private $currentAlbum = GALLERY_ROOT;


  /**
   * 
   *
   * @return 
   * @access public
   */
  public function getPhotos( ) {
  } // end of member function getPhotos

  /**
   * 
   *
   * @return Photo
   * @access public
   */
  public function getCurrentPhoto( ) {
  } // end of member function getCurrentPhoto

  /**
   * 
   *
   * @param Album album 

   * @return 
   * @access public
   */
  public function setAlbum( $album ) {
  } // end of member function setAlbum

  /**
   * 
   *
   * @return 
   * @access public
   */
  public function getItems( ) {
  } // end of member function getItems





} // end of Gallery
?>
