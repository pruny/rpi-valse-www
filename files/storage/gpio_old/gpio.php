#!/usr/bin/php

<!-- This page is requested by the JavaScript, it updates the output pin's status and then print it -->

<?php

/*
=====================================================
  Getting and using values for next pins:
	- outputs: 21, 22, 23, 24, 25, 27, 28, 29
	- auto-manual: (26)
	- gsm: [1], [5]
=====================================================
*/

	$path = "/var/www/TMPFS";

	//>>> output buttons <<<
	if (isset ($_GET["pin"]) && isset($_GET["status"]) ) {
	$pin = strip_tags($_GET["pin"]);
	$status = strip_tags($_GET["status"]);
	$pin_array = array("21","22","23","24","25","27","28","29","26");
	}
	//testing if values are numbers...
	if ( is_numeric($pin) && is_numeric($status) && in_array($pin,$pin_array) && ($status == "0" || $status == "1" )) {
		//set the gpio's mode to output
		system ("gpio mode ".$pin." out");
		//set the gpio to high/low ...
		//and register changing time (use gpio2)
		$status = ($status xor 1) ? '1' : '0';
		system ("gpio2 write ".$pin." ".$status );
	}
	//>>> auto-manual button
	if ( is_numeric($pin) && $pin=="26" ) {
		if ( $status == "1" ) system ("touch ".$path."/"."auto.stop");
		elseif ( $status == "0" ) {
		system ("rm -f ".$path."/"."auto.stop");
		}
	}
	//>>> tap open
	if ( is_numeric($pin) && $pin=="25" ) {
		if ( $status == "0" ) system ("touch ".$path."/"."tap.open");
		elseif ( $status == "1" ) {
		system ("rm -f ".$path."/"."tap.open");
		}
	}
	//...or print fail if cannot use values
	else { echo ("fail"); }

	//>>> gsm buttons <<<
	//>>> power button (5) & reset button (6)
	if (isset ($_GET["pin"]) && isset($_GET["status"]) ) {
	$pin = strip_tags($_GET["pin"]);
	$status = strip_tags($_GET["status"]);
	$gsm_pin_array = array("5","6");
	}
	//testing if values are numbers...
	if ( is_numeric($pin) && is_numeric($status) && in_array($pin,$gsm_pin_array) && ($status == "0" || $status == "1" )) {
		//set the gpio's mode to output
		system ("gpio mode ".$pin." out");
		//set the gpio to high/low ...
		//and register changing time (use gpio2)
		$status = ($status xor 1) ? '1' : '0';
		system ("gpio2 write ".$pin." ".$status );
		if ( is_numeric($pin) && ( $pin=="5" || $pin=="6" )) {
			if ( $status == "1" ) {
			//system ("sleep 2 && gpio2 write ".$pin." 0");
			sleep(1.5);
			system ("gpio2 write ".$pin." 0");
			}
		}
		//re-set gsm power & reset pins mode to input-pullup
		system ("gpio mode ".$pin." in") && system ("gpio mode ".$pin." up");
	}
	//...or print fail if cannot use values
	else { echo ("fail"); }
?>

