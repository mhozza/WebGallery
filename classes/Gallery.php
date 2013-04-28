<?php
require_once 'utils/Database.php';
require_once 'model/Album.php';
require_once 'model/Photo.php';
require_once 'controller/LoginManager.php';
require_once 'utils/Exceptions.php';

define('GALLERY_ROOT','gallery');


/**
 * class Gallery
 * 
 */
class Gallery
{
  private $currentAlbum = null;
  private $loginManager = null;

  function __construct() {
    $this->currentAlbum = new Album(GALLERY_ROOT);    
    $this->loginManager = new LoginManager();
  }   

  /**
   * @return Photo
   * @access public
   */
  public function getCurrentPhoto( ) 
  {    
    return $this->currentAlbum->getCurrentPhoto();
  }

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
  }

  public function getPreviousPhoto( ) 
  {    
    return $this->currentAlbum->getPreviousPhoto();
  }

  /**
   * @param Album album 
   * @return 
   * @access public
   */
  public function setAlbum( $album ) {
    $this->currentAlbum = new Album($album);    
  }

  public function setPhoto( $photo_path ) {
    $photo = Database::getPhotoByPath($photo_path);
    if($photo==null) throw new SecurityException('Could not access photo.');
    $this->currentAlbum = $photo->getParent();
    $this->currentAlbum->setCurrentPhoto($photo);
  }

  /**
   * @return 
   * @access public
   */
  public function getItems( ) {      
    return $this->currentAlbum->getItems();
  }
  
  public function getPhotos( ) {      
    return $this->currentAlbum->getPhotos();
  }

  public function getAlbum() {
    return $this->currentAlbum;
  }
}
?>