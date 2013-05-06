<?php
require_once 'classes/model/Photo.php';

if(isset($_GET['image'])) $image = $_GET['image'];
else $image = "";

$photo = new Photo($image);
header("Content-type: image/jpeg");
echo $photo->getThumbnail();
