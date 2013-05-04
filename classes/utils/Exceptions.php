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

  class DBFailureException extends RuntimeException
  {
    function  __construct ($message=NULL, $code=0)
    {
      parent::__construct($message,$code);
      $this->message = 'Database exception. '.$this->message;
    }
  }

  class JSONDecodeException extends RuntimeException
  {
    function  __construct ($code=0)
    {
      switch (code) {
        case JSON_ERROR_DEPTH:
            $msg = 'Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            $msg = 'Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            $msg = 'Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            $msg = 'Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            $msg = 'Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            $msg = 'Unknown error';
        break;
      parent::__construct($msg,$code);
      $this->message = 'JSON Decode Exception. '.$msg;
    }
  }
}
