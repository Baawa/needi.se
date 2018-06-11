<?php

header ("Content-Type:text/xml");

require "../include.php";

$a = htmlspecialchars($_GET["a"]);

$loginAction = $a == "login";

$logoutAction = $a == "logout";

$loginFBAction = $a == "loginFB";

$registerFBAction = $a == "registerFB";

$cookieConsentAction = $a == "cookieConsent";

$errorMessage = "";
if ($loginAction){
  $email = htmlspecialchars($_GET["email"]);
  $password = htmlspecialchars($_GET["password"]);

  $user = login($email, $password);
  if ($user != NULL){
    setClientSession($user->id, $user->sid);

    $data = $user->toArray();
    $rootname = "user";
    $xml = toXML($data, $rootname);
    echo $xml;
  } else{
    $errorMessage .= "No such user.";
  }
} elseif($logoutAction){
  logout();
  $data["flag"] = "true";
  $rootname = "logout";
  $xml = toXML($data, $rootname);

  echo $xml;
} elseif ($loginFBAction) {
  $fb_id = htmlspecialchars($_GET["fb_id"]);
  $key = htmlspecialchars($_GET["key"]);
  $date = htmlspecialchars($_GET["dk"]);

  $user = loginFB($fb_id, $key, $date);

  if ($user != NULL) {
    setClientSession($user->id, $user->sid);

    $data = $user->toArray();
    $rootname = "user";
    $xml = toXML($data, $rootname);
    echo $xml;
  } else{
    $errorMessage .= "Invalid parameter. ";
  }
} elseif ($registerFBAction) {
  $fb_id = htmlspecialchars($_GET["fb_id"]);
  $email = htmlspecialchars($_GET["email"]);
  $name = htmlspecialchars($_GET["name"]);
  $adress = htmlspecialchars($_GET["adress"]);
  $phone_number = htmlspecialchars($_GET["phone_number"]);

  $user = registerFBAction($fb_id, $email, $name, $adress, $phone_number);

  if ($user != NULL) {
    setClientSession($user->id, $user->sid);

    $data = $user->toArray();
    $rootname = "user";
    $xml = toXML($data, $rootname);
    echo $xml;
  } else{
    $errorMessage .= "Invalid parameter, or user might already exist.";
  }
} elseif ($cookieConsentAction){
  setCookieConsent();
} else{
  $errorMessage .= "No such action.";
}

if ($errorMessage != ""){
  message($errorMessage);
}

function message($text){
  $data["text"] = $text;
  $rootname = "message";
  $xml = toXML($data, $rootname);

  echo $xml;
}

function login($email, $password){
  if ($email != "" && $password != ""){
    $dbhandler = new DBHandler();

    $user = $dbhandler->loginUser($email, $password);

    return $user;
  }

  return NULL;
}

function loginFB($fb_id, $key, $date){
  /*if ($fb_id != "" && $key != "" && $date != "") {
    if (isKeyCorrect($key, $date)) {
      $dbhandler = new DBHandler();

      $user = $dbhandler->loginUserFB($fb_id);

      return $user;
    }
    $dbhandler = new DBHandler();

    $user = $dbhandler->loginUserFB($fb_id);

    return $user;
  }*/

  if ($fb_id != "") {
    $dbhandler = new DBHandler();

    $user = $dbhandler->loginUserFB($fb_id);

    return $user;
  }

  return NULL;
}

function registerFBAction($fb_id, $email, $name, $adress, $phone_number){
  if ($fb_id != "" && $email != "" && $name != "" && $adress != "" && $phone_number != "") {

    $length = 10;

    $password = randomString($length);

    $dbhandler = new DBHandler();

    $user = $dbhandler->createUser($email, $password, $name, $fb_id, $adress, $phone_number);

    return $user;
  }

  return NULL;
}

 ?>
