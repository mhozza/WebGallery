<?php
require_once 'GalleryItem.php';
require_once 'Exceptions.php';

/**
 * class Album
 * 
 */
class Album extends GalleryItem
{ 
  private $parentID;

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
      parent::__construct($info['id'],$info['caption'],$info['path']);        
      $this->parent_id = $info['parent_id'];
      settype($this->parent_id,'integer');
      if($this->parent_id!=null)
      {
        
        try
        {
          $this->parent = new Album($this->parent_id);
        }
        catch(Exception $e)
        {         
        }
      }
    }
    else
    {
      throw new SecurityException('Could not access album.');
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

  /**
   * returns parent album(directory)
   *
   * @return GalleryItem
   * @access public
   */
  public function getParent( ) {    
    return $this->parent;
  } // end of member function getParent





} // end of Album
?>
