<?php

require "../classes/include.php";

if (!isLoggedIn()) {
  redirect("../index.php"); //TODO: call login-action
} else{
  redirect("profile.php");
}

 ?>
