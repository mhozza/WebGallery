<?php

require_once 'session_init.php';
require_once 'lib/Twig/Autoloader.php';
require_once 'classes/Gallery.php';
require_once 'classes/controller/LoginManager.php';
require_once 'classes/Admin.php';
//require_once 'Twig/Extension.php';

define('MODE_MAIN',0);
define('MODE_DETAIL',1);
define('MODE_SETTINGS',2);
define('MODE_ADMIN',3);

define('DATA_MODE_GET',0);
define('DATA_MODE_POST',1);


$data_mode = -1;
$action = '';
if(isset($_GET['action']))
{
  $data_mode  = DATA_MODE_GET;
  $action = $_GET['action'];
}

if(isset($_POST['action']))
{
  $data_mode  = DATA_MODE_POST;
  $action = $_POST['action'];
}

if($data_mode == -1) die();

class AjaxInterface
{
  private $admin;
  private $lm;
  private $data_mode;
  
  function __construct($data_mode,$admin = false) {
    $this->admin = $admin;
    $this->lm = new LoginManager();
    $this->data_mode = $data_mode;
  }

  private function getData($name)
  {
    if($this->data_mode == DATA_MODE_GET)
    {
      if(isset($_GET[$name]))
        return $_GET[$name];
    }
    else
    {
      if(isset($_POST[$name]))
        return $_POST[$name];
    }
    throw new RuntimeException("Invalid parameter: $name.");
  }

  
  public function action($action)
  {

    $vars = array();
    include 'login.php';
    try
    {      
      switch($action)
      {   
      case 'getPhotos':
        $g = new Gallery();
        $a = new Album($this->getData('album'));
        $g->setAlbum($this->getData('album'));
        $albumArray = $a->toArray();
        if($a->getParent()!=null)
          $albumArray['parentCaption'] = $a->getParent()->getCaption();
        else
          $albumArray['parentCaption'] = '';
        $items = array($albumArray);
        foreach($g->getItems() as $item)
        {
          $items[] = $item->toArray();
        }
        echo json_encode($items);
        break;
      }
    }
    catch(Exception $e)
    {
      echo $e->getMessage();
      die();
    }
  }

} // end of AjaxInterface

$ajaxInterface = new AjaxInterface($data_mode);
$ajaxInterface->action($action);


