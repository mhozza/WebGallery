<?php
require_once 'GalleryItem.php';
require_once 'Privileges.php';


/**
 * class Album
 * 
 */
class Album extends GalleryItem
{

  /**
   * 
   * @access public
   */
  public $privileges;
  

  function __construct($arg) {   
    $info = NULL;
    if(gettype($arg)=='string')
    {      
      $info = Database::getAlbumByPath($arg);       
    }
    else if(gettype($arg)=='array')
    {
      $info = $arg;
    }

    if($info!=NULL)
    {      
      parent::__construct($info['id'],$info['caption'],$info['path']);    
    }
    else
    {
      //FIXME: throw error
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

  } // end of member function getParent





} // end of Album
?>
