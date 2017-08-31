<!DOCTYPE html>
<title>WOL</title>
<html>
<head>
<!-- <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"> -->
<link href="../../local_fonts/css/FontAwesome/font-awesome.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="../data/favicon.ico">
<style>
h1 span { font-size:200%; }
<!-- a{text-decoration:none} -->
</style>
</head>
<a href="javascript:javascript:history.go(-1)"><h2 style="color:green">&nbsp; <i class="fa fa-mail-reply"></i> &nbsp;back</h2><span class="value_text"></a>
<body background="../../files/data/img/carbon_fibre.png">
<h1><span style="color:#990000">
... sending magic packet ...
<br><br>
the local machine turns ON
<?php
     exec ( "wakeonlan 00:13:20:c7:72:76" );
?>
</span></h1>
</body>
</html>
