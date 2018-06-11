<?php

$noAction;

if (!$noAction) {
  header ("Content-Type:text/xml");
  require_once "../include.php";
}

$a = htmlspecialchars($_GET["a"]);

$getAction = $a == "get";

$getSingleAction = $a == "get-single";

$createAction = $a == "create";

$updateAction = $a == "update";

$searchAction = $a == "search";

$bookAction = $a == "book";

$approveAction = $a == "approve";

$retrieveAction = $a == "retrieve";

$returnAction = $a == "return";

$errorMessage = "";
if ($getAction){
  $user_id = htmlspecialchars($_GET["user_id"]);

  $items = getItems($user_id);
  if ($items != NULL) {
    $keyname = "item";
    $data = array_to_kv_array($items, $keyname);
    $childkey = "item";
    $rootname = "items";
    $xml = toXML($data, $rootname, $childkey);
    echo $xml;
  } else{
    $errorMessage .= "Invalid parameter.";
  }
} elseif ($getSingleAction) {
  $item_id = htmlspecialchars($_GET["item_id"]);

  $item = getItem($item_id);
  if ($item != NULL) {
    $data = $item->toArray();
    $rootname = "item";
    $xml = toXML($data, $rootname);
    echo $xml;
  } else{
    $errorMessage .= "Invalid parameter.";
  }
} elseif ($createAction){
  $user_id = htmlspecialchars($_POST["user_id"]);
  $name = htmlspecialchars($_POST["name"]);
  $description = htmlspecialchars($_POST["description"]);
  $price = htmlspecialchars($_POST["price"]);
  $time_available = htmlspecialchars($_POST["time_available"]);
  $days_available = htmlspecialchars($_POST["days_available"]);

  $image_url = upload("image");

  $item = createItem($user_id, $name, $description, $price, $image_url, $time_available, $days_available);

  if ($item != NULL) {
    $data = $item->toArray();
    $rootname = "item";
    $xml = toXML($data, $rootname);
    echo $xml;
  } else{
    $errorMessage .= "Error: Missing parameters.";
  }
} elseif ($updateAction) {
  $item_id = htmlspecialchars($_GET["item_id"]);
  $name = htmlspecialchars($_GET["name"]);
  $description = htmlspecialchars($_GET["description"]);
  $price = htmlspecialchars($_GET["price"]);
  $time_available = htmlspecialchars($_GET["time_available"]);
  $days_available = htmlspecialchars($_GET["days_available"]);

  $success = updateItem($item_id, $name, $description, $price, $time_available, $days_available);

  if ($success) {
    $data["flag"] = "true";
    $rootname = "success";
    $xml = toXML($data, $rootname);
    echo $xml;
  } else{
    $errorMessage .= "No such item.";
  }
} elseif ($searchAction) {
  $name = htmlspecialchars($_GET["name"]);

  $items = search($name);

  if ($items != NULL) {
    $keyname = "item";
    $data = array_to_kv_array($items, $keyname);
    $childkey = "item";
    $rootname = "items";
    $xml = toXML($data, $rootname, $childkey);
    echo $xml;
  } else{
    $data = array();
    $rootname = "item";
    $xml = toXML($data, $rootname);
    echo $xml;
  }
} elseif ($bookAction) {
  $item_id = htmlspecialchars($_GET["item_id"]);
  $user_id = htmlspecialchars($_GET["user_id"]);
  $start_date = htmlspecialchars($_GET["start_date"]);
  $end_date = htmlspecialchars($_GET["end_date"]);

  $request = book($item_id, $user_id, $start_date, $end_date);
  if ($request != NULL) {
    $data = $request->toArray();
    $rootname = "request";
    $xml = toXML($data, $rootname);
    echo $xml;

    $user = getUser($request->from_user_id);
    $item = getItem($request->item_id);

    requestRecievedMail($user->email, $item->name);
  } else{
    $errorMessage .= "Invalid parameter.";
  }
} elseif ($approveAction){
  $request_id = htmlspecialchars($_GET["request_id"]);

  $success = requestApprove($request_id);
  if ($success) {
    $data["flag"] = "true";
    $rootname = "success";
    $xml = toXML($data, $rootname);
    echo $xml;

    $request = getRequest($request_id);
    $user = getUser($request->to_user_id);
    $item = getItem($request->item_id);

    requestApprovedMail($user->email, $item->name);
  } else{
    $errorMessage .= "No such request.";
  }
} elseif ($retrieveAction){
  $request_id = htmlspecialchars($_GET["request_id"]);

  $success = requestRetrieve($request_id);
  if ($success) {
    $data["flag"] = "true";
    $rootname = "success";
    $xml = toXML($data, $rootname);
    echo $xml;

    $request = getRequest($request_id);
    $user = getUser($request->from_user_id);
    $item = getItem($request->item_id);

    itemRetrievedMail($user->email, $item->name);
  } else{
    $errorMessage .= "No such request.";
  }
} elseif ($returnAction){
  $request_id = htmlspecialchars($_GET["request_id"]);

  $success = requestReturn($request_id);
  if ($success) {
    $data["flag"] = "true";
    $rootname = "success";
    $xml = toXML($data, $rootname);
    echo $xml;

    $request = getRequest($request_id);
    $user = getUser($request->from_user_id);
    $item = getItem($request->item_id);

    itemReturnedMail($user->email, $item->name);

    $user = getUser($request->to_user_id);
    itemReturnedMail($user->email, $item->name);
  } else{
    $errorMessage .= "No such request.";
  }
} else{
  if (!$noAction) {
    $errorMessage .= "No such action.";
  }
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

function getItems($user_id){
  if ($user_id != "") {
    $dbhandler = new DBHandler();

    $user = $dbhandler->getUser($user_id);

    if ($user != NULL) {
      $items = $dbhandler->getItems($user->id);

      return $items;
    }
  }

  return NULL;
}

function getRandomItems($amount = 3){
  $dbhandler = new DBHandler();

  $items = $dbhandler->getRandomItems($amount);

  return $items;
}

function getItem($item_id){
  if ($item_id != "") {
    $dbhandler = new DBHandler();

    $item = $dbhandler->getItem($item_id);

    return $item;
  }

  return NULL;
}

function createItem($user_id, $name, $description, $price, $image_url, $time_available, $days_available){
  if ($user_id != "" && $name != "" && $description != "" && $price != "" && $image_url != "" && $time_available != "" && $days_available != "") {
    $dbhandler = new DBHandler();

    $user = $dbhandler->getUser($user_id);

    if ($user != NULL) {
      $item = $dbhandler->createItem($user, $name, $description, $price, $image_url, $time_available, $days_available);

      return $item;
    }
  }

  return NULL;
}

function updateItem($item_id, $name = "", $description = "", $price = "", $time_available = "", $days_available = ""){
  if ($item_id != "") {
    $dbhandler = new DBHandler();

    $item = $dbhandler->getItem($item_id);

    if ($item != NULL) {
      $success = $dbhandler->updateItem($item, $name, $description, $price, "", $time_available, $days_available);

      return $success;
    }
  }

  return FALSE;
}

function search($name){
  if ($name != "") {
    $dbhandler = new DBHandler();

    $items = $dbhandler->searchItem($name);

    return $items;
  }

  return NULL;
}

function book($item_id, $user_id, $start_date, $end_date){
  if ($user_id != "" && $item_id != "") {
    $dbhandler = new DBHandler();

    $item = $dbhandler->getItem($item_id);

    $from_user = $dbhandler->getUser($item->user_id);

    $to_user = $dbhandler->getUser($user_id);

    if ($item != NULL) {
      $request = $dbhandler->addRequest($item, $from_user, $to_user, $start_date, $end_date);

      return $request;
    }
  }
  return NULL;
}

function requestApprove($request_id){
  if ($request_id != "") {
    $dbhandler = new DBHandler();

    $approved = $dbhandler->setRequestApproved($request_id);

    return $approved;
  }
  return FALSE;
}

function requestRetrieve($request_id){
  if ($request_id != "") {
    $dbhandler = new DBHandler();

    $approved = $dbhandler->setRequestItemRetrieved($request_id);

    return $approved;
  }
  return FALSE;
}

function requestReturn($request_id){
  if ($request_id != "") {
    $dbhandler = new DBHandler();

    $approved = $dbhandler->setRequestItemReturned($request_id);

    return $approved;
  }
  return FALSE;
}

function getRequests($user_id){
  if ($user_id != "") {
    $dbhandler = new DBHandler();

    $requests = $dbhandler->getRequests($user_id);

    return $requests;
  }

  return NULL;
}

function getRequest($request_id){
  if ($request_id != "") {
    $dbhandler = new DBHandler();

    $requests = $dbhandler->getRequest($request_id);

    return $requests;
  }

  return NULL;
}

function getUser($user_id){
  if ($user_id != "") {
    $dbhandler = new DBHandler();

    $user = $dbhandler->getUser($user_id);

    return $user;
  }
  return NULL;
}

function upload($filename){
  $target_dir = "../../uploads/images/";

  $imageFileType = pathinfo($target_dir . basename($_FILES[$filename]["name"]),PATHINFO_EXTENSION);

  $nameLength = 10;
  $target_file_name = randomString($nameLength) . "." . $imageFileType;
  $target_file = $target_dir . $target_file_name; //basename($_FILES[$filename]["name"])

  while (file_exists($target_file)) {
    $target_file_name = randomString($nameLength) . "." . $imageFileType;
    $target_file = $target_dir . $target_file_name . "." . $imageFileType;
  }

  $check = getimagesize($_FILES[$filename]["tmp_name"]);
  if($check !== false) {
  } else {
    $errorMessage .= "File not a image.";
    return "";
  }

  if ($_FILES[$filename]["size"] > 200000000) {
    $errorMessage .= "File too large.";
    return "";
  }

  if (move_uploaded_file($_FILES[$filename]["tmp_name"], $target_file)) {
    return $target_file_name;
  } else {
    $errorMessage .= "Upload failed.";
    return "";
  }
}

 ?>
