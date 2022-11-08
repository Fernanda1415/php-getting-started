<?php

    $sub_array = array();


    $sub_array["periodo"]=10;
    $sub_array["saldoInicial"] = 20;
    $sub_array["anualidad"] = 30;
    $sub_array["montoIntereses"] =40;
    $sub_array["pagoAcapital"] = 50;
    $respAX["data"][] = $sub_array;
    
    $sub_array["periodo"]=10;
    $sub_array["saldoInicial"] = 20;
    $sub_array["anualidad"] = 30;
    $sub_array["montoIntereses"] =40;
    $sub_array["pagoAcapital"] = 50;
    $respAX["data"][] = $sub_array;

    $sub_array["periodo"]=10;
    $sub_array["saldoInicial"] = 20;
    $sub_array["anualidad"] = 30;
    $sub_array["montoIntereses"] =40;
    $sub_array["pagoAcapital"] = 50;
    $respAX["data"][] = $sub_array;

    $sub_array["periodo"]=10;
    $sub_array["saldoInicial"] = 20;
    $sub_array["anualidad"] = 30;
    $sub_array["montoIntereses"] =40;
    $sub_array["pagoAcapital"] = 50;
    $respAX["data"][] = $sub_array;
    echo json_encode($respAX);


?>