<?php
// require_once 'LoginManager.php';
require_once 'classes/utils/Database.php';
require_once 'classes/model/User.php';
require_once 'classes/utils/Exceptions.php';


/**
 * class LoginManager
 * 
 */
class LoginManager
{
  
  private $user = null;

  /**
   * 
   *
   * @return 
   * @access public
   */
  public function isLoggedIn( ) {    
    return ($this->getUser()->getID() != UID_UNLOGGED);
  } // end of member function isLoggedIn

  public function isRoot( ) {    
    return ($this->getUser()->isAdmin());
  } // end of member function isLoggedIn


  /**
   * 
   *
   * @return User
   * @access public
   */
  public function getUser( ) {
    //if(!isset($this->user) || $this->user==null)
    {   
      if(isset($_SESSION['user']))    
        $this->user = $_SESSION['user'];
      else
        $this->user = new User();
    }
    return $this->user;
    
  } // end of member function getUser

  /**
   * 
   *
   * @param User user 
   * @return 
   * @access public
   */
  public function logIn( $openID, $attributes ) {    
    //nastavit session
   
    /*if($this->checkSession())
    {*/
      $_SESSION['user'] = new User($openID);
      Database::updateUser($openID,$attributes);
    /*}
    else
    {
      $this->logOut();
      throw new LoginException('Bad session id.');
    } */
    
  } // end of member function logIn

  public function checkSession()
  { 
    if(Database::checkSession()) return true;                  
    if(Database::logSession()) return true;    
    return false;
  }

  /**
   * 
   *
   * @return 
   * @access public
   */
  public function logOut( ) {
    $this->user = null;
    $_SESSION['username'] = '';
    unset($_SESSION['username']);
    if ( isset( $_COOKIE[ session_name() ] ) ) {
      setcookie(session_name(), '', time()-3600, '/');
    }  
    Database::rmSession();
    session_destroy();
  } // end of member function logOut

} // end of LoginManager
?>
