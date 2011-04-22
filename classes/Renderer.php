<?php
require_once 'lib/Twig/Autoloader.php';

require_once 'Gallery.php';
//require_once 'ImageResizer.php'; TODO: treba to?


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
    $template_path = 'gallery_main.htm';
    $template = $this->twig->loadTemplate($template_path);

    $g = new Gallery();

    //parse commands
    if(isset($_GET['album']))
    {
      $g->setAlbum($_GET['album']);
    }

    $vars['gallery'] = $g;    
    $template->display($vars);
  }


} // end of Renderer
?>
