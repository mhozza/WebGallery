<?php
  require_once 'lib/Twig/Autoloader.php';
  require_once 'classes/Renderer.php';
  Twig_Autoloader::register();

  $cache = 'templates_cache';
  $cache = false;
  $loader = new Twig_Loader_Filesystem('templates');  
  $twig = new Twig_Environment($loader, array('cache' => $cache));

  $template_path = 'gallery_main.htm';
  $template = $twig->loadTemplate($template_path);
     
  $renderer = new Renderer();
  $vars['gallery'] = $renderer->render();
        
  $template->display($vars);
?>