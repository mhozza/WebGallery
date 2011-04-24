<?php
require_once 'session_init.php';
require_once 'lib/Twig/Autoloader.php';
require_once 'Gallery.php';
require_once 'LoginManager.php';

define('MODE_MAIN',0);
define('MODE_DETAIL',1);
define('MODE_ADMIN',2);

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
  
  function __construct($admin = false) {
    Twig_Autoloader::register();
    $loader = new Twig_Loader_Filesystem('templates');  
    $this->twig = new Twig_Environment($loader, array('cache' => $this->cache));     
    $this->admin = $admin;
  } 

  public function render()
  {
    $template_path_main = 'pages/gallery_main.htm';
    $template_path_detail = 'pages/gallery_detail.htm';
    $template_path_admin = 'pages/gallery_admin.htm';
    
    //set mode
    $mode = MODE_MAIN;
    if(isset($_GET['detail']))
      $mode = MODE_DETAIL;
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
          $template = $this->twig->loadTemplate($template_path_admin);
          break;
        case MODE_DETAIL:
          $template = $this->twig->loadTemplate($template_path_detail);
          //parse commands
          $g->setPhoto($_GET['detail']);          
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
      die($e->getMessage());
    }

    $vars['gallery'] = $g;    
    $lm = new LoginManager();    
    $vars['user'] = $lm->getUser();
    $template->display($vars);        
  }

} // end of Renderer
?>
