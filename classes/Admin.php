<?php
require_once 'Database.php';
require_once 'Album.php';
require_once 'Photo.php';
require_once 'LoginManager.php';
require_once 'Exceptions.php';


/**
 * class Gallery
 * 
 */
class AdminTools
{
  

  /**
   * 
   * @access private
   */
  private $loginManager = null;

  function __construct() {
    $this->loginManager = new LoginManager();
  }   
 
  public function getAlbums( ) {    
    return Database::getAllAlbums();
  } // end of member function getItems
  
  public function addPhoto($photo)
  {
    if($this->loginManager->getUser()->getId()!=UID_ROOT) return;
    if(get_class($photo)=='Photo')
    {
      Database::addPhoto($photo);
    }
  }

  public function addAlbum($parentID,$albumName,$albumCaption,$albumPerms)
  {
    if($this->loginManager->getUser()->getId()!=UID_ROOT) return;
    //TODO: sklontrolovat veci co  prisli
    settype($parentID,'integer');
    $parent = new Album($parentID);
    $info['path'] = $parent->getPath().'/'.$albumName;//? mat v kazdom albume full cestu alebo ju vyskladavat?
    $info['parent_id'] = $parentID;
    $info['id'] = 0;
    $info['caption'] = $albumCaption;
    $info['permissions'] = $albumPerms;
    Database::addAlbum(new Album($info));
  }

} // end of Admin
?>


