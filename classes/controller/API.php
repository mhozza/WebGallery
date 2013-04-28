<?php
require_once 'classes/utils/Database.php';
require_once 'classes/model/Album.php';
require_once 'classes/model/Photo.php';
require_once 'classes/controller/LoginManager.php';
require_once 'classes/utils/Exceptions.php';

// define('GALLERY_ROOT','gallery');


class API
{

	function __construct() {	    
	    $this->loginManager = new LoginManager();
  	}   

	//photo view
	function getItems($albumName = GALLERY_ROOT)
	{
		$album = new Album($albumName);
		$res = json_encode(array("parent"=>$album->getParent(),"items"=>$album->getItems()), JSON_NUMERIC_CHECK);
	    return $res;
	}

	//photo manipulation

	//user manipulation
}