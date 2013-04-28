<?php
require_once 'classes/controller/LoginManager.php';
require_once 'classes/model/Privileges.php';
require_once 'classes/model/User.php'; 
require_once 'config.php';

$cnt = 0;

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
  
  /*
   * 
   *
   * @param string query 

   * @return 
   * @static
   * @access public
   */  
  
  public static function runQuery( $query, $params = NULL )
  {
    global $cnt;
    $cnt+=1;
    self::connect();
    //echo $query.'<br/>';    
    $st = self::$db->prepare($query);//TODO: driver parameters    
    if($params==NULL)
    {
     $r = $st->execute();
     if(!$r) throw new DBFailureException($query);
    }
    else
    {
      $r = $st->execute($params);
      if(!$r) throw new DBFailureException($query);
    }
    //$st->debugDumpParams();
    return $st;//->fetchAll(PDO::FETCH_ASSOC);
  } // end of member function runQuery


  public static function init( ) {
    //INIT:
    if(!self::$isInitialized)
    {
      self::$isInitialized = true;
      self::$loginManager = new LoginManager();
    }
  }
  /**
   * 
   *
   * @return 
   * @static
   * @access public
   */
  public static function connect( ) {
    self::init();
    
    //CONNECT:
    //TODO: nacitat z nastaveni    
    global $DB_CONNECTION_STRING;
    global $DB_USER;
    global $DB_PASS;
    if(self::$db==null)
    {
      self::$db = new PDO($DB_CONNECTION_STRING, $DB_USER, $DB_PASS);        
      self::$db->query("SET CHARACTER SET 'utf8'");
    }
    
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
     
     $userID = self::$loginManager->getUser()->getId();     
     $mustlogin = ($userID==UID_UNLOGGED) ? 'AND permissions = ' . PT_PUBLIC : '';
          //FIXME: permissions
     $sql = "SELECT Photos.id,caption,path,album,permissions  ,rating FROM Photos,(
        (SELECT photo_id as id,AVG(rating) as rating FROM `Rating` GROUP BY photo_id)
        UNION 
        (SELECT id,0 as rating FROM Photos WHERE id not in (SELECT photo_id FROM Rating))
        ) Rate
      WHERE (      
      album = ? AND Photos.id = Rate.id
      AND (
        Photos.id NOT IN (
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
          OR Photos.id IN (
            SELECT photo_id FROM PhotoPermissions WHERE (
              user_id= ? 
              AND type=" . PT_ALOW . "
            )
          )
          OR $userID = " . UID_ROOT . "
        )
        $mustlogin 
        )) ORDER BY Photos.id;";

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

  public static function getPhotoByPath($path)
  {
     
     $userID = self::$loginManager->getUser()->getId();    

     $mustlogin = ($userID==UID_UNLOGGED) ? 'AND permissions = ' . PT_PUBLIC : '';     

     $sql = "SELECT Photos.id,caption,path,album,permissions  ,rating FROM Photos,(
        (SELECT photo_id as id,AVG(rating) as rating FROM `Rating` GROUP BY photo_id)
        UNION 
        (SELECT id,0 as rating FROM Photos WHERE id not in (SELECT photo_id FROM Rating))
        ) Rate
      WHERE (      
      path = ? AND Photos.id = Rate.id
      AND (
        Photos.id NOT IN (
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
          OR Photos.id IN (
            SELECT photo_id FROM PhotoPermissions WHERE (
              user_id= ? 
              AND type=" . PT_ALOW . "
            )
          )
          OR $userID = " . UID_ROOT . "
        )
        $mustlogin         
        )) LIMIT 1";
      //echo $sql;      
      $res = self::runQuery($sql,array($path,$userID,$userID,$userID))->fetch(PDO::FETCH_ASSOC);            
      //FIXME: error checking            
      if(!$res) return null;
      return  new Photo($res);
  }

  public static function getPhotoByID($photoID)
  {
     
     $userID = self::$loginManager->getUser()->getId();    

     $mustlogin = ($userID==UID_UNLOGGED) ? 'AND permissions = ' . PT_PUBLIC : '';     

     $sql = "SELECT Photos.id,caption,path,album,permissions  ,rating FROM Photos,(
        (SELECT photo_id as id,AVG(rating) as rating FROM `Rating` GROUP BY photo_id)
        UNION 
        (SELECT id,0 as rating FROM Photos WHERE id not in (SELECT photo_id FROM Rating))
        ) Rate
      WHERE (      
      Photos.id = ? AND Photos.id = Rate.id
      AND (
        Photos.id NOT IN (
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
          OR Photos.id IN (
            SELECT photo_id FROM PhotoPermissions WHERE (
              user_id= ? 
              AND type=" . PT_ALOW . "
            )
          )
          OR $userID = " . UID_ROOT . "
        )
        $mustlogin         
        )) LIMIT 1";
      //echo $sql;      
      $res = self::runQuery($sql,array($photoID,$userID,$userID,$userID))->fetch(PDO::FETCH_ASSOC);            
      //FIXME: error checking            
      if(!$res) return null;
      return  new Photo($res);
  }

  public static function getAllAlbums() //for root only
  {    
    if(!self::$loginManager->isRoot()) return false;       
    $sql = "SELECT id,caption,path,parent_id,permissions  FROM Albums;";      
    $res = self::runQuery($sql)->fetchAll(PDO::FETCH_ASSOC);
      //FIXME: error checking
      
    $ret = array();
    foreach($res as $row)
    {
      $ret[] = new Album($row);
    }
    return $ret;      
  }

  public static function getAllPhotos() //for root only
  {    
    if(!self::$loginManager->isRoot()) return false;       
    $sql = "SELECT id,caption,path,album,permissions,0 as rating  FROM Photos;";      
    $res = self::runQuery($sql)->fetchAll(PDO::FETCH_ASSOC);
      //FIXME: error checking
      
    $ret = array();
    foreach($res as $row)
    {
      $ret[] = new Photo($row);
    }
    return $ret;      
  }

  public static function getAllUsers() //for root only
  {    
    if(!self::$loginManager->isRoot()) return false;       
    $sql = "SELECT id,username,name,surname,nick,email FROM Users;";      
    $res = self::runQuery($sql)->fetchAll(PDO::FETCH_ASSOC);
      //FIXME: error checking
      
    $ret = array();
    foreach($res as $row)
    {
      $ret[] = new User($row);
    }
    return $ret;      
  }


  public static function getAlbums($albumID)
  {
    
    $userID = self::$loginManager->getUser()->getId();     
      
    $mustlogin = ($userID==UID_UNLOGGED) ? 'AND permissions = ' . PT_PUBLIC : '';           
    
    $sql = "SELECT id,caption,path,parent_id,permissions  FROM Albums WHERE (
      parent_id = ? AND 
      (
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
          OR $userID = " . UID_ROOT . "
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
     
     $userID = self::$loginManager->getUser()->getId();    

     $mustlogin = ($userID==UID_UNLOGGED) ? 'AND permissions = ' . PT_PUBLIC : '';     

     $sql = "SELECT id,caption,path,parent_id,permissions  FROM Albums WHERE (
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
          OR $userID = " . UID_ROOT . "
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
     
     $userID = self::$loginManager->getUser()->getId();    

     $mustlogin = ($userID==UID_UNLOGGED) ? 'AND permissions = ' . PT_PUBLIC : '';     

     $sql = "SELECT id,caption,path,parent_id,permissions FROM Albums WHERE (
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
          OR $userID = " . UID_ROOT . "
        ) 
        $mustlogin 
      )) LIMIT 1";
      //echo $sql;      
      $res = self::runQuery($sql,array($albumID,$userID,$userID))->fetch(PDO::FETCH_ASSOC);        
      //print_r($res);
      //FIXME: error checking            
      return $res;
  }


  public static function logSession()
  {
    
    $ssnid = session_id();
    $ipaddr = $_SERVER['REMOTE_ADDR'];
     
    $sql = "INSERT INTO SessionMap (`id`, `session_id`, `ip_address`) VALUES (NULL, ? , ?);";
    try
    {
      self::runQuery($sql,array($ssnid,$ipaddr));
      return true;
    }  
    catch(DBFailureException $e)
    {
      return false;
    }
  }

  public static function rmSession()
  {    
    $ssnid = session_id();     
    $sql = "DELETE FROM SessionMap WHERE session_id = ?;";         
    $res = self::runQuery($sql,array($ssnid))->rowCount();      
    return $res;
  }

  public static function checkSession()
  {    
    $ssnid = session_id();
    $ipaddr = $_SERVER['REMOTE_ADDR'];     
    $sql = "SELECT id FROM SessionMap WHERE (session_id = ? AND ip_address = ? )";         
    $res = self::runQuery($sql,array($ssnid,$ipaddr))->rowCount();      
    return $res;
  }

  public static function updateUser($openID,$attributes)
  {    
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
      if($nick == null)
      {
        $names = explode('@',$attributes['contact/email']);
        $nick = $names[0];
      }
    }

    try
    {
      //TODO: grup support  
      $sql = "INSERT INTO Users (`id`, `username`, `name`, `surname`, `grp`, `nick`, `email`) VALUES (NULL, ?, ?, ?, NULL, ?, ?);";         
      $res = self::runQuery($sql,array($openID,$name,$surname,$nick,$email))->rowCount();                
      return true;
    }
    catch(DBFailureException $e)
    {
        //uz tam je    
        $sql = "UPDATE Users SET `name` = ?, `surname` = ?, `nick` = ?, `email` = ? WHERE `username` = ? AND autoupdate = 1;";         
        $res = self::runQuery($sql,array($name,$surname,$nick,$email,$openID))->rowCount();  
        return true;
    }
    return false;
  }
  
  public static function getUserInfo($openID)
  {    
    $sql = "SELECT id,username,name,surname,nick,email FROM Users WHERE (username = ?) LIMIT 1";    
    $res = self::runQuery($sql,array($openID))->fetch(PDO::FETCH_ASSOC);            
    //FIXME: error checking            
    return $res;
  }

  public static function getUserInfoByID($uid)
  {    
    $sql = "SELECT id,username,name,surname,nick,email FROM Users WHERE (id = ?) LIMIT 1";    
    $res = self::runQuery($sql,array($uid))->fetch(PDO::FETCH_ASSOC);            
    //FIXME: error checking            
    return $res;
  }
  
  public static function getRating($photoID)
  {    
    //TODO: permissions?    
    $sql = "SELECT AVG(rating) as rating FROM `Rating` WHERE photo_id = ?";
    $res = self::runQuery($sql,array($photoID))->fetch(PDO::FETCH_ASSOC);            
    //FIXME: error checking            
    return $res['rating'];
  }

  public static function getComments($photoID)
  {    
    //TODO: permissions?        
    $sql = "SELECT text,Users.id,username,name,surname,nick,email FROM `Comments`, Users WHERE user_id = Users.id AND photo_id = ? ORDER BY time_added";
    $res = self::runQuery($sql,array($photoID))->fetchAll(PDO::FETCH_ASSOC);            
    //FIXME: error checking                
    $ret = array();
    foreach($res as $row)
    {
      $ret[] = new Comment($row);
    }
    return $ret;    
  }

  public static function addRating($photoID,$rating)
  {    
    $userID = self::$loginManager->getUser()->getId();    
    $mustlogin = ($userID==UID_UNLOGGED) ? 'AND permissions = ' . PT_PUBLIC : '';   
    
    //check permissions
    $sql = "SELECT id FROM Photos WHERE (
      id = ?
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
          OR $userID = " . UID_ROOT . "
        )
        $mustlogin 
        ))";   
    try{      
    
      $res = self::runQuery($sql,array($photoID,$userID,$userID,$userID))->rowCount();
      
      if(!$res) return false;   

      $sql = "INSERT INTO Rating (`id`, `photo_id`, `user_id`, `rating`) VALUES (NULL, ?, ?, ?);";         
      try{      
        self::runQuery($sql,array($photoID,$userID,$rating));
      }
      catch (DBFailureException $e)
      {     
        //uz tam je      
        $sql = "UPDATE Rating SET `photo_id` = ?, `user_id` = ?, `rating` = ? WHERE `photo_id` = ? AND `user_id` = ?;";          
        $res = self::runQuery($sql,array($photoID,$userID,$rating,$photoID,$userID));        
      }
    }
    catch (DBFailureException $e)
    {     
      return false;
    }
    return true;
  }

  public static function addComment($photoID,$comment)// logged user only
  {    
    $userID = self::$loginManager->getUser()->getId();    
    //$mustlogin = ($userID==UID_UNLOGGED) ? 'AND permissions = ' . PT_PUBLIC : '';   
    
    //check permissions
    $sql = "SELECT id FROM Photos WHERE (
      id = ?
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
          OR $userID = " . UID_ROOT . "
        )        
        ))";    
    $res = self::runQuery($sql,array($photoID,$userID,$userID,$userID))->rowCount();
    
    if(!$res) return false;
    try
    {
      $sql = "INSERT INTO Comments (`id`, `photo_id`, `user_id`, `text`) VALUES (NULL, ?, ?, ?);";         
      $res = self::runQuery($sql,array($photoID,$userID,$comment));                  
      return true;
    }
    catch(DBFailureException $e)
    {
      return false;
    }    
  }

  public static function addAlbum($album) //root only
  {    
    if(!self::$loginManager->isRoot()) return false;
    try
    {
      $sql = "INSERT INTO Albums (`id`, `parent_id`, `caption`, `path`, `permissions`) VALUES (NULL, ?, ?, ?, ?);";         
      $res = self::runQuery($sql,array($album->getParentId(),$album->getCaption(),$album->getPath(),$album->getPermissions(),));                  
      return true;
    }
    catch(DBFailureException $e)
    {
      return false;
    }    
  }

  public static function addPhoto($photo) //root only
  {    
    if(!self::$loginManager->isRoot()) return false;
    $hash = md5(file_get_contents($photo->getPath()));
    try
    {
      $sql = "INSERT INTO Photos (`id`, `caption`, `path`, `hash`, `album`, `permissions`) VALUES (NULL, ?, ?, ?, ?, ?);";         
      $res = self::runQuery($sql,array($photo->getCaption(),$photo->getPath(),$hash,$photo->getParentId(),$photo->getPermissions(),));                  
      return true;
    }
    catch(DBFailureException $e)
    {
      return false;
    }    
  }

  public static function addPerms($type,$id,$uid,$perms) //root only
  {    
    if(!self::$loginManager->isRoot()) return false;   
    $table = ($type == PT_PHOTO) ? 'PhotoPermissions' : 'AlbumPermissions';
    $idc = ($type == PT_PHOTO) ? 'photo_id' : 'album_id';
    try
    {
      try
      {
        $sql = "INSERT INTO $table (`id`, `$idc`, `user_id`, `type`) VALUES (NULL, ?, ?, ?);";         
        $res = self::runQuery($sql,array($id,$uid,$perms,));
        return true;
      }
      catch(DBFailureException $e)
      {        
        $sql = "UPDATE $table SET `$idc` = ?, `user_id` = ?, `type` = ? WHERE `$idc` = ? AND `user_id` = ?;";              
        $res = self::runQuery($sql,array($id,$uid,$perms,$id,$uid));
        return true;
      }
    }
    catch(DBFailureException $e)
    {
      return false;
    }    
  }

  public static function editAlbum($album) //root only
  {    
    if(!self::$loginManager->isRoot()) return false;
    try
    {
      $sql = "UPDATE Albums SET `caption` = ?, `path` = ?, `permissions` = ? WHERE id = ?;";               
      $res = self::runQuery($sql,array($album->getCaption(),$album->getPath(),$album->getPermissions(),$album->getId()));                  
      return true;
    }
    catch(DBFailureException $e)
    {
      return false;
    }    
  }

  public static function editPhoto($photo) //root only
  {    
    if(!self::$loginManager->isRoot()) return false;
  
    $sql = "UPDATE Photos SET `caption` = ?, `path` = ?, `permissions` = ? WHERE id = ?;";         
    self::runQuery($sql,array($photo->getCaption(),$photo->getPath(),$photo->getPermissions(),$photo->getId()));                  
    return true;        
  }

  public static function editUser($user)
  { 
    if(!self::$loginManager->isRoot() && $user->getId()!=self::$loginManager->getUser()->getId()) return false;    
    $sql = "UPDATE Users SET `name` = ?, `surname` = ?, `nick` = ?, `email` = ?, autoupdate = 0 WHERE `id` = ?;";         
    self::runQuery($sql,array($user->getFirstName(),$user->getLastName(),$user->getFriendlyName(),$user->getEmail(),$user->getId()));
  }

} // end of Database

Database::init();
?>