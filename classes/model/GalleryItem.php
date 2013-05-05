<?php
require_once 'classes/Gallery.php';
require_once 'classes/utils/ImageLoader.php';
require_once 'Comment.php';
require_once 'GalleryItem.php';


/**
 * class GalleryItem
 * 
 */
class GalleryItem
{

  //public properties are included in json
  public $id;
  public $caption;
  public $path;
  public $parentID;
  public $class;
  public $parentPath;
  public $lastChanged;

  protected $permissions;
  protected $rating = 0; 
  protected $parent = null;
  protected $imageLoader;

  public function toArray()
  {
    return array('id' => $this->id, 'caption' => $this->caption, 'path' => $this->path,  'parentId' => $this->parentID, 'class' =>  $this->getClass(), 'parentPath' => $this->parentPath);
  }

  function __construct($id,$caption,$path,$parentID,$perms,$lastChanged) {
    $this->id = $id;
    $this->caption = $caption;
    $this->path = $path;    
    $this->permissions = $perms;
    settype($parentID,'integer');     
    $this->parentID = $parentID;
    $this->class = $this->getClass();
    $this->parentPath = $this->getParentPath();
    $this->imageLoader =  new ImageLoader();
    $this->lastChanged = $lastChanged;
  }

  public function getParentPath( ) {
    $parentPath = '';
    if($this->getParent()!=null)
    {
      $parentPath = $this->getParent()->getPath();
    }
    return $parentPath;
  }


  public function getRating( ) {
    if($this->rating==0) return 'neohodnotené';
    return $this->rating;
  }

  public function getCaption( ) {
    return $this->caption;
  }

  public function setCaption($c ) {
    $this->caption = $c;
  }
 
  public function getId( ) {
    return $this->id;
  }

  public function getPath( ) {
    return $this->path;
  }

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
  }

  public function getClass( ) {
    return get_class($this);
  }

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
    }
    return $this->parent;
  }

  public function getThumbnail()
  {
    return $imageLoader->getThumbnail($this);
  }

}
?>
