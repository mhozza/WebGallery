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

  /** Aggregations: */    

  /** Compositions: */

   /*** Attributes: ***/

  /**
   * 
   * @access private
   */
  private $comments;

  /**
   * 
   * @access private
   */
  private $rating = 0;

  /**
   * 
   * @access private
   */
  private $parent;
  


  /**
   * 
   *
   * @return Comment[]
   * @access public
   */
  public function getComments( ) {
  } // end of member function getComments

  /**
   * 
   *
   * @return float
   * @access public
   */
  public function getRating( ) {
  } // end of member function getRating

  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getCaption( ) {
    return 'Mnau';
  } // end of member function getCaption

  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getDescription( ) {
  } // end of member function getDescription

  /**
   * 
   *
   * @return int
   * @access public
   */
  public function getId( ) {
  } // end of member function getId

  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getPath( ) {
  } // end of member function getPath





} // end of GalleryItem
?>
