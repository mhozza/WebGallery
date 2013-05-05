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
	function getItems($albumPath = GALLERY_ROOT)
	{
		$album = new Album($albumPath);
		$res = json_encode(array("caption"=>$album->getCaption(),"parent"=>$album->getParent(),"items"=>$album->getItems()), JSON_NUMERIC_CHECK);
	    return $res;
	}

	//image getting
	function getThumbnail($path, $class = "Photo")
	{
		if($class=="Album")
		{
			$album = new Album($albumPath);
			$res = $album->getThumbnail();
			return $res;
		}
	}

	// function getPhotoImage()
	// {

	// }

	//photo manipulation

	//user manipulation

}