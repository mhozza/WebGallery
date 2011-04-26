<?php
require_once 'GalleryItem.php';
require_once 'ImageResizer.php';
require_once 'Exif.php';

define('MAX_RATE',5);

/**
 * class Photo
 * 
 */
class Photo extends GalleryItem
{

 
  /**
   * 
   * @access private
   */
  private $exifData = NULL;

  /**
   * 
   * @access private
   */
  private $comments = null;


  function __construct($info) {    
    if(is_array($info))
    {    
      parent::__construct($info['id'],$info['caption'],$info['path'],$info['album'],$info['permissions']);          
      $this->rating = $info['rating'];      
    }    
    else
    {
      throw new RuntimeException('Info is not an array');
    }
  }   

  /**
   * 
   *
   * @return Exif
   * @access public
   */
  public function getExifData( ) {
  } // end of member function getExifData

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
    //TODO: check comment    
    if(!Database::addComment($this->getId(),$comment_text)) throw new SecurityException('Could not add comment');
    $this->reloadComments();
  }

  public function addRating($rating)
  {    
    if($rating<1 || $rating>MAX_RATE)throw new SecurityException('Bad range.');                
    if(!Database::addRating($this->getID(),$rating)) throw new SecurityException('Could not add rating.');
    $this->reloadRating();
  }

   /**
   * 
   *
   * @return Comment[]
   * @access public
   */
  public function getComments( ) {       
    if($this->comments==null) $this->reloadComments();
    return $this->comments;    
  } // end of member function getComments

} // end of Photo
?>
