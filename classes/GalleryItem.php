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

  protected $parent = null;

  protected $permissions;
  protected $rating = 0; 
  protected $parentID;
  protected $id;
  protected $caption;
  protected $path;


  function __construct($id,$caption,$path,$parentID,$perms) {
    $this->id = $id;
    $this->caption = $caption;
    $this->path = $path;    
    $this->permissions = $perms;
    settype($parentID,'integer');     
    $this->parentID = $parentID;
  }

  public function getRating( ) {
    if($this->rating==0) return 'neohodnotené';
    return $this->rating;
  } // end of member function getRating

  public function getCaption( ) {
    return $this->caption;
  } // end of member function getCaption

  public function setCaption($c ) {
    $this->caption = $c;
  } // end of member function getCaption
 
  public function getId( ) {
    return $this->id;
  } // end of member function getId

  public function getPath( ) {
    return $this->path;
  } // end of member function getPath

  public function setName( $p ) {
    if($this->parentID!=null)
    {
      $parent = $this->getParent();  
      $this->path = $parent->getPath().'/'.$p;
    }
    else
    {     
      $this->path = $p;
    }    
    
  } // end of member function getPath

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
  
  public function setPermissions($p)
  {
    $this->permissions = $p;
  }

  /**
   * returns parent album(directory)
   *
   * @return GalleryItem
   * @access public
   */
  public function getParent( ) {    
    if($this->parent==null)
    {
      if($this->parentID!=null)
      {        
        try
        {
          $this->parent = new Album($this->parentID);
        }
        catch(Exception $e)
        {         
        }
      }
      //else throw new RuntimeException('Null parentID ItemID:' . $this->getId() . ' Class: ' . $this->getClass());
    }
    return $this->parent;
  } // end of member function getParent

  public function toArray()
  {
    return array('id' => $this->id, 'caption' => $this->caption, 'path' => $this->path,  'parentId' => $this->parentID, 'class' =>  $this->getClass());
  }

  
} // end of GalleryItem
?>
