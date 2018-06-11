<?php
class Item{
  public $id;
  public $user_id;

  public $name;
  public $description;
  public $price;
  public $image_url;

  public $time_available;
  public $days_available;

  function toArray(){
    $data["id"] = $this->id;
    $data["user_id"] = $this->user_id;
    $data["name"] = $this->name;
    $data["description"] = $this->description;
    $data["price"] = $this->price;
    $data["image_url"] = $this->image_url;
    $data["time_available"] = $this->time_available;
    $data["days_available"] = $this->days_available;

    return $data;
  }
}

 ?>
