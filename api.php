<?php
require_once 'session_init.php';
require_once 'classes/controller/API.php';
require_once 'classes/utils/Exceptions.php';

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


try{
	$p = array();
	if(isset($params))
	{
		$p = json_decode(stripslashes($params));
		if(json_last_error()!=JSON_ERROR_NONE)
		throw new JSONDecodeException(json_last_error());
	}

	$api = new API();
	$res = call_user_func_array(array($api,$method), $p);
	echo $res;
}
catch(Exception $e)
{
  echo $e->getMessage()."<br/>";
  echo htmlentities($_SERVER['REQUEST_URI'])."<br/>";
  echo $method."<br/>";
  print_r($p);

  die();
}
