<?php
include("./../pages/getPosts.php");
session_start();//crear sesion
//pasar todos los datos del formulario a la sesion
$_SESSION["monto"]=$montoNecesitado;
$_SESSION["prestamista"]=$prestamista;
$_SESSION["tasaInteresAnual"]=$interes;
$_SESSION["tasaEfectiva"]=$tasaEfec;
$_SESSION["periodos"]=$meses;
$_SESSION["fecha"]=$fecha;
$_SESSION["tipoPlazo"]=$group1;



date_default_timezone_set("America/Mexico_City");
$NvaFecha=str_replace('/','-',$fecha);//cambiar el formato 01/08/2022 por 01-08-2022 para que no haya problema en las operaciones

$respAX["status"]=1;
$arreglo["pagosIguales"] = [];
$arreglo["pagoIntFinPeriodo"] = [];
$arreglo["pagoCadaPeriodo"] = [];
$arreglo["pagoParteProporcional"] = [];
$arreglo["pagoCreciente"] = [];
$arreglo["fechas"] = [];//lista de las fechas
$TasaInteres=0.0;
//Tasa de interes Checar si es por semanas. quincenas, etc
//Tambien se checan las fechas

switch($group1)
{
    case "Semanas":
        {
            $TasaInteres=($interes/100)/52;

            for($i=0;$i<$meses;$i++)
            {
                $NvaFecha=date('d-m-Y',strtotime($NvaFecha.' + 7 days '));//Se suman 7 dias
                $arreglo["fechas"][]=str_replace('-','/',$NvaFecha);//se regresa al formato 01/08/2022
            }

            break;
        }
    case "Quincenas":
        {
            $TasaInteres=($interes/100)/26;

            for($i=0;$i<$meses;$i++)
            {
                $NvaFecha=date('d-m-Y',strtotime($NvaFecha.' + 15 days '));//Se suman 15 dias
                $arreglo["fechas"][]=str_replace('-','/',$NvaFecha);//se regresa al formato 01/08/2022
            }

            break;
        }
    case "Meses":
        {
            $TasaInteres=($interes/100)/12;

            for($i=0;$i<$meses;$i++)
            {
                $NvaFecha=date('d-m-Y',strtotime($NvaFecha.' + 1 month'));//Se suman 1 mes
                $arreglo["fechas"][]=str_replace('-','/',$NvaFecha);//se regresa al formato 01/08/2022
            }

            break;
        }
    case "Trimestres":
        {
            $TasaInteres=($interes/100)/4;

            for($i=0;$i<$meses;$i++)
            {
                $NvaFecha=date('d-m-Y',strtotime($NvaFecha.' + 3 month'));//Se suman 3 meses
                $arreglo["fechas"][]=str_replace('-','/',$NvaFecha);//se regresa al formato 01/08/2022
            }

            break;
        }
    case "Anios":
        {
            $TasaInteres=$interes/100;

            for($i=0;$i<$meses;$i++)
            {
                $NvaFecha=date('d-m-Y',strtotime($NvaFecha.' + 1 year'));//Se suman 1 mes
                $arreglo["fechas"][]=str_replace('-','/',$NvaFecha);//se regresa al formato 01/08/2022
            }

            break;
        }

}

