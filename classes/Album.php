<?php
require_once 'GalleryItem.php';
require_once 'Exceptions.php';

/**
 * class Album
 * 
 */
class Album extends GalleryItem
{

  

  function __construct($arg) {   
    $info = NULL;
    if(is_int($arg))
    {      
      $info = Database::getAlbumById($arg);       
    }
    if(is_string($arg))
    {      
      $info = Database::getAlbumByPath($arg);       
    }
    else if(is_array($arg))
    {
      $info = $arg;
    }

    if($info!=NULL) //FIXME: neloadovat hned novy album
    {      
      parent::__construct($info['id'],$info['caption'],$info['path'],$info['parent_id'],$info['permissions']);                     
    }
    else
    {      
      print_r($info);
      throw new SecurityException('Could not access album. Argument:'.$arg);
    }
    
  }   

  /**
   * 
   *
   * @return 
   * @access public
   */
  public function getPhotos( ) {
    return Database::getPhotos($this->id);
  } // end of member function getPhotos

  /**
   * 
   *
   * @return 
   * @access public
   */
  public function getAlbums( ) {
    return Database::getAlbums($this->id);
  } // end of member function getPhotos


  /**
   * 
   *
   * @return 
   * @access public
   */
  public function getItems( ) {
    return array_merge($this->getAlbums(),$this->getPhotos());
  } // end of member function getItems

 





} // end of Album
?>
