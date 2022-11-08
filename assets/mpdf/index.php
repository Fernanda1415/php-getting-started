<?php
    session_start();
    //Comprobar si esta permitido Ingresar
    $validacion=$_SESSION["valid"];
    if($validacion==2||$validacion==1)//solo para administrador
    {
        session_destroy();
        header("Location:http://localhost/tt2/");
        
    }
    else{
        echo 'No tiene autorizacion';
        header("Location:http://localhost/tt2/");
        
    }
?>