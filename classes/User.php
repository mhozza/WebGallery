<?php
require_once 'Database.php';

define('UID_UNLOGGED',-1);
/**
 * class User
 * 
 */
class User
{

  private $uid = UID_UNLOGGED;

  /**
   * 
   *
   * @return int
   * @access public
   */
  public function getId( ) {
    return $this->uid;
  } // end of member function getId

  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getUserName( ) {
  } // end of member function getUserName

  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getFriendlyName( ) {
  } // end of member function getFriendlyName

  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getEmail( ) {
  } // end of member function getEmail

  /**
   * 
   *
   * @return 
   * @access public
   */
  public function __construct($openID = null) {
    if($openID == null || $openID == '') return;
    $info = Database::getUserInfo($openID);
    if($info == false) return;
    $this->uid = $info['id'];
  } // end of member function User





} // end of User
?>
