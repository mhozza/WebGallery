<?php  
  require_once 'classes/Database.php';  
  function checkSession()
  { 
    if(Database::checkSession()) return true;                  
    if(Database::logSession()) return true;    
    return false;
  }

  //session_name("adgjvbabk");
  session_save_path('session');
  session_start();
  
  
  /*if(!checkSession())
  {
    require_once 'classes/LoginManager.php';
    $lm = new LoginManager();
    $lm->logOut();
    die('Try again');
  }*/  
?>