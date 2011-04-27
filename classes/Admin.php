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
    if(!$this->loginManager->isRoot()) throw new SecurityException();    
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

    //TODO: sklontrolovat veci co  prisli
    settype($parentID,'integer');
    $parent = new Album($parentID);
    $info['path'] = $parent->getPath().'/'.$photoName;
    $info['album'] = $parentID;
    $info['id'] = 0;
    $info['caption'] = $photoCaption;
    $info['permissions'] = $photoPerms;
    $info['rating'] = 0;
    
    //make dir
    if(move_uploaded_file($photoTmpName,$info['path']))
    {
      //add to DB
      Database::addPhoto(new Photo($info));
    }
    else
      throw new RuntimeException('Could not upload photo');    
    
      
    
  }

  public function addAlbum($parentID,$albumName,$albumCaption,$albumPerms)
  {

    //TODO: sklontrolovat veci co  prisli
    settype($parentID,'integer');
    $parent = new Album($parentID);
    $info['path'] = $parent->getPath().'/'.$albumName;
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

    //TODO: sklontrolovat veci co  prisli    
    Database::addPerms($type,$id,$uid,$perms);
  }

  public function editAlbum($albumID,$albumName,$albumCaption,$albumPerms)
  {
    
    //TODO: sklontrolovat veci co  prisli
    settype($albumID,'integer');
    $album = new Album($albumID);
    
    if($albumName!='')
    {
      $oldname = $album->getPath();
      $album->setName($albumName);
      $newname = $album->getPath();
      if(!rename($oldname,$newname))
        throw new RuntimeException('Could not rename album');      
    }
    if($albumCaption!='')
      $album->setCaption($albumCaption);
    if($albumPerms!='')
      $album->setPermissions($albumPerms);    
        
    //add to DB
    Database::editAlbum($album);    
  }

  public function editPhoto($photoID,$photoName,$photoCaption,$photoPerms)
  {
        
    //TODO: sklontrolovat veci co  prisli
    settype($photoID,'integer');
    $photo = Database::getPhotoById($photoID);
    
    if($photoName!='')
    {
      $oldname = $photo->getPath();
      $photo->setName($photoName);
      $newname = $photo->getPath();
      if(!rename($oldname,$newname))
        throw new RuntimeException('Could not rename photo');      
    }
    if($photoCaption!='')
      $photo->setCaption($photoCaption);
    if($photoPerms!='')
      $photo->setPermissions($photoPerms);    
        
    //add to DB
    Database::editPhoto($photo);    
  }
  
  public function editUser($userID,$name,$surname,$nick,$email)
  {
     
    //TODO: sklontrolovat veci co  prisli     
    settype($userID,'integer');
    $user = new User($userID);
    
    if($name!='')
    {      
      $user->setFirstName($name);         
    }
    if($surname!='')
      $user->setLast($surname);
    if($nick!='')
      $user->setFriendlyName($nick);    
    if($email!='')
      $user->setEmail($email);    
    //add to DB
    Database::editUser($user);    
  }

} // end of Admin
?>