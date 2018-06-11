<?php

class Request{
  public $id;
  public $item_id;
  public $from_user_id;
  public $to_user_id;
  public $start_date;
  public $end_date;
  public $approved_date;
  public $retrieved_date;
  public $returned_date;

  function toArray(){
    $data["id"] = $id;
    $data["item_id"] = $item_id;
    $data["from_user_id"] = $from_user_id;
    $data["to_user_id"] = $to_user_id;
    $data["start_date"] = $start_date;
    $data["end_date"] = $end_date;
    $data["approved_date"] = $approved_date;
    $data["retrieved_date"] = $retrieved_date;
    $data["returned_date"] = $returned_date;

    return $data;
  }
}


 ?>
