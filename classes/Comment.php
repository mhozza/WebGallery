<?php
/**
 * class Comment
 * 
 */
class Comment
{
  /**
   * 
   * @access private
   */
  private $author;
  
  /**
   * 
   * @access private
   */
  private $text;


  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getAuthor( ) {
    return $this->author;
  } // end of member function getAuthor
  
  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getText( ) {
    return $this->text;
  } // end of member function getText
  
  function __construct($info) {    
    if($info!=NULL)
    {      
      settype($info['user_id'],'integer');
      $this->author = new User($info['user_id']);
      $this->text = $info['text'];
    }    
  }   

  



} // end of Comment
?>
