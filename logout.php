<?php
require_once 'session_init.php';
require_once 'classes/LoginManager.php';
$lm = new LoginManager();
$lm->logOut();
header('Location: index.php');
 