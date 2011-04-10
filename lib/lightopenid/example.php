<?php
require 'openid.php';
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
    OpenID: <input type="text" name="openid_identifier" /> <button>Submit</button>
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
