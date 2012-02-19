<?php

require_once 'session_init.php';
require_once 'lib/Twig/Autoloader.php';
require_once 'Gallery.php';
require_once 'LoginManager.php';
require_once 'Admin.php';
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

function getData($name)
{
  if($data_mode == DATA_MODE_GET)
  {
    if(isset($_GET[$name]))
      return $_GET[$name];
  }  
  else
  {
    if(isset($_POST[$name]))
      return $_POST[$name];
  }
  return false;
}

switch($action)
{
  case 'getPhotos':
    
    break;
}