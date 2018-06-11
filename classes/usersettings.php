<?php

require_once "include.php";

define('USER_ID', 'user_id');
define('USER_SID', 'user_sid');
define('COOKIE_CONSENT', 'cookie_consent');

function setClientSession($id, $sid){
  setcookie(constant('USER_ID'), $id, time() + (86400 * 30), "/"); // 30 days
  setcookie(constant('USER_SID'), $sid, time() + (86400 * 30), "/"); // 30 days
}

function getClientId(){
  return htmlspecialchars($_COOKIE[constant('USER_ID')]);
}

function getClientSid(){
  return htmlspecialchars($_COOKIE[constant('USER_SID')]);
}

function setCookieConsent(){
  setcookie(constant('COOKIE_CONSENT'), "true", time() + (10 * 365 * 24 * 60 * 60), "/");
}

function getCookieConsent(){
  return htmlspecialchars($_COOKIE[constant('COOKIE_CONSENT')]) == "true";
}

function logout(){
  setClientSession("","");
  return TRUE;
}

function isLoggedIn(){
  $dbhandler = new DBHandler();
  $id = getClientId();
  $sid = getClientSid();

  $flag = $dbhandler->isUserLoggedIn($id, $sid);

  return $flag;
}


 ?>
