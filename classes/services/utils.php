<?php
function randomString($length){
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

function currentDateAndTime(){
  $format = "d-m-Y H:i";

  $date = date($format);

  return $date;
}

function currentDate(){
  $format = "d-m-Y";

  $date = date($format);

  return $date;
}

function getDaysArrayFromString($str){
  $data["mon"] = strpos($str, "mon") !== FALSE;
  $data["tue"] = strpos($str, "tue") !== FALSE;
  $data["wed"] = strpos($str, "wed") !== FALSE;
  $data["thur"] = strpos($str, "thur") !== FALSE;
  $data["fri"] = strpos($str, "fri") !== FALSE;
  $data["sat"] = strpos($str, "sat") !== FALSE;
  $data["sun"] = strpos($str, "sun") !== FALSE;

  return $data;
}

function getTimesFromString($str){
  $data = explode("-", $str);

  return $data;
}

/*KEY FUNCS*/
$secret_format = "mHdYi";
function getKey(){
  $array["date"] = date($secret_format);

  $array["key"] = (intVal($array["date"]) * 24091 * 25589) + 24781;

  return $array;
}

function isKeyCorrect($key, $date){
  $correct_date = date_format($date, $secret_format);

  $correct_key = $intVal($correct_date) * 24091 * 25589 + 24781;

  if ($key == $correct_key) {
    return TRUE;
  }
  return FALSE;
}

/*
day - 2 digits
month - 2 digits
year - 4 digits
*/
function dateToString($day, $month, $year){
  $string = "$day-$month-$year";

  return $string;
}

/* interval is the days from now as a integer */
function dateWithInterval($date, $interval){
  $new_date = DateTime::createFromFormat("d-m-Y", "$date");
  $i = new DateInterval("P" . $interval . "D");

  $new_date->add($i);

  return $new_date->format("d-m-Y");
}

/* Compares 2 dates as strings, returns:
  -1 -> date1 < date2
  0 -> date 1 == date 2
  1 -> date 1 > date 2
*/
function compareDateStrings($date1, $date2){
  $tmp_date1 = DateTime::createFromFormat("d-m-Y", "$date1");
  $tmp_date2 = DateTime::createFromFormat("d-m-Y", "$date2");

  if ($tmp_date1 < $tmp_date2){
    return -1;
  } else if ($tmp_date1 == $tmp_date2){
    return 0;
  } else {
    return 1;
  }
}

//xss mitigation functions
function xssafe($data,$encoding='UTF-8'){
   return htmlspecialchars($data,ENT_QUOTES | ENT_HTML401,$encoding);
}

function xecho($data){
   echo xssafe($data);
}

//xml
function toXML($data, $rootname = "data", $childkey = ""){
  $xml_data = new SimpleXMLElement("<$rootname/>");

  array_to_xml($data,$xml_data,$childkey);

  return $xml_data->asXML();
}

function array_to_xml( $data, &$xml_data, $childkey = "" ) {
    foreach( $data as $key => $value ) {
        if ($childkey != "") {
          $key = $childkey;
        }
        if( is_numeric($key) ){
            $key = 'item'.$key; //dealing with <0/>..<n/> issues
        }
        if( is_array($value) ) {
            $subnode = $xml_data->addChild($key);
            array_to_xml($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
     }
}

function array_to_kv_array($array, $keyname){
  $data = array();

  foreach ($array as $value){
    $item = $value->toArray();
    array_push($data, $item);
  }
  return $data;
}

function redirect($url){
  echo "<script>window.location.replace('$url');</script>";
}

 ?>