///////////////////////////////////////////////////////////////////////////////
//PAGOS IGUALES//
$amortizacionTotal = 0;
$totalInteresesPagosIguales=0;
$pagoTotalPagosIguales = 0;
for($i=0;$i<=$meses;$i++)
{
    $arrayAux=[];
    if($i==0)//periodo 0, solo es el saldo final
    {
        $arrayAux["periodo"]=$i;
        $arrayAux["saldoInicial"]=0;
        $arrayAux["intereses"]=0;
        $arrayAux["abonoCap"]=0;
        $arrayAux["anualidad"]=0;
        $arrayAux["saldoFinal"]=$montoNecesitado;
    }
    else
    {
        $arrayAux["periodo"]=$i;
        $arrayAux["saldoInicial"]=$arreglo["pagosIguales"][$i-1]["saldoFinal"];
        $arrayAux["intereses"]=$arrayAux["saldoInicial"]*$TasaInteres;
        $totalInteresesPagosIguales = $totalInteresesPagosIguales + $arrayAux["saldoInicial"]*$TasaInteres;
        $arrayAux["abonoCap"]=(($montoNecesitado*$TasaInteres)/(1-pow((1+$TasaInteres),$meses*(-1))))-$arrayAux["intereses"];
        $amortizacionTotal = $amortizacionTotal + (($montoNecesitado*$TasaInteres)/(1-pow((1+$TasaInteres),$meses*(-1))))-$arrayAux["intereses"];
        $arrayAux["anualidad"]=($montoNecesitado*$TasaInteres)/(1-pow((1+$TasaInteres),$meses*(-1)));
        $arrayAux["saldoFinal"]=$arrayAux["saldoInicial"]-$arrayAux["abonoCap"];;
    }
    $arreglo["pagosIguales"][]=$arrayAux;
}

$arreglo["totalInteresesPagosIguales"] = number_format( $totalInteresesPagosIguales,2);
$arreglo["amortizacionTotal"] = number_format($amortizacionTotal,2);
$pagoTotalPagosIguales = $totalInteresesPagosIguales + $amortizacionTotal;
$arreglo["pagoTotalPagosIguales"] = number_format($pagoTotalPagosIguales,2);

///////////////////////////////////////////////////////////////////////////

//PAGO DE CAPITAL E INTERESES AL FINALIZAR EL PERIODO DEL PRESTAMO//
$arrayAux=[];
$arrayAux["periodo"]=1;
$arrayAux["pagoCapital"]=$montoNecesitado;
$arrayAux["pagoFinal"]=$montoNecesitado*(pow(1+$TasaInteres,$meses));
$arrayAux["intereses"]=$arrayAux["pagoFinal"]-$montoNecesitado;
$arreglo["pagoIntFinPeriodo"][]=$arrayAux;
//////////////////////////////////////////////////////////////////////////

//PAGO DE INTERESES AL FINAL DE CADA PERIODO Y PAGO DEL PRINCIPAL AL FINAL DEL PLAZO - Bullet
$pagoFinalPeriodoTotal=0;
$interesPeriodo=$montoNecesitado*$TasaInteres;
for($i=0;$i<$meses;$i++)
{
    $arrayAux=[];
    if($i==0)//periodo 0, solo es el saldo final
    {
        $arrayAux["periodo"]=$i;
        $arrayAux["intereses"]=0;
        $arrayAux["pagoFinalPeriodo"]=0;
        $arrayAux["deudaDespuesPago"]=$montoNecesitado;
    }
    else
    {
        $arrayAux["periodo"]=$i;
        $arrayAux["intereses"]=$interesPeriodo;
        $arrayAux["pagoFinalPeriodo"]=$interesPeriodo;
        $pagoFinalPeriodoTotal = $pagoFinalPeriodoTotal +$interesPeriodo;
        $arrayAux["deudaDespuesPago"]=$montoNecesitado;
    }
    $arreglo["pagoCadaPeriodo"][]=$arrayAux;
}
$arrayAux=[];
$arrayAux["periodo"]=$meses;
$arrayAux["intereses"]=$interesPeriodo;
$arrayAux["pagoFinalPeriodo"]=$interesPeriodo+$montoNecesitado;
$arrayAux["deudaDespuesPago"]=0;
$arreglo["pagoCadaPeriodo"][]=$arrayAux;
$pagoFinalPeriodoTotal = $pagoFinalPeriodoTotal + $interesPeriodo + $montoNecesitado;
$arreglo["bulletPagototal"]= number_format( $pagoFinalPeriodoTotal,2);

//////////////////////////////////////////////////////////////////////////

