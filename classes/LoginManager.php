<?php
require_once 'Database.php';
require_once 'LoginManager.php';
require_once 'User.php';
require_once 'bool.php';


/**
 * class LoginManager
 * 
 */
class LoginManager
{

  /** Aggregations: */

  var $m_;

  var $m_;

  /** Compositions: */

   /*** Attributes: ***/

  /**
   * 
   * @access private
   */
  private $user = null;

  /**
   * 
   * @access private
   */
  private $logged = false;


  /**
   * 
   *
   * @return 
   * @access public
   */
  public function isLoggedIn( ) {
  } // end of member function isLoggedIn

  /**
   * 
   *
   * @return User
   * @access public
   */
  public function getUser( ) {
  } // end of member function getUser

  /**
   * 
   *
   * @param User user 

   * @return 
   * @access public
   */
  public function logIn( $user ) {
  } // end of member function logIn

  /**
   * 
   *
   * @return 
   * @access public
   */
  public function logOut( ) {
  } // end of member function logOut





} // end of LoginManager
?>
