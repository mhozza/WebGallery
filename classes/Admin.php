<?php
require_once 'utils/Database.php';
require_once 'utils/Exceptions.php';
require_once 'model/Album.php';
require_once 'model/Photo.php';
require_once 'controller/LoginManager.php';

define('ST_INVALID_MAIL',11);
define('ST_INVALID_NICK',12);
define('ST_INVALID_FIRSTNAME',13);
define('ST_INVALID_LASTNAME',14);
define('ST_INVALID_FILENAME',21);
define('ST_INVALID_CAPTION',22);
define('ST_INVALID_PHOTO',23);
define('ST_OK',0);

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
    //if(!$this->loginManager->isRoot()) throw new SecurityException();    
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
    if(!Validator::validateCaption($photoCaption)) return ST_INVALID_CAPTION;    
    settype($parentID,'integer');
    $parent = new Album($parentID);
    $info['path'] = $parent->getPath().'/'.$photoName;
    $info['album'] = $parentID;
    $info['id'] = 0;
    $info['caption'] = $photoCaption;
    $info['permissions'] = $photoPerms;
    $info['rating'] = 0;  

    if(!Validator::validatePhotoFileName($photoName))
    {
      return ST_INVALID_PHOTO;
    }
    else
    {
      if(strtolower(mime_content_type($photoTmpName))!="image/jpeg")
        return ST_INVALID_PHOTO;
    }

    //make dir
    if(move_uploaded_file($photoTmpName,$info['path']))
    {
      //add to DB
      Database::addPhoto(new Photo($info));
    }
    else
      throw new RuntimeException('Could not upload photo');    
    
    return ST_OK;    
  }

  public function addAlbum($parentID,$albumName,$albumCaption,$albumPerms)
  {

    if(!Validator::validateFileName($albumName)) return ST_INVALID_FILENAME;
    if(!Validator::validateCaption($albumCaption)) return ST_INVALID_CAPTION;

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

    return ST_OK;
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
    
    if($albumName!='' && Validator::validateFileName($albumName,32))
    {
      $oldname = $album->getPath();
      $album->setName($albumName);
      $newname = $album->getPath();
      if(!rename($oldname,$newname))
        throw new RuntimeException('Could not rename album');      
    }
    else
    {
      return ST_INVALID_FILENAME;
    }
    if(Validator::validateCaption($albumCaption,32))
      $album->setCaption($albumCaption);
     else
    {
      return ST_INVALID_CAPTION;
    }

    if($albumPerms<0 || $albumPerms>1 ) 
      throw new SecurityException('Invalid parameter.');

    if($albumPerms!='')
      $album->setPermissions($albumPerms);    
        
    //add to DB
    Database::editAlbum($album);    
    return ST_OK;
  }

  public function editPhoto($photoID,$photoName,$photoCaption,$photoPerms)
  {
        
    //TODO: sklontrolovat veci co  prisli
    settype($photoID,'integer');
    $photo = Database::getPhotoById($photoID);
    
    if($photoName!='' && Validator::validateFileName($photoName,32))
    {
      $oldname = $photo->getPath();
      $photo->setName($photoName);
      $newname = $photo->getPath();
      if(!rename($oldname,$newname))
        throw new RuntimeException('Could not rename photo');      
    }
    else
    {
      return ST_INVALID_FILENAME;
    }
    
    if(Validator::validateCaption($photoCaption,32))
      $photo->setCaption($photoCaption);
    else
    {
      return ST_INVALID_CAPTION;
    }
    if($photoPerms<0 || $photoPerms>1 ) 
      throw new SecurityException('Invalid parameter.');
    if($photoPerms!='')
      $photo->setPermissions($photoPerms);    
        
    //add to DB
    Database::editPhoto($photo);    
    return ST_OK;
  }
  
  public function editUser($userID,$name,$surname,$nick,$email)
  {    
    //TODO: sklontrolovat veci co  prisli
    settype($userID,'integer');
    $user = new User($userID);
    
    if($name!='' && Validator::validateFirstName($name,128))
    {      
      $user->setFirstName($name);         
    }
    else
    {
      return ST_INVALID_FIRSTNAME;
    }

    if($surname!='' && Validator::validateLastName($surname,128))
    {
      $user->setLastName($surname);
    }
    else
    {
      return ST_INVALID_LASTNAME;
    }

    if(Validator::validateNick($nick,128))
    {
      $user->setFriendlyName($nick);    
    }
    else
    {
      return ST_INVALID_NICK;
    }

    if($email!='' && Validator::validateEmail($email,128))
    {
      $user->setEmail($email);    
    }
    else
    {
      return ST_INVALID_MAIL;
    }
    //add to DB
    Database::editUser($user); 
    return ST_OK;
  }

} // end of Admin
?>