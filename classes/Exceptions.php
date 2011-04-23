<?php
  class LoginException extends RuntimeException
  {
    function  __construct ($message=NULL, $code=0)
    {
      parent::__construct($message,$code);
      $this->message = 'Login failed. '.$this->message;      
    }
  }
  
  class SecurityException extends RuntimeException
  {
    function  __construct ($message=NULL, $code=0)
    {
      parent::__construct($message,$code);
      $this->message = 'Security exception. '.$this->message;      
    }
  }
?>