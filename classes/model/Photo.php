<?php
require_once 'GalleryItem.php';
require_once 'Exif.php';
require_once 'classes/utils/Validator.php';

define('MAX_RATE',5);
define('MAX_COMMENT_SIZE',500);

/**
 * class Photo
 */
class Photo extends GalleryItem
{
  private $exifData = NULL;
  private $comments = null;  
  public $width = 0;
  public $height = 0;

  function __construct($arg) {
    $info = null;
    if(is_string($arg))
    {
      $path = $arg;
      $info = Database::getPhotoByPath($path);
    }

    if(is_array($arg))
    {   
      $info = $arg;
    }

    if($info !=null)
    {      
      parent::__construct($info['id'],$info['caption'],$info['path'],$info['album'],$info['permissions'],$info['last_changed']);          
      $this->rating = $info['rating'];
      $this->width = $info['width'];
      $this->height = $info['height'];
    }    
    else
    {
      throw new RuntimeException('Invalid array');
    }
  }   

  /**
   * @return Exif
   * @access public
   */
  public function getExifData( ) {
  }

  public function reloadComments()
  {
    $this->comments = Database::getComments($this->id);
  }

  public function reloadRating()
  {
    $this->rating = Database::getRating($this->id);
    if(!$this->rating) $this->rating = 0;
  }

  public function addComment($comment_text)
  {    
    if(!Validator::checkSize($comment_text,MAX_COMMENT_SIZE)) throw new SecurityException('Comment is too long');
    if(!Database::addComment($this->getId(),$comment_text)) throw new SecurityException('Could not add comment');
    $this->reloadComments();
  }

  public function addRating($rating)
  {    
    if($rating<1 || $rating>MAX_RATE) throw new SecurityException('Bad range.');                
    if(!Database::addRating($this->getID(),$rating)) throw new SecurityException('Could not add rating.');
    $this->reloadRating();
  }

   /**
   * @return Comment[]
   * @access public
   */
  public function getComments( ) {       
    if($this->comments==null) $this->reloadComments();
    return $this->comments;    
  }  

  public function getImage($w, $h)
  {
    return $this->imageLoader->getThumbnail($this,$w, $h);
  }

}
?>
