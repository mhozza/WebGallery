<?php 
  class Validator
  {
    public static function checkSize($string, $maxsize)
    {
      if($maxsize>=0)
      {
        if(sizeof($string)>$maxsize) return false;
      }
      return true;
    }
    
    public static function validateFileName($string, $maxsize = -1)
    {
      if(!self::checkSize($string,$maxsize)) return false;
      $regexp = '/[\w\s]*/';      
      return preg_match($regexp,$string);
    }   
    
    public static function validateEmail($string, $maxsize = -1)
    {
      if(!self::checkSize($string,$maxsize)) return false;
      $regexp = '/[\w-\.]*@[\w-\.]*\.[a-z]*/';      
      return preg_match($regexp,$string);
    }   
  }

?>