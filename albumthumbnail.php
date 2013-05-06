<?php
require_once 'classes/model/Album.php';

header("Content-type: image/png");

if(isset($_GET['image'])) $image = $_GET['image'];
else $image = "";
// if(isset($_GET['size'])) $size = $_GET['size'];
// else $size = "";
// if(isset($_GET['w'])) $w = $_GET['w'];
// else $w = "";
// if(isset($_GET['h'])) $h = $_GET['h'];
// else $h = "";
// if(isset($_GET['q'])) $q = $_GET['q'];
// else $q = "";

if ($w == ""){$w = $size;}
if ($h == ""){$h = $size;}
if ($w == ""){$w = "200";}
if ($h == ""){$h = "200";}
if ($q == ""){$q = 100;}

$album = new Album($image);
echo $album->getThumbnail();
 