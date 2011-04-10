<?php
# Logging in with Google accounts requires setting special identity, so this example shows how to do it.
require 'openid.php';
try {
    $openid = new LightOpenID;
    if(!$openid->mode) {
        if(isset($_GET['login'])) {
            $openid->identity = 'https://www.google.com/accounts/o8/id';
	    $openid->required = array('namePerson/friendly','contact/email', 'namePerson','birthDate','person/gender', 'contact/postalCode/home', 'contact/country/home','pref/language', 'pref/timezone' , 'timezone',);	    
            header('Location: ' . $openid->authUrl());
        }
?>
<form action="?login" method="post">
    <button>Login with Google</button>
</form>
<?php
    } elseif($openid->mode == 'cancel') {
        echo 'User has canceled authentication!';
    } else {
        echo 'User ' . ($openid->validate() ? $openid->identity . ' has ' : 'has not ') . 'logged in.';
	$attributes = $openid->getAttributes();
	echo sizeof($attributes);
	foreach($attributes as $key=>$attribute)
	{
	  echo $key . ":" . $attribute;
	}
    }
} catch(ErrorException $e) {
    echo $e->getMessage();
}
