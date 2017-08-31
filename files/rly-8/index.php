<!--
Date         : 	22/08/2016
Author       : 	A Benkorich (Dzduino)
website      : 	www.dzduino.com
Description  : 	Control DFR RLY-8 ViaWebPage (JSON)
Refernces    : 	-> www.stackoverflow.com/questions/14081124/
		-> www.php.net/manual/en/function.fsockopen.php	
				
IMPORTANT : A Local web server is need since we are using PHP, XAMPP or USBWebserver (Portable) are recommanded
-->
<html>
<head>
	<title> RLY-8 WEB CONTROL </title>

<link href="../../local_fonts/css/FontAwesome/font-awesome.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="../data/favicon.ico">

	<style>
		<!-- a{text-decoration:none} -->
		tab1 { padding-left: 4em; }
		tab2 { padding-left: 8em; }
		tab3 { padding-left: 12em; }
		tab4 { padding-left: 16em; }
		tab5 { padding-left: 20em; }
		#cmd { width: 65%; padding:5px; border: 3px solid green; background: aquamarine; font-weight: bold; font-family: sans-sherif; font-size: 20; }
		#submit { width: 18%; border-radius: 1px; height: 40px; border: 3px solid blue; background: aqua; font-weight: bold; font-family: sans-sherif; font-size: 20; }
		#container { width: 90%; margin: 5% auto; color : red; font-weight:bold; font-family: sans-sherif; font-size: 20; }
		#top {padding: 10px; border: Solid 5px white; border-radius: 10px; }
	</style>
</head>
<body background="../data/img/carbon_fibre.png">

<a href="javascript:javascript:history.go(-1)"><h6 style="color:green">&nbsp; <i class="fa fa-mail-reply"></i> &nbsp;back</h6><span class="value_text"></a>

  <div id="container">
    <div id="top">
			<span style="text-align: center; color: yellow"><h3> DFR RLY-8 Relay Web Control Test </h3></span>
			<div id="">
				<!-- form to submit the JSON command -->
				<form name="cmd_form" method="post" action="" >
				<span style="color : aquamarine";> <h4> Type in the box bellow command as JSON Protocol: </h4></span >
				<span style="color : yellow"><h6> &nbsp;&nbsp;EXAMPLE: </h6></span >
				<span style="color : yellow"><h6> &nbsp;&nbsp;&nbsp;&nbsp;- GET DEVICE NAME: ====================> &nbsp;&nbsp;{"get":"name"} </h6></span >
				<span style="color : yellow"><h6> &nbsp;&nbsp;&nbsp;&nbsp;- SET DEVICE NAME: ====================> &nbsp;&nbsp;{"name":"DFRobot"} </h6></span >
				<span style="color : yellow"><h6> &nbsp;&nbsp;&nbsp;&nbsp;- GET FW VERSION: =====================> &nbsp;&nbsp;{"get":"version"} </h6></span >
				<span style="color : yellow"><h6> &nbsp;&nbsp;&nbsp;&nbsp;- GET DHCP STATE: =====================> &nbsp;&nbsp;{"get":"dhcp"} </h6></span >
				<span style="color : yellow"><h6> &nbsp;&nbsp;&nbsp;&nbsp;- SET DHCP STATE (on is open, off is closed): ===> &nbsp;&nbsp;{"dhcp":"on"} </h6></span >
				<span style="color : yellow"><h6> &nbsp;&nbsp;&nbsp;&nbsp;- GET NETWORK CONFIGURATION: =======> &nbsp;&nbsp;{"get":"netconfig"} </h6></span >
				<span style="color : yellow"><h6> &nbsp;&nbsp;&nbsp;&nbsp;- SET UP NETWORK CONFIGURATION: =====> &nbsp;&nbsp;{"ipaddr":"192.168.1.10","gateway":"192.168.1.1","netmask":"255.255.255.0","port":"2000"} </h6></span >
				<span style="color : yellow"><h6> &nbsp;&nbsp;&nbsp;&nbsp;- GET RELAY STATUS: ===================> &nbsp;&nbsp;{"get":"relayStatus"} </tab4></h6></span >
				<span style="color : yellow"><h6> &nbsp;&nbsp;&nbsp;&nbsp;- SET RELAY STATE (on is open, off is closed): ==> &nbsp;&nbsp;{"relay1":"on"} or {"relay1":"on","relay2":"on"} etc. </h6></span >
				<span style="color : yellow"><h6> &nbsp;&nbsp;&nbsp;&nbsp;- RESTART DEVICE: =====================> &nbsp;&nbsp;{"reboot":"1"} </h6></span >
					<!-- Command input -->
					<input id="cmd" name ="cmd" type="text"  required="" value='{"cmd":"test"}'>
					<!-- Submit button-->
					<input id="submit" name="subm" type="submit" value="Send to RLY-8">
					<span style="color: orangered"><h4>Controller Feedback:</h4></span >
				</form>
			</div>
			<!-- all the communications with the RLY-8 controller happens hier -->
      <?php
          $addr = "192.168.1.123";								// RLY-8 default IP adress 
          $port = 2000;										// RLY-8 default port 
          $timeout = 30;									// connection time-out in sec
				
            if (isset($_POST["cmd"])){								// check if a submit was done, otherwise the communication will start after page loading
              $cmd = $_POST["cmd"] ;								// capture the input command
              $fp = fsockopen ($addr, $port, $errno, $errstr, $timeout);			// initiate a socket connection 
              if (!$fp) {
                echo "($errno) $errstr\n";							// return the error if no connection was established
              }
              else {
                fwrite ($fp, $cmd);								// Send the command to the connected device
                echo '<span style="color: orangered"><h5>'.fread($fp, 128).'</h5></span >';		// echo the return string from the device
                fclose ($fp);									// close the connection
              }
            }
      ?>
      <!-- Done for the communication with the controller -->

    </div>

		<!-- Thank you -->
		<!-- <span style="text-align: center; color: white"><h6> Thank's to A. Benkorich (Dzduino)</br>www.dzduino.com </h6></span> -->
  </div>
</body>
</html>