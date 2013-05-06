<?php
require_once 'GalleryItem.php';
require_once 'classes/utils/Exceptions.php';

/**
 * class Album
 * 
 */
class Album extends GalleryItem
{
  private $nextPhoto = null;
  private $prevPhoto = null;  
  private $currentPhoto = null;
  private $indexMap = null;
  private $photos = null;
  private $albums = null;

  function __construct($arg) {   
    $info = null;
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

    if($info!=null) //FIXME: neloadovat hned novy album
    {      
      parent::__construct($info['id'],$info['caption'],$info['path'],$info['parent_id'],$info['permissions'],$info['last_changed']);
    }
    else
    {      
      throw new SecurityException('Could not access album.');
    }
  }   

  /**
   * @return 
   * @access public
   */
  public function getPhotos( ) {
    if($this->photos==null)
    {
      $this->photos = Database::getPhotos($this->id);
      foreach($this->photos as $index=>$value)
      {
        $this->indexMap[$value->getId()] = $index;
      }
    }
    return $this->photos;
  }

  /**
   * @return 
   * @access public
   */
  public function getAlbums( ) {
    if($this->albums==null)
    {
      $this->albums = Database::getAlbums($this->id);
    }
    return $this->albums;
  }


  /**
   * @return 
   * @access public
   */
  public function getItems( ) {
    return array_merge($this->getAlbums(),$this->getPhotos());
  }

 
  /**
   * @return Photo
   * @access public
   */
  // public function getCurrentPhoto(  ) 
  // {    
  //   return $this->currentPhoto;
  // }
  
  // public function getCurrentPhotoIndex(  ) 
  // {    
  //   return $this->indexMap[$this->currentPhoto->getId()];        
  // }

  public function getPhotoCount(  ) 
  {    
    return sizeof($this->getPhotos());
  }

  
  // public function setCurrentPhoto( $photo ) {
  //   $this->currentPhoto = $photo;        
  //   $photos = $this->getPhotos();
  //   $index = $this->indexMap[$photo->getId()];        
  //   if(isset($photos[$index-1]))
  //     $this->prevPhoto = $photos[$index-1];
  //   else 
  //     $this->prevPhoto = null;

  //   if(isset($photos[$index+1]))
  //     $this->nextPhoto = $photos[$index+1];
  //   else
  //     $this->nextPhoto = null;
  // }

  // public function getNextPhoto( ) 
  // {    
  //   return $this->nextPhoto;
  // }

  // public function getPreviousPhoto( ) 
  // {    
  //   return $this->prevPhoto;
  // }

} 
