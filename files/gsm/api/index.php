<?php

require_once 'api.php';

////////////////
//// CONFIG ////
////////////////

// "buttons"
//----------

$pin_array = array (
  array( "label" => "ON / OFF",       "board" => 18, "pin" =>  5,   "mode" => "MIXT",    "ref_value" => -1, "value" => -1, "icon" => "&#xF021;", "category" => "MODEM"),
  array( "label" => "RESET",          "board" => 22, "pin" =>  6,   "mode" => "MIXT",    "ref_value" => -1, "value" => -1, "icon" => "&#xF021;", "category" => "MODEM"),

  array( "label" => "NETWORK",        "board" => NA, "pin" =>  888, "mode" => "F_EXIST", "ref_value" => -1, "value" => -1, "icon" => "&#xF1A5;", "category" => "STATUS",
          "INFILE_array" => array(
            "/var/www/TMPFS/GSM/REGISTERED" => 1,
            "/var/www/TMPFS/GSM/NO-NETWORK" => -1,
            "/var/www/TMPFS/GSM/GPRS" => 1
          )
        ),
  array( "label" => "GPRS",           "board" => NA, "pin" =>  999, "mode" => "F_EXIST", "ref_value" => -1, "value" => -1, "icon" => "&#xF1A5;", "category" => "STATUS",
          "INFILE_array" => array(
            "/var/www/TMPFS/GSM/REGISTERED" => -1,
            "/var/www/TMPFS/GSM/NO-NETWORK" => -1,
            "/var/www/TMPFS/GSM/GPRS" => 1
            )
          ),

  array( "label" => "SMS",            "board" => NA, "pin" => 333,  "mode" => "F_TOUCH", "ref_value" => 0,  "value" => 0,  "icon" => "&#xF011;", "category" => "MODE", "file" => "/var/www/TMPFS/GSM/PPP", "file_touch_value" => 0),
  array( "label" => "INTERNET",       "board" => NA, "pin" => 666,  "mode" => "F_TOUCH", "ref_value" => 0,  "value" => 0,  "icon" => "&#xF011;", "category" => "MODE", "file" => "/var/www/TMPFS/GSM/PPP", "file_touch_value" => 1)

);




////////////////
//// ROUTES ////
////////////////

// Read the status of all pins and update $pin_array
//--------------------------------------------------
API::Serve('GET', '/read', function () {
  global $pin_array;

  $GPIO_values = read_GPIO();
  $INFILE_values = read_INFILE();

  // update pin_array with new values
  //---------------------------------
  foreach ($pin_array as $index => $i) {
    if ($i["mode"] == "MIXT") continue;
    else if($i["mode"] == "F_TOUCH" && array_key_exists('file', $pin_array[$index])) {
      if(file_exists($pin_array[$index]["file"])) {
        $pin_array[$index]["value"] = $pin_array[$index]["file_touch_value"];
      }
      else {
        $pin_array[$index]["value"] = $pin_array[$index]["file_touch_value"] ^ 1;
      }
      continue;
    }
    else if($i["mode"] == "F_EXIST") {
      $pin_array[$index]["value"] = $INFILE_values[$pin_array[$index]["pin"]];
      continue;
    }
    $pin_array[$index]["value"] = $GPIO_values[$pin_array[$index]["pin"]];
  }

  return API::Reply($pin_array);
});


// Change pin value
//-----------------

API::Serve('GET', '/write/(#num)/(#num)', function ($pin, $val) {
  global $pin_array;

  if (!check_input($pin, $val)) return API::$HTTP[400]; // Check if pin and value are valid
  $pin_array_index = find_index_by_pin($pin);
  if($pin_array_index == -1) return API::$HTTP[400];    // Check if pin is in pin_array


  // Create or delete file if pin_array has "file" and "file_touch_value" keys
  // -------------------------------------------------------------------------
  if (array_key_exists('file', $pin_array[$pin_array_index]) && array_key_exists('file_touch_value', $pin_array[$pin_array_index])) {
    if($val == $pin_array[$pin_array_index]["file_touch_value"]) {
      system ("touch ".$pin_array[$pin_array_index]['file']);
    }
    else {
      system ("rm -f ".$pin_array[$pin_array_index]['file']);
    }
  }

  $pin_mode = $pin_array[$pin_array_index]["mode"];

  if($pin_mode == "F_TOUCH" && array_key_exists($pin_array[$pin_array_index])) {

    return API::$HTTP[200];
  }

  write_GPIO($pin, $val, $pin_mode);

  return API::$HTTP[200];
});




///////////////////////////
//// HELPER FUNCTIONS  ////
///////////////////////////


function check_input($pin, $val) {
//------------------------------
  global $pin_array;
  $pin_list = array_map(function($ar) { return $ar["pin"]; }, $pin_array );
  if (!(in_array($pin,$pin_list) && ($val == "0" || $val == "1" ))) return false;
  return true;
}


function find_index_by_pin($pin) {
//------------------------------
  global $pin_array;
  foreach ($pin_array as $index => $i) {
    if($i["pin"] == $pin && $i["mode"] != "IN") return $index;
  }
  return -1;
}




///////////////////////////
//// IN_FILE FUNCTIONS ////
///////////////////////////


function read_INFILE() {
//--------------------

  global $pin_array;
  $INFILE_values = array();
  foreach ($pin_array as $index => $i) {
    if( $i["mode"] == "F_EXIST" && array_key_exists('INFILE_array', $i) ) {
      $found = false;
      foreach ($i["INFILE_array"] as $infile_name => $infile_val) {
        if(file_exists($infile_name)) {
          $INFILE_values[$i["pin"]] = $infile_val;
          $found = true;
        }
      }
      if(!$found) $INFILE_values[$i["pin"]] = $i["ref_value"];
    }
  }
  return $INFILE_values;
}




////////////////////////
//// GPIO FUNCTIONS ////
////////////////////////


function read_GPIO() {
//------------------

  $cmd = "gpio readall | head -n 23 | tail -n 20 | awk -F'|' -v ORS=' ' '{print $3, $6, $13, $10}' | sed -n 's/ \+/ /gp'";
  $cmd_result = array();
  exec($cmd, $cmd_result);

  $GPIO_res = explode(" ", $cmd_result[0]);
  $GPIO_values = array();

  // create GPIO pin/value lookup array
  for($i=1; $i<sizeof($GPIO_res); $i+=2) {
    $GPIO_values[$GPIO_res[$i]] = $GPIO_res[$i+1];
  }

  return $GPIO_values;
}


function write_GPIO($pin, $val, $pin_mode) {
//----------------------------------------

  // exclude file based pins
  //------------------------
  if($pin_mode == "F_EXIST" || $pin_mode == "F_TOUCH") return true;

  // If MIXT pin change value to $val for 1.5 sec then back to 0 (change mode to out then in)
  // pullup resistors for MIXT pin are external, no need 'gpio mode $pin up'
  //-----------------------------------------------------------------------------------------
  if($pin_mode == "MIXT") {
    //$cmd = "gpio mode $pin out && gpio2 write $pin $val && sleep 1.5 && gpio2 write $pin 0 && gpio mode $pin in && gpio mode $pin up";
    $cmd = "gpio mode $pin out && gpio2 write $pin 1 && sleep 1.5 && gpio2 write $pin 0 && gpio mode $pin in"; 
    exec($cmd);
    return true;
  }
}

?>
