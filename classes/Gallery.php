<?php
require_once 'Database.php';
require_once 'Album.php';
require_once 'Photo.php';
require_once 'LoginManager.php';
require_once 'Exceptions.php';

define('GALLERY_ROOT','gallery');


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

  private $currentAlbum = null;
  private $loginManager = null;

  
  function __construct() {
    $this->currentAlbum = new Album(GALLERY_ROOT);    
    $this->loginManager = new LoginManager();
  }   
 

  /**
   * 
   *
   * @return Photo
   * @access public
   */
  public function getCurrentPhoto( ) 
  {    
    return $this->currentAlbum->getCurrentPhoto();
  } // end of member function getCurrentPhoto

  public function getCurrentPhotoIndex( ) 
  {
    return $this->currentAlbum->getCurrentPhotoIndex();
  }

  public function getPhotoCount( ) 
  {
    return $this->currentAlbum->getPhotoCount();
  }


  public function getNextPhoto( ) 
  {    
    return $this->currentAlbum->getNextPhoto();
  } // end of member function getNextPhoto

  public function getPreviousPhoto( ) 
  {    
    return $this->currentAlbum->getPreviousPhoto();
  } // end of member function getPreviousPhoto

  /**
   * 
   *
   * @param Album album 

   * @return 
   * @access public
   */
  public function setAlbum( $album ) {
    $this->currentAlbum = new Album($album);    
  } // end of member function setAlbum

  public function setPhoto( $photo_path ) {
    $photo = Database::getPhotoByPath($photo_path);
    if($photo==null) throw new SecurityException('Could not access photo.');
    $this->currentAlbum = $photo->getParent();
    $this->currentAlbum->setCurrentPhoto($photo);
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
  
  public function getPhotos( ) {      
    return $this->currentAlbum->getPhotos();
  } // end of member function getItems
  
  public function getAlbum() {
    return $this->currentAlbum;
  } // end of member function getAlbum
 
  
  

} // end of Gallery
?>