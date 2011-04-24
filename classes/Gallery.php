<?php
require_once 'Database.php';
require_once 'Album.php';
require_once 'Photo.php';
require_once 'LoginManager.php';
require_once 'Exceptions.php';

define('GALLERY_ROOT','/gallery/');
define('MAX_RATE',5);

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

  public function getAlbums( ) {
    return $this->currentAlbum->getAlbums();
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
  
  public function addComment($photo,$comment_text)
  {    
    if($this->loginManager->getUser()->getId()==UID_UNLOGGED) throw new SecurityException('You have to be logged in to add comment.');    
    //TODO: check comment    
    if(!Database::addComment($photo->getId(),$comment_text)) throw new SecurityException('Could not add comment');
    $photo->reloadComments();
  }

  public function addRating($photo,$rating)
  {    
    if($rating<1 || $rating>MAX_RATE)throw new SecurityException('Bad range.');    
    if($this->loginManager->getUser()->getId()==UID_UNLOGGED) throw new SecurityException('You have to be logged in to add rating.');    
    //TODO: check rating
    //if(!is_int($rating))
    if(!Database::addRating($photo->getID(),$rating)) throw new SecurityException('Could not add rating.');
    $photo->reloadRating();
  }

} // end of Gallery
?>


