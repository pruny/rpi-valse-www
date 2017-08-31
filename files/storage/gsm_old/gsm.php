<!DOCTYPE html>
<html>
	<head>
		<link href="../../local_fonts/css/FontAwesome/font-awesome.min.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet" type="text/css">
		<meta http-equiv="refresh" content="5">
		<meta charset="utf-8">
		<style>
		<!-- a{text-decoration:none} -->
		</style>
		<title>GSM/GPRS STATUS</title>
	</head>
	<body>

    <a href="../../index.html"><h2 style="color:green">&nbsp; &nbsp; &nbsp; <i class="fa fa-mail-reply"></i> &nbsp;back</h2><span class="value_text"></a>

    <div id="container">
	<!-- On/Off button's picture -->

	<?php
	//this php script generate the first page in function of the gpio's status

	//GSM/GPRS buttons
		$current_label = 0;
		$status = array (0, 0, 0, 0, 0, 0);
		$labels = array (
		// Button Labels
		"SMS SYGNALL",
		"GSM STATUS",
		"POWER",
		"RESET",
		"CSD",
		"GPRS",
		"INTERNET",	// over ppp connection
		);

	//sygnalling leds: sms & status

	echo("<br>");
	echo('<div class"description"><div class="main_text"><h1 style="color:red">&nbsp; &nbsp; STATUS LED</h1><span class="value_text"></div></div>');

	for ($i = 10; $i < 11; $i++) {
		//set the pin's mode to output and read them
		system("gpio mode ".$i." out");
		exec ("gpio read ".$i, $status[$i], $return );
		//if off (gpio = 0 - "low" level)
		if ($status[$i][0] == 0 ) {
		echo ("<div class='btn_div'><a class='btn_off' rel='external' id='button_".$i."' target='off'>&#xF1A5;</a><span id='light_red'></span>
		<h4>".$labels[$current_label]."</h4></div>");					//single line label
                //<div class='btn_label'><h4>".$labels[$current_label]."</h4></div></div>");	//multiple line label
		}
		//if on ( gpio = 1 - "high" level)
		if ($status[$i][0] == 1 ) {
		echo ("<div class='btn_div'><a class='btn_on' rel='external' id='button_".$i."' target='on'>&#xF1A5;</a><span id='light_green'></span>
		<h4>".$labels[$current_label]."</h4></div>");					//single line label
                //<div class='btn_label'><h4>".$labels[$current_label]."</h4></div></div>");	//multiple line label
		}
		//go to next label
		$current_label++;
	}
	for ($i = 11; $i < 12; $i++) {
		//set the pin's mode to input and read them
		system("gpio mode ".$i." in");
		exec ("gpio read ".$i, $status[$i], $return );
		//if off (gpio = 0 - "low" level)
		if ($status[$i][0] == 0 ) {
		echo ("<div class='btn_div'><a class='btn_off' rel='external' id='button_".$i."' target='off'>&#xF1A5;</a><span id='light_red'></span>
		<h4>".$labels[$current_label]."</h4></div>");					//single line label
                //<div class='btn_label'><h4>".$labels[$current_label]."</h4></div></div>");	//multiple line label
		}
		//if on ( gpio = 1 - "high" level)
		if ($status[$i][0] == 1 ) {
		echo ("<div class='btn_div'><a class='btn_on' rel='external' id='button_".$i."' target='on'>&#xF1A5;</a><span id='light_green'></span>
		<h4>".$labels[$current_label]."</h4></div>");					//single line label
                //<div class='btn_label'><h4>".$labels[$current_label]."</h4></div></div>");	//multiple line label
		}
		//go to next label
		$current_label++;
	}

	//power & reset buttons

	echo("<br>");
	echo('<div class"description"><div class="main_text"><h1 style="color:red">&nbsp; &nbsp; HW BUTTONS</h1><span class="value_text"></div></div>');

	//power
	for ($i = 5; $i < 6; $i++) {
		//set the pin's mode to output and read them
		system("gpio mode ".$i." out");
		exec ("gpio read ".$i, $status[$i], $return );
		//if off (gpio = 0 - "low" level)
		if ($status[$i][0] == 0 ) {
		echo ("<div class='btn_div'><a class='btn_off' rel='external' id='button_".$i."' target='off'>&#xF021;</a><span id='light_red'></span>
		<h4>".$labels[$current_label]."</h4></div>");					//single line label
                //<div class='btn_label'><h4>".$labels[$current_label]."</h4></div></div>");	//multiple line label
		}
		//if on ( gpio = 1 - "high" level)
		if ($status[$i][0] == 1 ) {
		echo ("<div class='btn_div'><a class='btn_on' rel='external' id='button_".$i."' target='on'>&#xF021;</a><span id='light_green'></span>
		<h4>".$labels[$current_label]."</h4></div>");					//single line label
                //<div class='btn_label'><h4>".$labels[$current_label]."</h4></div></div>");	//multiple line label
		}
		//go to next label
		$current_label++;
		system("gpio mode ".$i." in");
		exec ("gpio read ".$i, $status[$i], $return );
	}
	// reset
	for ($i = 1; $i < 2; $i++) {
		//set the pin's mode to output and read them
		system("gpio mode ".$i." out");
		exec ("gpio read ".$i, $status[$i], $return );
		//if off (gpio = 0 - "low" level)
		if ($status[$i][0] == 0 ) {
		echo ("<div class='btn_div'><a class='btn_off' rel='external' id='button_".$i."' target='off'>&#xF021;</a><span id='light_red'></span>
		<h4>".$labels[$current_label]."</h4></div>");					//single line label
                //<div class='btn_label'><h4>".$labels[$current_label]."</h4></div></div>");	//multiple line label
		}
		//if on ( gpio = 1 - "high" level)
		if ($status[$i][0] == 1 ) {
		echo ("<div class='btn_div'><a class='btn_on' rel='external' id='button_".$i."' target='on'>&#xF021;</a><span id='light_green'></span>
		<h4>".$labels[$current_label]."</h4></div>");					//single line label
                //<div class='btn_label'><h4>".$labels[$current_label]."</h4></div></div>");	//multiple line label
		}
		//go to next label
		$current_label++;
	}

	//gsm/gprs "buttons"

	echo("<br>");
	echo('<div class"description"><div class="main_text"><h1 style="color:red">&nbsp; &nbsp; MODE</h1><span class="value_text"></div></div>');
//===============================================
		echo ("<div class='btn_div'><a href='gsm.php?csd=CSD' class='btn_off' rel='external' id='button_".$i."'>&#xF011;</a><span id='light_red'></span>
		<h4>".$labels[$current_label]."</h4></div>");
		$current_label++;
		echo ("<div class='btn_div'><a href='gsm.php?gprs=GPRS' class='btn_off' rel='external' id='button_".$i."'>&#xF011;</a><span id='light_red'></span>
		<h4>".$labels[$current_label]."</h4></div>");					//single line label
                //<div class='btn_label'><h4>".$labels[$current_label]."</h4></div></div>");	//multiple line label
		$current_label++;

		//if voice/sms
		if(isset($_GET['csd'])){
		exec ("sudo /usr/bin/poff itead");
		sleep(2);
		exec ("sudo service gammu-smsd start");
		echo ("<div class='btn_div'><a href='gsm.php?csd=CSD' class='btn_off' rel='external' id='button_".$i."'>&#xF05C;</a><span id='light_red'></span>
		<h4>".$labels[$current_label]."</h4></div>");					//single line label
                //<div class='btn_label'><h4>".$labels[$current_label]."</h4></div></div>");	//multiple line label
		}

		//if gprs
		if(isset($_GET['gprs'])){
		exec ("sudo service gammu-smsd stop");
		sleep(2);
		exec ("sudo /usr/bin/pon itead");
		echo ("<div class='btn_div'><a href='gsm.php?gprs=GPRS' class='btn_on' rel='external' id='button_".$i."'>&#xF05D;</a><span id='light_green'></span>
		<h4>".$labels[$current_label]."</h4></div>");					//single line label
                //<div class='btn_label'><h4>".$labels[$current_label]."</h4></div></div>");	//multiple line label
		}
		$current_label++;
//===============================================
	//}
	?>
	</div>
	<script src="js/script.js"></script>
	</body> 
</html>
