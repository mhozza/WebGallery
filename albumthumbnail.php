<?php
require_once 'classes/model/Album.php';

if(isset($_GET['image'])) $image = $_GET['image'];
else $image = "";

$album = new Album($image);
header("Content-type: image/png");
echo $album->getThumbnail();
 