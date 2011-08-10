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

    public static function replaceDiacritics($string)
    {
      setlocale(LC_ALL,'sk_SK.utf8');
      return iconv('utf-8', 'ascii//TRANSLIT//IGNORE',$string);
    }
    
    public static function validateFileName($string, $maxsize = -1)
    {
      if(!self::checkSize($string,$maxsize)) return false;
      $regexp = '/^[\w_\.]+$/';      
      return preg_match($regexp,$string);
    }
    
    public static function validatePhotoFileName($string, $maxsize = -1)
    {
      if(!self::checkSize($string,$maxsize)) return false;
      $regexp = '/^[\w_\.]+\.(j|J)(p|P)(g|G)$/';      
      return preg_match($regexp,$string);
    }

    public static function validateCaption($string, $maxsize = -1)
    {
      if(!self::checkSize($string,$maxsize)) return false;
      $regexp = '/^[\w _\-\.@\$0-9]*$/';      
      return preg_match($regexp,self::replaceDiacritics($string));
    }      
    
    public static function validateEmail($string, $maxsize = -1)
    {
      if(!self::checkSize($string,$maxsize)) return false;
      $regexp = '/^[\w\-_\.]+@[\w\-_\.]+\.[a-z]+$/';      
      return preg_match($regexp,$string);
    }
    
    public static function validateNick($string, $maxsize = -1)
    {
      if(!self::checkSize($string,$maxsize)) return false;
      $regexp = '/^[\w _\-\.@\$0-9]*$/';      
      return preg_match($regexp,self::replaceDiacritics($string));
    }
    
    public static function validateFirstName($string, $maxsize = -1)
    {
      if(!self::checkSize($string,$maxsize)) return false;
      $regexp = '/^[[:alpha:] ]+$/';      
      return preg_match($regexp,self::replaceDiacritics($string));
    }
    
    public static function validateLastName($string, $maxsize = -1)
    {
      if(!self::checkSize($string,$maxsize)) return false;
      $regexp = '/^[[:alpha:] ]+$/';
      return preg_match($regexp,self::replaceDiacritics($string));
    }
  }

?>