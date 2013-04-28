<?php
require_once 'classes/utils/Database.php';

define('UID_UNLOGGED',-1);
define('UID_ROOT', 1);
/**
 * class User
 * 
 */
class User
{

  private $uid = UID_UNLOGGED;
  private $firstname = null;
  private $lastname = null;
  private $nickname = null;
  private $username = null;
  private $email = null;

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
  public function getDisplayName( ) {
    if($this->nickname!=null) return $this->nickname;
    if($this->firstname!=null) return $this->firstname;
    if($this->lastname!=null) return $this->lastname;
    return '';
  } // end of member function getUserName


  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getUserName( ) {
    return $this->username;
  } // end of member function getUserName

  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getFriendlyName( ) {
    return $this->nickname;
  } // end of member function getFriendlyName

  public function setFriendlyName($n ) {
    $this->nickname = $n;
  } // end of member function getFriendlyName

  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getFullName( ) {
    return $this->firstname . ' ' . $this->lastname;
  } // end of member function getFullName

  
  public function getFirstName( ) {
    return $this->firstname;
  } // end of member function getFirstName
  
  public function getLastName( ) {
    return $this->lastname;
  } // end of member function getLastName

  public function setFirstName( $n) {
      $this->firstname = $n;
  } // end of member function setFirstName

  public function setLastName($n ) {
      $this->lastname = $n;
  } // end of member function setLastName


  /**
   * 
   *
   * @return string
   * @access public
   */
  public function getEmail( ) {
    return $this->email;
  } // end of member function getEmail

  public function setEmail( $e) {
    $this->email = $e;
  } // end of member function getEmail

  /**
   * 
   *
   * @return 
   * @access public
   */
  public function __construct($arg = null) {
    if($arg == null || $arg == '') return;
    $info = false;
    if(is_string($arg))
    {            
      $info = Database::getUserInfo($arg);      
    }
    if(is_int($arg))
    {
      $info = Database::getUserInfoByID($arg);
    } 
    if(is_array($arg))
    {
      $info = $arg;
    }
    if($info == false) return;         
    $this->uid = $info['id'];
    $this->username = $info['username'];
    $this->firstname = $info['name'];
    $this->lastname = $info['surname'];
    $this->nickname = $info['nick'];
    $this->email = $info['email'];
  } // end of member function User


  public function isAdmin()
  {
   return $this->uid == UID_ROOT; 
  }

  public function isEditor()
  {
   return $this->isAdmin(); //TODO: odlisit
  }

  public function reload()
  {    
    $info = Database::getUserInfoByID($this->uid);    
    if($info == false) return;         
    $this->uid = $info['id'];
    $this->username = $info['username'];
    $this->firstname = $info['name'];
    $this->lastname = $info['surname'];
    $this->nickname = $info['nick'];
    $this->email = $info['email'];
  }


} // end of User
?>
