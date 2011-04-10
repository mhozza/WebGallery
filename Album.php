require_once 'GalleryItem.php';
require_once 'Privileges.php';


/**
 * class Album
 * 
 */
class Album extends GalleryItem
{

  /** Aggregations: */

  /** Compositions: */

   /*** Attributes: ***/

  /**
   * 
   * @access public
   */
  public $privileges;


  /**
   * 
   *
   * @return 
   * @access public
   */
  public function getItems( ) {
  } // end of member function getItems

  /**
   * returns parent album(directory)
   *
   * @return GalleryItem
   * @access public
   */
  public function getParent( ) {
  } // end of member function getParent





} // end of Album
?>
