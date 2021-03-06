<?php
require_once 'session_init.php';
require_once 'lib/Twig/Autoloader.php';
require_once 'Gallery.php';
require_once 'controller/LoginManager.php';
require_once 'Admin.php';
//require_once 'Twig/Extension.php';

define('MODE_MAIN',0);
define('MODE_DETAIL',1);
define('MODE_SETTINGS',2);
define('MODE_ADMIN',3);

/**
 * class Renderer
 * 
 */
class Renderer
{
  //private $cache = 'templates_cache';
  private $cache = false;

  private $twig;

  private $admin;

  private $lm;
  
  function __construct($admin = false) {
    Twig_Autoloader::register();
    $loader = new Twig_Loader_Filesystem('templates');  
    $this->twig = new Twig_Environment($loader, array('cache' => $this->cache));     
    //$this->twig->addExtension(new Extension());
    $this->admin = $admin;
    $this->lm = new LoginManager();    
  } 

  public function render()
  {
    $ajax = false;
    if(isset($_GET['ajax']) || isset($_POST['ajax']))
    {
       $ajax = true;
    }

    $template_path_main = 'pages/gallery_main.htm';
    $template_path_detail = 'pages/gallery_detail.htm';
    $template_path_detail_sa = 'parts/gallery_detail_stand_alone.htm';
    $template_path_admin = 'pages/gallery_admin.htm';    
    $template_path_settings = 'pages/gallery_settings.htm';    
    
    //set mode
    $mode = MODE_MAIN;
    if(isset($_GET['detail']))
      $mode = MODE_DETAIL;
    if(isset($_GET['settings']))
      $mode = MODE_SETTINGS;
    if($this->admin)
      $mode = MODE_ADMIN;

    $vars = array();
    include 'login.php';
    
    try
    {
      $g = new Gallery();

      switch($mode)
      {
        case MODE_ADMIN:
          if($lm->getUser()->getID()!=UID_ROOT) die();//TODO: hodit 404
          $template = $this->twig->loadTemplate($template_path_admin);
          $adminTools = new AdminTools();          
          $vars['adminTools'] = $adminTools;    
          //parse commands
          
          if($ajax)
          {
            if(isset($_GET['action']))
            {
              switch($_GET['action'])
              {
                case 'getUserInfo':
                  if(isset($_GET['id']))
                  {
                    $user = Database::getUserInfoByID($_GET['id']);                    
                    echo "{\n \"name\" : \"{$user['name']}\" , \"surname\" : \"{$user['surname']}\" , \"nick\" : \"{$user['nick']}\" , \"email\" : \"{$user['email']}\"\n}\n";                    
                  }
                  break;
                case 'getAlbumInfo':
                  if(isset($_GET['id']))
                  {
                    $album = Database::getAlbumById($_GET['id']);                   
                    $name = basename($album['path']);
                    echo "{\n \"caption\" : \"{$album['caption']}\" , \"name\" : \"$name\" , \"permissions\" : \"{$album['permissions']}\"\n}\n";                    
                  }
                  break;
                case 'getPhotoInfo':
                  if(isset($_GET['id']))
                  {
                    $photo = Database::getPhotoById($_GET['id']);      
                    if($photo!=null)
                    {                    
                      $name = basename($photo->getPath());
                      echo "{\n \"caption\" : \"{$photo->getCaption()}\" , \"name\" : \"$name\" , \"permissions\" : \"{$photo->getPermissions()}\"\n}\n";
                    }
                    else
                    {
                      echo "{\n \"caption\" : \"\" , \"name\" : \"\" , \"permissions\" : \"\"\n}\n";
                    }
                  }
                  break;
              }
            }
            die();
          }

          if(isset($_POST['addAlbum']))
          {           
            $retcode = $adminTools->addAlbum($_POST['album'],$_POST['album_name'],$_POST['album_caption'],$_POST['album_perm']);
            switch($retcode)
            {
              case ST_OK:
                $vars['messages'][] = 'Nastavenia boli úspešne uložené';
                break;      
              case ST_INVALID_FILENAME:
                $vars['errors'][] = 'Nesprávny názov.';
                break;
              case ST_INVALID_CAPTION:
                $vars['errors'][] = 'Nesprávny nadpis.';
                break;
              default:
                $vars['errors'][] = 'Nastala chyba.';
            }              
          }
          if(isset($_POST['addPhoto']))
          {
            $retcode = $adminTools->addPhoto($_POST['photo_album'],basename($_FILES['photo_file']['name']),$_FILES['photo_file']['tmp_name'],$_POST['photo_caption'],$_POST['photo_perm']);
            switch($retcode)
            {
              case ST_OK:
                $vars['messages'][] = 'Nastavenia boli úspešne uložené';
                break;      
              case ST_INVALID_FILENAME:
                $vars['errors'][] = 'Nesprávny názov.';
                break;
              case ST_INVALID_CAPTION:
                $vars['errors'][] = 'Nesprávny nadpis.';
                break;
              case ST_INVALID_PHOTO:
                $vars['errors'][] = 'Nesprávny formát obrázku.';
                break;
              default:
                $vars['errors'][] = 'Nastala chyba.';
            }              
          }
          if(isset($_POST['addPhotoPerms']))
          {
            $retcode = 0;
            foreach($_POST['perm_photo'] as $perm_photo)
            {
              foreach($_POST['perm_photo_user'] as $perm_user)
              {
                $retcode += $adminTools->addPerms(PT_PHOTO,$perm_photo,$perm_user,$_POST['perm_photo_perm']);
              }
            }
            if($retcode==0) $vars['messages'][] = 'Nastavenia boli úspešne uložené';
          }
          if(isset($_POST['addAlbumPerms']))
          {
            $retcode = 0;
            foreach($_POST['perm_album'] as $perm_album)
            {
              foreach($_POST['perm_album_user'] as $perm_user)
              {
                $retcode += $adminTools->addPerms(PT_ALBUM,$perm_album, $perm_user,$_POST['perm_album_perm']);                              
              }
            }
            if($retcode==0) $vars['messages'][] = 'Nastavenia boli úspešne uložené';
          }
          if(isset($_POST['editAlbum']))
          {
            $retcode =  $adminTools->editAlbum($_POST['album_edit'],$_POST['album_edit_name'],$_POST['album_edit_caption'],$_POST['album_edit_perm']);
            switch($retcode)
            {
              case ST_OK:
                $vars['messages'][] = 'Nastavenia boli úspešne uložené';
                break;      
              case ST_INVALID_FILENAME:
                $vars['errors'][] = 'Nesprávny názov.';
                break;
              case ST_INVALID_CAPTION:
                $vars['errors'][] = 'Nesprávny nadpis.';
                break;
              default:
                $vars['errors'][] = 'Nastala chyba.';
            }              
          }
          if(isset($_POST['editPhoto']))
          {
            $retcode =  $adminTools->editPhoto($_POST['photo_edit'],$_POST['photo_edit_name'],$_POST['photo_edit_caption'],$_POST['photo_edit_perm']);
            switch($retcode)
            {
              case ST_OK:
                $vars['messages'][] = 'Nastavenia boli úspešne uložené';
                break;      
              case ST_INVALID_FILENAME:
                $vars['errors'][] = 'Nesprávny názov.';
                break;
              case ST_INVALID_CAPTION:
                $vars['errors'][] = 'Nesprávny nadpis.';
                break;
              default:
                $vars['errors'][] = 'Nastala chyba.';
            }              
          }        
          if(isset($_POST['editUser']))
          {
            $retcode =  $adminTools->editUser($_POST['user_edit'],$_POST['user_edit_name'],$_POST['user_edit_surname'],$_POST['user_edit_nick'],$_POST['user_edit_mail']);
            switch($retcode)
            {
              case ST_OK:
                $vars['messages'][] = 'Nastavenia boli úspešne uložené';
              break;
              case ST_INVALID_MAIL:
                $vars['errors'][] = 'Nesprávny email.';
              break;
              case ST_INVALID_FIRSTNAME:
                $vars['errors'][] = 'Nesprávne meno.';
              break;
              case ST_INVALID_LASTNAME:
                $vars['errors'][] = 'Nesprávne priezvisko.';
              break;
              case ST_INVALID_NICK:
                $vars['errors'][] = 'Nesprávna prezývka.';
              break;
              default:
                $vars['errors'][] = 'Nastala chyba.';
            }              
          }        

          break;
        case MODE_DETAIL:          
	  if(!$ajax)
	    $template = $this->twig->loadTemplate($template_path_detail);
	  else
	    $template = $this->twig->loadTemplate($template_path_detail_sa);

          $g->setPhoto($_GET['detail']);
          //parse commands          
          if(isset($_POST['comment_text']) && $this->lm->getUser()->getId()!=UID_UNLOGGED)
          {           
            $g->getCurrentPhoto()->addComment($_POST['comment_text']);
            if($ajax)
            {
              $template = $this->twig->loadTemplate('parts/comments.htm');
            }
          }
          if(isset($_GET['rate']) && $this->lm->getUser()->getId()!=UID_UNLOGGED)
          {            
            $g->getCurrentPhoto()->addRating($_GET['rate']);            
            if($ajax)
            {
              echo $g->getCurrentPhoto()->getRating();
              die();
            }
            else
            {
              header('Location: index.php?detail='.$_GET['detail']);
            }
          }
          $vars['CONST']['MAX_COMMENT_SIZE'] = MAX_COMMENT_SIZE;	  
          break;
        case MODE_SETTINGS: 
          $template = $this->twig->loadTemplate($template_path_settings);
          $adminTools = new AdminTools();
          if(isset($_POST['editUser']))
          {
            $retcode = $adminTools->editUser($lm->getUser()->getId(),$_POST['user_edit_name'],$_POST['user_edit_surname'],$_POST['user_edit_nick'],$_POST['user_edit_mail']);
            switch($retcode)
            {
              case ST_OK:
                $vars['messages'][] = 'Nastavenia boli úspešne uložené';
              break;
              case ST_INVALID_MAIL:
                $vars['errors'][] = 'Nesprávny email.';
              break;
              case ST_INVALID_FIRSTNAME:
                $vars['errors'][] = 'Nesprávne meno.';
              break;
              case ST_INVALID_LASTNAME:
                $vars['errors'][] = 'Nesprávne priezvisko.';
              break;
              case ST_INVALID_NICK:
                $vars['errors'][] = 'Nesprávna prezývka.';
              break;
              default:
                $vars['errors'][] = 'Nastala chyba.';
            }                    
            $lm->getUser()->reload();
          }        
          break;
        default:
          $template = $this->twig->loadTemplate($template_path_main);
            
          //parse commands
          if(isset($_GET['album']))
          {
            $g->setAlbum($_GET['album']);
          }
   
      }
    }    
    catch(Exception $e)
    {      
      echo $e->getMessage();
      die();
    }

    $vars['gallery'] = $g;    
    
    $vars['user'] = $lm->getUser();
    
    $template->display($vars);      
    
    global $cnt;
    //echo "Pocet dotazov na DB: $cnt";
  }

} // end of Renderer
?>
