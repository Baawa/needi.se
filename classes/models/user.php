<?php

class User{
  public $id;
  public $fb_id;

  public $email;
  public $password;

  public $name;
  public $adress;
  public $phone_number;

  public $sid;
  public $last_login;
  public $reset_pass_url;

  function toArray(){
    $data["id"] = $this->id;
    $data["fb_id"] = $this->fb_id;
    $data["email"] = $this->email;
    $data["name"] = $this->name;
    $data["adress"] = $this->adress;
    $data["phone_number"] = $this->phone_number;
    $data["sid"] = $this->sid;
    $data["last_login"] = $this->last_login;

    return $data;
  }
}



 ?>
