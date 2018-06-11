<?php

if (FALSE) {
  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', 'On');
  ini_set('html_errors', 0);

  require_once "include.php";

  echo "starting...";

  $dbhandler = new DBHandler();

  $flag = $dbhandler->createUserTable();

  if (!$flag) {
    echo "Error on create user table. ";
  }

  $flag = $dbhandler->createItemTable();

  if (!$flag) {
    echo "Error on create item table. ";
  }

  $flag = $dbhandler->createRequestTable();

  if (!$flag) {
    echo "Error on create request table. ";
  }

  if ($flag) {
    echo "Created tables succesfully.";
  }
}


 ?>
