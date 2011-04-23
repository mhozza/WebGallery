<?php

require_once 'LoginManager.php';
require_once 'Privileges.php';
require_once 'User.php'; 


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
    return $st;//->fetchAll(PDO::FETCH_ASSOC);
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


  public static function getPhotos($albumID)
  {
     self::connect();
     $userID = self::$loginManager->getUser()->getId();     
     $mustlogin = ($userID==UID_UNLOGGED) ? 'AND permissions = ' . PT_PUBLIC : '';
          //FIXME: permissions
     $sql = "SELECT id,caption,path FROM Photos WHERE (
      album = ?
      AND (
        id NOT IN (
          SELECT photo_id FROM PhotoPermissions WHERE (
            user_id= ? 
            AND type=" . PT_DENY . "
          )
        )
        AND album NOT IN (
          SELECT album_id FROM AlbumPermissions WHERE (
            user_id= ? 
            AND type=" . PT_DENY . "
          )
        )
        AND (
          permissions <> " . PT_PRIVATE . "         
          OR id IN (
            SELECT photo_id FROM PhotoPermissions WHERE (
              user_id= ? 
              AND type=" . PT_ALOW . "
            )
          )
        )
        $mustlogin 
        ))";

      //echo $sql;
      $res = self::runQuery($sql,array($albumID,$userID,$userID,$userID))->fetchAll(PDO::FETCH_ASSOC);
      //FIXME: error checking
      
      $ret = array();
      foreach($res as $row)
      {
        $ret[] = new Photo($row);
      }
      return $ret;
      
  }

  public static function getAlbums($albumID)//TODO: album filter
  {
    self::connect();
    $userID = self::$loginManager->getUser()->getId();     
      
    $mustlogin = ($userID==UID_UNLOGGED) ? 'AND permissions = ' . PT_PUBLIC : '';       
          
    $sql = "SELECT id,caption,path,parent_id FROM Albums WHERE (
      parent_id = ? 
      AND (
        id NOT IN (
          SELECT album_id FROM AlbumPermissions WHERE (
            user_id= ? 
            AND type=" . PT_DENY . "
          )
        )
        AND (
          permissions <> " . PT_PRIVATE . "
          OR id IN (
            SELECT album_id FROM AlbumPermissions WHERE (
              user_id= ? 
              AND type=" . PT_ALOW .
            ")
          )
        ) 
        $mustlogin 
      ))";
      //echo $sql;
      $res = self::runQuery($sql,array($albumID,$userID,$userID))->fetchAll(PDO::FETCH_ASSOC);
      //FIXME: error checking
      
      $ret = array();
      foreach($res as $row)
      {
        $ret[] = new Album($row);
      }
      return $ret;
      
  }

  public static function getAlbumByPath($path)
  {
     self::connect();
     $userID = self::$loginManager->getUser()->getId();    

     $mustlogin = ($userID==UID_UNLOGGED) ? 'AND permissions = ' . PT_PUBLIC : '';     

     $sql = "SELECT id,caption,path,parent_id FROM Albums WHERE (
      path = ?
      AND (
        id NOT IN (
          SELECT album_id FROM AlbumPermissions WHERE (
            user_id= ? 
            AND type=" . PT_DENY . "
          )
        )
        AND (
          permissions <> " . PT_PRIVATE . "
          OR id IN (
            SELECT album_id FROM AlbumPermissions WHERE (
              user_id= ? 
              AND type=" . PT_ALOW .
            ")
          )
        ) 
        $mustlogin 
      )) LIMIT 1";
      //echo $sql;      
      $res = self::runQuery($sql,array($path,$userID,$userID))->fetch(PDO::FETCH_ASSOC);            
      //FIXME: error checking            
      return $res;
  }

  public static function getAlbumById($albumID)
  {
     self::connect();
     $userID = self::$loginManager->getUser()->getId();    

     $mustlogin = ($userID==UID_UNLOGGED) ? 'AND permissions = ' . PT_PUBLIC : '';     

     $sql = "SELECT id,caption,path,parent_id FROM Albums WHERE (
      id = ?
      AND (
        id NOT IN (
          SELECT album_id FROM AlbumPermissions WHERE (
            user_id= ? 
            AND type=" . PT_DENY . "
          )
        )
        AND (
          permissions <> " . PT_PRIVATE . "
          OR id IN (
            SELECT album_id FROM AlbumPermissions WHERE (
              user_id= ? 
              AND type=" . PT_ALOW .
            ")
          )
        ) 
        $mustlogin 
      )) LIMIT 1";
      //echo $sql;      
      $res = self::runQuery($sql,array($albumID,$userID,$userID))->fetch(PDO::FETCH_ASSOC);            
      //FIXME: error checking            
      return $res;
  }


  public static function logSession()
  {
    self::connect();     
    $ssnid = session_id();
    $ipaddr = $_SERVER['REMOTE_ADDR'];
     
    $sql = "INSERT INTO SessionMap (`id`, `session_id`, `ip_address`) VALUES (NULL, ? , ?);";
         
    $res = self::runQuery($sql,array($ssnid,$ipaddr))->rowCount();            
      
    return $res;
  }

  public static function rmSession()
  {
    self::connect();     
    $ssnid = session_id();    
     
    $sql = "DELETE FROM SessionMap WHERE session_id = ?;";
         
    $res = self::runQuery($sql,array($ssnid))->rowCount();            
      
    return $res;
  }

  public static function checkSession()
  {
    self::connect();     
    $ssnid = session_id();
    $ipaddr = $_SERVER['REMOTE_ADDR'];
     
    $sql = "SELECT id FROM SessionMap WHERE (session_id = ? AND ip_address = ? )";
         
    $res = self::runQuery($sql,array($ssnid,$ipaddr))->rowCount();            
      
    return $res;
  }

  public static function updateUser($openID,$attributes)
  {    
    self::connect();     
    $name = null;
    $surname = null; 
    $nick = null;
    $email = null;
      
    if(isset($attributes['namePerson/friendly']))
    {
      $nick = $attributes['namePerson/friendly'];
    }

    if(isset($attributes['namePerson']))
    {
      $names = explode(' ',$attributes['namePerson']);
      $name = '';
      for($i = 0;$i<sizeof($names);$i++)
      {
          if($i<sizeof($names)-1)
          {
            if($i>0) $name.=' ';
            $name.=$names[$i];
          }
          else
            $surname = $names[$i];
      }      
    }

    if(isset($attributes['contact/email']))
    {
      $email = $attributes['contact/email'];
    }
    
    //TODO: grup support  
    $sql = "INSERT INTO Users (`id`, `username`, `name`, `surname`, `grp`, `nick`, `email`) VALUES (NULL, ?, ?, ?, NULL, ?, ?);";
         
    $res = self::runQuery($sql,array($openID,$name,$surname,$nick,$email))->rowCount();            
    
    if($res) return true;//uz tam je
    
    $sql = "UPDATE Users SET `name` = ?, `surname` = ?, `nick` = ?, `email` = ? WHERE `username` = ?;";
         
    $res = self::runQuery($sql,array($name,$surname,$nick,$email,$openID))->rowCount();  

    return $res;
  }
  
  public static function getUserInfo($openID)
  {    
    self::connect();       
    $sql = "SELECT id,username,name,surname,nick,email FROM Users WHERE (username = ?) LIMIT 1";    
    $res = self::runQuery($sql,array($openID))->fetch(PDO::FETCH_ASSOC);            
    //FIXME: error checking            
    return $res;
  }
  
  public static function getRating($photoID)
  {    
    self::connect();       //TODO: permissions?    
    $sql = "SELECT AVG(rating) as rating FROM `Rating` WHERE photo_id = ?";
    $res = self::runQuery($sql,array($photoID))->fetch(PDO::FETCH_ASSOC);            
    //FIXME: error checking            
    return $res['rating'];
  }

  public static function getComments($photoID)
  {    
    self::connect();   //TODO: permissions?    
    $sql = "SELECT * FROM `Comments` WHERE photo_id = ?";
    $res = self::runQuery($sql,array($photoID))->fetchAll(PDO::FETCH_ASSOC);            
    //FIXME: error checking            
    
    $ret = array();
    foreach($res as $row)
    {
      $ret[] = new Comment($row);
    }
    return $ret;    
  }

} // end of Database
?>
