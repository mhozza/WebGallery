<?php

require_once 'LoginManager.php';
require_once 'Privileges.php';
 


/**
 * class Database
 * 
 */
class Database
{

   /**
   * 
   * @static
   * @access private
   */
  private static $isInitialized = false;
  private static $db = NULL;

  private static $loginManager;
  
  /**
   * 
   *
   * @param string query 

   * @return 
   * @static
   * @access public
   */  
  
  public static function runQuery( $query, $params = NULL )
  {
    //self::connect();
    //FIXME: osetrit chyby    
    $st = self::$db->prepare($query);//TODO: driver parameters    
    if($params==NULL)
      $st->execute();
    else
      $st->execute($params);
    //$st->debugDumpParams();
    return $st->fetchAll(PDO::FETCH_ASSOC);
  } // end of member function runQuery

  /**
   * 
   *
   * @return 
   * @static
   * @access public
   */
  public static function connect( ) {
    //INIT:
    if(!self::$isInitialized)
    {
      self::$isInitialized = true;
      self::$loginManager = new LoginManager();
    }
    //CONNECT:
    //TODO: nacitat z nastaveni       
    if(self::$db==null)
      self::$db = new PDO('mysql:host=localhost;dbname=mio-gallery', "user", "drowssap");        
    
    //TODO: mozno nastavit utf8

    
    
  } // end of member function connect

  /**
   * 
   *
   * @return 
   * @static
   * @access public
   */
  public static function disconnect( ) {
    //TODO: nakodit
  } // end of member function disconnect


  public static function getPhotos($albumID)//TODO: album filter
  {
     self::connect();
     $userID = self::$loginManager->getUser()->getId();     
          
     $sql = "SELECT id,caption,path FROM Photos WHERE 
      (id NOT IN (SELECT photo_id FROM PhotoPermissions WHERE user_id= ? AND type=" . PT_DENY . ") 
      AND album NOT IN (SELECT album_id FROM AlbumPermissions WHERE user_id= ? AND type=" . PT_DENY . ")
      AND (permissions <> " . PP_PRIVATE . ") OR id IN (SELECT photo_id FROM PhotoPermissions WHERE user_id= ? AND type=" . PT_ALOW . ")"
      . ($userID==-1? 'AND (permissions<>' . PP_PUBLIC . ')' : '') . 
      ")";
      //echo $sql;
      $res = self::runQuery($sql,array($userID,$userID,$userID));
      //FIXME: error checking
      
      $ret = array();
      foreach($res as $row)
      {
        $ret[] = new Photo($row);
      }
      return $ret;
      
  }

  public static function getAlbumByPath($path)
  {
     self::connect();
     $userID = self::$loginManager->getUser()->getId();    
          
     $sql = "SELECT id,caption,path FROM Albums WHERE
      (path = ? AND id NOT IN (SELECT album_id FROM AlbumPermissions WHERE user_id= ? AND type=" . PT_DENY . ")
      AND (permissions <> " . PP_PRIVATE . ") OR id IN (SELECT album_id FROM AlbumPermissions WHERE user_id= ? AND type=" . PT_ALOW . ")"
      . ($userID==-1? 'AND (permissions<>' . PP_PUBLIC . ')' : '') . 
      ") LIMIT 1";
      //echo $sql;      
      $res = self::runQuery($sql,array($path,$userID,$userID));      
      $ret = NULL;
      //FIXME: error checking      
      if(isset($res[0])) $ret = $res[0];      
      return $ret;
  }
} // end of Database
?>
