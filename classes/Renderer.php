<?php
require_once 'session_init.php';
require_once 'lib/Twig/Autoloader.php';
require_once 'Gallery.php';
require_once 'LoginManager.php';

/**
 * class Renderer
 * 
 */
class Renderer
{
  //private $cache = 'templates_cache';
  private $cache = false;
  
  private $twig;
  
  function __construct() {
    Twig_Autoloader::register();
    $loader = new Twig_Loader_Filesystem('templates');  
    $this->twig = new Twig_Environment($loader, array('cache' => $this->cache));     
  } 

  public function render()
  {
    $template_path = 'pages/gallery_main.htm';
    $template = $this->twig->loadTemplate($template_path);
    
    try
    {
      $g = new Gallery();
      
      //parse commands
      if(isset($_GET['album']))
      {
        $g->setAlbum($_GET['album']);
      }   
    }
    catch(Exception $e)
    {      
      die($e->getMessage());
    }
    $vars = array();
    include 'login.php';    
    $vars['gallery'] = $g;    
    $lm = new LoginManager();    
    $vars['user'] = $lm->getUser();
    $template->display($vars);
  }


} // end of Renderer
?>