//PAGO DE INTERESES Y UNA PARTE PROPORCIONAL DEL PRINCIPAL CADA PERIODO - PAGOS DECRECIENTES
$pagoCap=$montoNecesitado/$meses;
$decrecientesAmortizacionTotal = 0;
$decrecientesInteresesTotales = 0;
$decrecientesPagoTotal = 0;
for($i=0;$i<=$meses;$i++)
{
    $arrayAux=[];
    if($i==0)//periodo 0, solo es el saldo final
    {
        $arrayAux["periodo"]=$i;
        $arrayAux["saldoInicial"]=0;
        $arrayAux["intereses"]=0;
        $arrayAux["pagoCap"]=0;
        $arrayAux["pagoPeriodo"]=0;
        $arrayAux["saldoFinal"]=$montoNecesitado;
    }
    else
    {
        $arrayAux["periodo"]=$i;
        $arrayAux["saldoInicial"]=$arreglo["pagoParteProporcional"][$i-1]["saldoFinal"];
        $arrayAux["intereses"]=$arrayAux["saldoInicial"]*$TasaInteres;
        $decrecientesInteresesTotales = $decrecientesInteresesTotales + $arrayAux["saldoInicial"]*$TasaInteres;
        $arrayAux["pagoCap"]=$pagoCap;
        $decrecientesAmortizacionTotal = $decrecientesAmortizacionTotal + $pagoCap;
        $arrayAux["pagoPeriodo"]=$arrayAux["intereses"]+$arrayAux["pagoCap"];
        $arrayAux["saldoFinal"]=$arrayAux["saldoInicial"]-$arrayAux["pagoCap"];;
    }
    $arreglo["pagoParteProporcional"][]=$arrayAux;
}
$arreglo["decrecientesAmortizacionTotal"]= number_format($decrecientesAmortizacionTotal,2);
$arreglo["decrecientesInteresesTotales"] = number_format($decrecientesInteresesTotales,2);
$decrecientesPagoTotal = $decrecientesAmortizacionTotal + $decrecientesInteresesTotales;
$arreglo["decrecientesPagoTotal"] = number_format($decrecientesPagoTotal,2);

//////////////////////////////////////////////////////////////////////////
$crecientesAmortizacionTotal = 0;
$crecientesInteresesTotales = 0;
$crecientesPagoTotal = 0;
//PAGOS CRECIENTES
for($i=0;$i<=$meses;$i++)
{
    $arrayAux=[];
    if($i==0)//periodo 0, solo es el saldo final
    {
        $arrayAux["periodo"]=$i;
        $arrayAux["saldoInicial"]=0;
        $arrayAux["intereses"]=0;
        $arrayAux["abonoCap"]=0;
        $arrayAux["pago"]=0;
        $arrayAux["saldoFinal"]=$montoNecesitado;
    }
    else
    {
        $arrayAux["periodo"]=$i;
        $arrayAux["saldoInicial"]=$arreglo["pagoCreciente"][$i-1]["saldoFinal"];
        $arrayAux["intereses"]=$arrayAux["saldoInicial"]*$TasaInteres;
        $crecientesInteresesTotales = $crecientesInteresesTotales + $arrayAux["saldoInicial"]*$TasaInteres;
        $arrayAux["pago"]=($montoNecesitado/$meses)*pow(1+$TasaInteres,$i);
        $arrayAux["abonoCap"]=$arrayAux["pago"]-$arrayAux["intereses"];
        $crecientesAmortizacionTotal = $crecientesAmortizacionTotal + $arrayAux["pago"]-$arrayAux["intereses"];
        $arrayAux["saldoFinal"]=$arrayAux["saldoInicial"]-$arrayAux["abonoCap"];;
    }
    $arreglo["pagoCreciente"][]=$arrayAux;
}
$arreglo["crecientesAmortizacionTotal"] = number_format($crecientesAmortizacionTotal,2);
$arreglo["crecientesInteresesTotales"] = number_format($crecientesInteresesTotales,2);
$crecientesPagoTotal = $crecientesAmortizacionTotal + $crecientesInteresesTotales;
$arreglo["crecientesPagoTotal"] = number_format($crecientesPagoTotal,2);



