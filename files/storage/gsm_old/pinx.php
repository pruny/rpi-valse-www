#!/usr/bin/php

<!-- This page is requested by the JavaScript, it updates the output pin's status and then print it -->

<?php

	//getting and using values for pins: 1, 5, 10, 11

	if (isset ($_GET["pin"]) && isset($_GET["status"]) ) {
	$pin = strip_tags($_GET["pin"]);
	$status = strip_tags($_GET["status"]);
	$pin_array = array("1","5","10","11","30","31");
	}

	//testing if values are numbers

	if ( is_numeric($pin) && is_numeric($status) && in_array($pin,$pin_array) && ($status == "0" || $status == "1" )) {

		//set the gpio's mode to output
		system ("gpio mode ".$pin." out");

		//set the gpio to high/low ...
		//and register changing time (use gpio2)
		$status = ($status xor 1) ? '1' : '0';
		system ("gpio2 write ".$pin." ".$status );

		//toggle buttons - gpio (1) & (5)
		if ( is_numeric($pin) && ( $pin=="1" || $pin=="5" )) {
			if ( $status == "1" ) {
			usleep(5000000);
			system ("gpio2 write ".$pin." 0");
			}
		}

	}

	//print fail if cannot use values

	else { echo ("fail"); }
?>

