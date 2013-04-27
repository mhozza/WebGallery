<?php
// require_once 'google-api-php-client/src/Google_Client.php';

require_once 'session_init.php';

require_once 'lib/lightopenid/openid.php';
require_once 'classes/LoginManager.php';
require_once 'classes/Exceptions.php';

$lm = new LoginManager();
try {
  if(!$lm->isLoggedIn())  
  {
      $openid = new LightOpenID('http://localhost');
      if(!$openid->mode) {
          if(isset($_POST['openid_identifier'])) {
              $openid->identity = $_POST['openid_identifier'];
              $openid->required = array('namePerson/friendly','contact/email', 'namePerson','birthDate','person/gender', 'contact/postalCode/home', 'contact/country/home','pref/language', 'pref/timezone',);
              //$openid->required = array('namePerson/friendly','contact/email', 'namePerson', 'namePerson/first', 'namePerson/last','pref/timezone',);
              header('Location: ' . $openid->authUrl());
          }
          $vars['login'] = false;
      } elseif($openid->mode == 'cancel') {
          echo 'User has canceled authentication!';
      } else {
          if(!$openid->validate()) throw new LoginException('OpenId validation failed');                  
          $lm->logIn($openid->identity,$openid->getAttributes());     
      }

      // $client = new Google_Client();
      // $client->setApplicationName('WebGallery');
      // $client->setClientId('617594265168.apps.googleusercontent.com');
      // $client->setClientSecret('wFlk5h842qxx9xM-Tx8KY9fT');
      // $client->setRedirectUri('http://localhost/oauth2callback');
      // $client->setDeveloperKey('insert_your_simple_api_key');
  }
  if($lm->isLoggedIn())
    $vars['login'] = true;    
} catch(ErrorException $e) {
    echo $e->getMessage();
} catch(LoginException $e) {
    echo $e->getMessage();
}