//////////////////////////////////////////////////////////////////////////
//Poner formato de $ en el arreglo
for($i=0;$i<sizeof($arreglo["pagosIguales"]);$i++)
{
    $arreglo["pagosIguales"][$i]["saldoInicial"]=number_format($arreglo["pagosIguales"][$i]["saldoInicial"],2);
    $arreglo["pagosIguales"][$i]["intereses"]=number_format($arreglo["pagosIguales"][$i]["intereses"],2);
    $arreglo["pagosIguales"][$i]["abonoCap"]=number_format($arreglo["pagosIguales"][$i]["abonoCap"],2);
    $arreglo["pagosIguales"][$i]["anualidad"]=number_format($arreglo["pagosIguales"][$i]["anualidad"],2);
    $arreglo["pagosIguales"][$i]["saldoFinal"]=number_format($arreglo["pagosIguales"][$i]["saldoFinal"],2);
}

$arreglo["pagoIntFinPeriodo"][0]["pagoCapital"]=number_format($arreglo["pagoIntFinPeriodo"][0]["pagoCapital"],2);
$arreglo["pagoIntFinPeriodo"][0]["pagoFinal"]=number_format($arreglo["pagoIntFinPeriodo"][0]["pagoFinal"],2);
$arreglo["pagoIntFinPeriodo"][0]["intereses"]=number_format($arreglo["pagoIntFinPeriodo"][0]["intereses"],2);

for($i=0;$i<sizeof($arreglo["pagoCadaPeriodo"]);$i++)
{
    $arreglo["pagoCadaPeriodo"][$i]["intereses"]=number_format($arreglo["pagoCadaPeriodo"][$i]["intereses"],2);
    $arreglo["pagoCadaPeriodo"][$i]["pagoFinalPeriodo"]=number_format($arreglo["pagoCadaPeriodo"][$i]["pagoFinalPeriodo"],2);
    $arreglo["pagoCadaPeriodo"][$i]["deudaDespuesPago"]=number_format($arreglo["pagoCadaPeriodo"][$i]["deudaDespuesPago"],2);
}

for($i=0;$i<sizeof($arreglo["pagoParteProporcional"]);$i++)
{
    $arreglo["pagoParteProporcional"][$i]["saldoInicial"]=number_format($arreglo["pagoParteProporcional"][$i]["saldoInicial"],2);
    $arreglo["pagoParteProporcional"][$i]["intereses"]=number_format($arreglo["pagoParteProporcional"][$i]["intereses"],2);
    $arreglo["pagoParteProporcional"][$i]["pagoCap"]=number_format($arreglo["pagoParteProporcional"][$i]["pagoCap"],2);
    $arreglo["pagoParteProporcional"][$i]["pagoPeriodo"]=number_format($arreglo["pagoParteProporcional"][$i]["pagoPeriodo"],2);
    $arreglo["pagoParteProporcional"][$i]["saldoFinal"]=number_format($arreglo["pagoParteProporcional"][$i]["saldoFinal"],2);
}

for($i=0;$i<sizeof($arreglo["pagoCreciente"]);$i++)
{
    $arreglo["pagoCreciente"][$i]["saldoInicial"]=number_format($arreglo["pagoCreciente"][$i]["saldoInicial"],2);
    $arreglo["pagoCreciente"][$i]["intereses"]=number_format($arreglo["pagoCreciente"][$i]["intereses"],2);
    $arreglo["pagoCreciente"][$i]["abonoCap"]=number_format($arreglo["pagoCreciente"][$i]["abonoCap"],2);
    $arreglo["pagoCreciente"][$i]["pago"]=number_format($arreglo["pagoCreciente"][$i]["pago"],2);
    $arreglo["pagoCreciente"][$i]["saldoFinal"]=number_format($arreglo["pagoCreciente"][$i]["saldoFinal"],2);
}
echo json_encode($arreglo);
?>