<?php
date_default_timezone_set("America/Mexico_City");
$fecha='29/03/2022';
$fecha1=str_replace('/','-',$fecha);
//$fecha= date('Y/m/d',strtotime(date("Y/m/d").' + 30 days '));
echo $fecha;
echo "\n";
echo date('d-m-Y',strtotime($fecha1.' + 1 month '));
echo "\n";
echo date('d-m-Y',strtotime($fecha1.' + 1 year '));
echo "\n";
echo date('d-m-Y',strtotime($fecha1.' + 1 days '));

?>