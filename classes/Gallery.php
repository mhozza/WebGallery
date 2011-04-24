<?php
require_once 'Database.php';
require_once 'Album.php';
require_once 'Photo.php';
require_once 'LoginManager.php';

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
  private $currentAlbum = null;
  private $currentPhoto = null;
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
    return $this->currentPhoto;
  } // end of member function getCurrentPhoto

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
    $this->currentPhoto = Database::getPhotoByPath($photo_path);
    if($this->currentPhoto==null) throw new SecurityException('Could not access photo.');
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

  public function addPhoto($photo)
  {
    if($this->loginManager->getUser()->getId()!=UID_ROOT) return;
    if(get_class($photo)=='Photo')
    {
      Database::addPhoto($photo);
    }
  }

  public function addAlbum($album)
  {
    if($this->loginManager->getUser()->getId()!=UID_ROOT) return;
    if(get_class($album)=='Album')
    {
      Database::addAlbum($album);
    }
  }
  
  public function addComment($photo,$comment)
  {
    if($this->loginManager->getUser()->getId()==UID_UNLOGGED) return;
    if(get_class($photo)=='Photo' && get_class($comment)=='Comment' )
    {
      Database::addComment($photo,$comment);
    }
  }

  public function addRating($photo,$rating)
  {
    if($this->loginManager->getUser()->getId()==UID_UNLOGGED) return;
    if(get_class($photo)=='Photo')
    {
      Database::addRating($photo,$rating);
    }
  }

} // end of Gallery
?>


