<?php
require 'lib/lightopenid/openid.php';
require 'classes/LoginManager.php';

try {
    $openid = new LightOpenID;
    if(!$openid->mode) {
        if(isset($_POST['openid_identifier'])) {
            $openid->identity = $_POST['openid_identifier'];
	    $openid->required = array('namePerson/friendly','contact/email', 'namePerson','birthDate','person/gender', 'contact/postalCode/home', 'contact/country/home','pref/language', 'pref/timezone',);
            header('Location: ' . $openid->authUrl());
        }
?>
<form action="" method="post"> 
  <input type="text" name="openid_identifier" id="openid_identifier"/>  
  <script id="__openidselector" src="https://www.idselector.com/widget/button/1"></script>  
  <input type="submit" value="Sign in" style="vertical-align:middle;"/>   
</form> 

<?php
    } elseif($openid->mode == 'cancel') {
        echo 'User has canceled authentication!';
    } else {
        if($openid->validate())
        {          
          $lm = new LoginManager();
          $lm->logIn($openid->identity,$openid->getAttributes());
        }
        else
        {
           echo 'Login failed.';
        }
    }
} catch(ErrorException $e) {
    echo $e->getMessage();
}
