<?php
require_once 'session_init.php';
require_once 'classes/controller/API.php';

if(isset($_GET['method']))
{
  $data = $_GET;
}

if(isset($_POST['method']))
{
  $data  = $_POST;
}

if(!isset($data)) die();
$method = $data['method'];

if(isset($data['params']))
	$params = $data['params'];

$api = new API();

$p = array();
if(isset($params))
{
	$p = json_decode($params);
}

try{
   
	$res = call_user_func_array(array($api,$method), $p);
	echo $res;
}
catch(Exception $e)
{
  echo $e->getMessage();
  echo $_SERVER['REQUEST_URI'];
  die();
}

