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


  public function getPhotos( ) {    
    return Database::getAllPhotos();
  } // end of member function getItems

  public function getUsers( ) {    
    return Database::getAllUsers();
  } // end of member function getItems
  
  public function addPhoto($parentID,$photoName,$photoTmpName,$photoCaption,$photoPerms)
  {
    if($this->loginManager->getUser()->getId()!=UID_ROOT) return;
    settype($parentID,'integer');
    $parent = new Album($parentID);
    $info['path'] = $parent->getPath().'/'.$photoName;
    $info['album'] = $parentID;
    $info['id'] = 0;
    $info['caption'] = $photoCaption;
    $info['permissions'] = $photoPerms;
    $info['rating'] = 0;
    
    //make dir
    if(copy($photoTmpName,$info['path']))
    {
      //add to DB
      Database::addPhoto(new Photo($info));
    }
    else
      throw new RuntimeException('Could not upload photo');    
    
      
    
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
    //make dir
    if(mkdir($info['path'],0775))
    {
      //add to DB
      Database::addAlbum(new Album($info));
    }
    else
      throw new RuntimeException('Could not create directory');
  }

  public function addPerms($type,$id,$uid,$perms)
  {
    if($this->loginManager->getUser()->getId()!=UID_ROOT) return;
    //TODO: sklontrolovat veci co  prisli    
    Database::addPerms($type,$id,$uid,$perms);
  }

} // end of Admin
?>


