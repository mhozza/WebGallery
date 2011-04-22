<?php
require_once 'Database.php';
require_once 'Album.php';
require_once 'Photo.php';

define('GALLERY_ROOT','/gallery/');

/**
 * class Gallery
 * 
 */
class Gallery
{
  

  /**
   * 
   * @access private
   */
  private $currentIndex = 0;
  private $currentAlbum = NULL;

  function __construct() {
    $this->currentAlbum = new Album(GALLERY_ROOT);    
  }   
 

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
    //TODO: check existence
    {
      $this->currentAlbum = new Album($album);
    }   
  } // end of member function setAlbum

  /**
   * 
   *
   * @return 
   * @access public
   */
  public function getItems( ) {
    return $this->currentAlbum->getItems();
  } // end of member function getItems
  
  public function getAlbum() {
    return $this->currentAlbum;
  } // end of member function getAlbum

} // end of Gallery
?>


