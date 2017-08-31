<?php

require_once 'api.php';


$pin_array = array (
  array( "label" => "IN#1", "pin" =>  1, "mode" => "IN", "ref_value" => 0, "value" => -1, "icon" => "&#xf08c;", "category" => "SENZORI"),
  array( "label" => "IN#2", "pin" =>  2, "mode" => "IN", "ref_value" => 0, "value" => -1, "icon" => "&#xf08c;", "category" => "SENZORI"),
  array( "label" => "IN#3", "pin" =>  3, "mode" => "IN", "ref_value" => 0, "value" => -1, "icon" => "&#xf08c;", "category" => "SENZORI"),
  array( "label" => "IN#4", "pin" => 12, "mode" => "IN", "ref_value" => 0, "value" => -1, "icon" => "&#xf08c;", "category" => "SENZORI"),
  array( "label" => "IN#5", "pin" => 13, "mode" => "IN", "ref_value" => 0, "value" => -1, "icon" => "&#xf08c;", "category" => "SENZORI"),
  array( "label" => "IN#6", "pin" => 14, "mode" => "IN", "ref_value" => 0, "value" => -1, "icon" => "&#xf08c;", "category" => "SENZORI"),
  array( "label" => "IN#7", "pin" =>  5, "mode" => "IN", "ref_value" => 0, "value" => -1, "icon" => "&#xf08c;", "category" => "SENZORI"),
  array( "label" => "IN#8", "pin" =>  6, "mode" => "IN", "ref_value" => 0, "value" => -1, "icon" => "&#xf08c;", "category" => "SENZORI"),

  array( "label" => "1WIRE GRID",   "pin" => 21,  "mode" => "OUT", "ref_value" => 1, "value" => -1, "icon" => "&#xF011;", "category" => "EXECUȚIE"),
  array( "label" => "OUT#2",        "pin" => 22,  "mode" => "OUT", "ref_value" => 1, "value" => -1, "icon" => "&#xF011;", "category" => "EXECUȚIE"),
  array( "label" => "OUT#3",        "pin" => 23,  "mode" => "OUT", "ref_value" => 1, "value" => -1, "icon" => "&#xF011;", "category" => "EXECUȚIE"),
  array( "label" => "ARTEZIANĂ",    "pin" => 24,  "mode" => "OUT", "ref_value" => 1, "value" => -1, "icon" => "&#xF011;", "category" => "EXECUȚIE"),
  array( "label" => "APĂ SOLAR",    "pin" => 25,  "mode" => "OUT", "ref_value" => 1, "value" => -1, "icon" => "&#xF011;", "category" => "EXECUȚIE", "file" => "/var/www/TMPFS/tap.open"),
  array( "label" => "CALORIFER 1",  "pin" => 27,  "mode" => "OUT", "ref_value" => 1, "value" => -1, "icon" => "&#xF011;", "category" => "EXECUȚIE"),
  array( "label" => "CALORIFER 2",  "pin" => 28,  "mode" => "OUT", "ref_value" => 1, "value" => -1, "icon" => "&#xF011;", "category" => "EXECUȚIE"),
  array( "label" => "LUMINĂ CURTE", "pin" => 29,  "mode" => "OUT", "ref_value" => 1, "value" => -1, "icon" => "&#xF011;", "category" => "EXECUȚIE"),

  array( "label" => "MANUAL", "pin" => 26,  "mode" => "OUT", "ref_value" => 0, "value" => -1, "icon" => "&#xF0AD;", "category" => "AUTO", "file" => "/var/www/TMPFS/auto.stop"),

  array( "label" => "POWER",  "pin" =>  5,  "mode" => "MIXT", "ref_value" => -1, "value" => -1, "icon" => "&#xF021;", "category" => "GSM"),
  array( "label" => "RESET",  "pin" =>  6,  "mode" => "MIXT", "ref_value" => -1, "value" => -1, "icon" => "&#xF021;", "category" => "GSM")
);


//// ROUTES

// Initialize pins to their default mode: IN/OUT
API::Serve('GET', '/init', function () {
  global $pin_array;

  foreach ($pin_array as $i) {
    if($i["mode"] == "OUT" || $i["mode"] == "IN" )
      system("gpio2 mode ".$i["pin"]." ".strtolower($i["mode"]));
  }

  return API::$HTTP[200];
});

// Read the status of all pins and update $pin_array
API::Serve('GET', '/read', function () {
  global $pin_array;

  foreach ($pin_array as $index => $i) {
    if ($i["mode"] == "MIXT") continue;
    $pin_value = -1;
    exec ("gpio2 read ".$i["pin"], $pin_value );
    foreach ($pin_value as $val) {
      $pin_array[$index]["value"] = intval($val);
    }
  }

  return API::Reply($pin_array);
});

// Change pin value
API::Serve('GET', '/write/(#num)/(#num)', function ($pin, $val) {
  global $pin_array;

  if (!check_input($pin, $val)) return API::$HTTP[400]; // Check if pin and value are valid
  $pin_array_index = find_index_by_pin($pin);
  if($pin_array_index == -1) return API::$HTTP[400]; // Check if pin is in pin_array

  // Create or delete file if pin_array has "file" key
  if (array_key_exists('file', $pin_array[$pin_array_index])) {
    if($val == "1") system ("touch ".$pin_array[$pin_array_index]['file']);
    else system ("rm -f ".$pin_array[$pin_array_index]['file']);
  }

  // If MIXT pin change value to $val for 1.5 sec then back to 0 (change mode to out then in)
  if($pin_array[$pin_array_index]["mode"] == "MIXT") {
    $cmd = "gpio2 mode $pin out && gpio2 write $pin $val && sleep 1.5 && gpio2 write $pin 0 && gpio2 mode $pin in && gpio2 mode $pin up";
    exec($cmd);
    return API::$HTTP[200];
  }

  // Change pin value
  system("gpio2 mode ".$pin." out");
  exec ("gpio2 write ".$pin." ".$val);

  return API::$HTTP[200];
});


//// HELPER FUNCTIONS

function check_input($pin, $val){
  global $pin_array;
  $pin_list = array_map(function($ar) { return $ar["pin"]; }, $pin_array );
  if (!(in_array($pin,$pin_list) && ($val == "0" || $val == "1" ))) return false;
  return true;
}

function find_index_by_pin($pin) {
  global $pin_array;
  foreach ($pin_array as $index => $i) {
    if($i["pin"] == $pin && $i["mode"] != "IN") return $index;
  }
  return -1;
}

?>
