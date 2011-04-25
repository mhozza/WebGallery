<?php
require_once 'Gallery.php';
require_once 'Comment.php';
require_once 'ImageResizer.php';
require_once 'GalleryItem.php';


/**
 * class GalleryItem
 * 
 */
class GalleryItem
{

  
  protected $permissions;
  /**
   * 
   * @access private
   */
  protected $rating = 0;

  /**
   * 
   * @access protected
   */
  
  protected $parentID;
  
  /**
   * 
   * @access protected
   */
  protected $id;

  /**
   * 
   * @access protected
   */
  protected $caption;

  /**
   * 
   * @access protected
   */
  protected $path;


  function __construct($id,$caption,$path,$parentID,$perms) {
    $this->id = $id;
    $this->caption = $caption;
    $this->path = $path;    
    $this->permissions = $perms;
    $this->parentID = $parentID;
  }


 

  /**
   * 
   *
   * @return float
   * @access public
   */
  public function getRating( ) {
    return $this->rating;
  } // end of member function getRating

  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getCaption( ) {
    return $this->caption;
  } // end of member function getCaption

  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getDescription( ) {
    //return $this->
  } // end of member function getDescription

  /**
   * 
   *
   * @return int
   * @access public
   */
  public function getId( ) {
    return $this->id;
  } // end of member function getId

  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getPath( ) {
    return $this->path;
  } // end of member function getPath


  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getClass( ) {
    return get_class($this);
  } // end of member function getClass

  public function getParentID()
  {
    return $this->parentID;
  }

  public function getPermissions()
  {
    return $this->permissions;
  }
  
} // end of GalleryItem
?>
