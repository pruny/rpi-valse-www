<!DOCTYPE html>

<html>

<head>
  <!-- <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"> -->
  <link href="../../../local_fonts/css/FontAwesome/font-awesome.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet" type="text/css">
  <meta http-equiv="refresh" content="60">
  <meta charset="UTF-8">
  <title>TERMOMETRU</title>
  <link rel="stylesheet" href="css/style.css" media="screen" type="text/css" />

  <style>
  <!-- a{text-decoration:none} -->
  </style>

</head>

<body>

<a href="javascript:javascript:history.go(-1)"><h2 style="color:green">&nbsp; <i class="fa fa-mail-reply"></i> &nbsp;back</h2><span class="value_text"></a>

<<!-- description on screen center -->>
<style>h1 span { font-size:150%; }</style>
<div align="center"><div class="text"><h1><span style="color:yellow">Temperaturi</span></h1></div></div>

<br>

<div class="device">

<?php

  $device = file("/var/www/html/valse/files/data/TMPFS/temp.val");

  function create_device($value, $decimal, $unit, $label, $css_class="")
  {
    $device_html_str = "<div class='de'>
                         <div class='den'>
                          <div class='dene'>
                           <div class='denem'>
                            <div class='deneme'>
                             <div class = 'val_ %s'>
                              <span class = 'val_base'>%s</span>
                              <span class = 'val_decimal'>.%s</span>
                             </div>
                             <span class='unit'>%s</span>
                            </div>
                           </div>
                          </div>
                         </div>
                         <div class='label'>%s</div>
                        </div>";

    echo sprintf($device_html_str, $css_class, $value, $decimal, $unit, $label);
  }

  foreach($device as $line_num => $line)

  {
      $line_array  = explode(";", $line);
        $label       = str_replace(' ', '', $line_array[1]);
        $val_array   = explode( ".", $line_array[2] );
        $val_base    = str_replace(' ', '', $val_array[0]);
        $val_decimal = substr(str_replace(' ', '', $val_array[1]), 0, -4);
        $unit        = substr(str_replace(' ', '', $val_array[1]), -4);
      $css_class = "";
        if(mb_strlen($val_base) > 3) { $css_class = "four_char"; }
        else if(strlen($val_base) > 2) { $css_class = "three_char"; }

    create_device($val_base, $val_decimal, $unit, $label, $css_class);
  }

?>

</div>
</body>

</html>
