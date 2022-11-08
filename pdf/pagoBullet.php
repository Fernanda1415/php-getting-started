<?php

session_start();
//obtener los datos de la sesion
$montoNecesitado=$_SESSION["monto"];
$prestamista=$_SESSION["prestamista"];
$interes=$_SESSION["tasaInteresAnual"];
$tasaEfec=$_SESSION["tasaEfectiva"];
$meses=$_SESSION["periodos"];
$fecha=$_SESSION["fecha"];
$group1=$_SESSION["tipoPlazo"];

require_once __DIR__ .'./../assets/mpdf/vendor/autoload.php';
/////////////////////////////////////////////////////////////////////////
// Create an instance of the class:
$mpdfConfig = array(
    'mode' => 'utf-8', 
    //'format' => 'A4',
    'margin_header' => 10,     // 30mm not pixel
    'margin_footer' => 10,     // 10mm
    'margin-bottom' => 20,
    //'orientation' => 'P'    
);
$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->SetTitle('Pago Bullet');//titulo del documento

$stylesheet = file_get_contents('./../css/estiloPDF.css');
$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->setFooter('{PAGENO}');
$html='<br>';//variable html(se guarda todo el codigo html aqui)
/////////////////////////////////////////////////////////////////////

//Cabecera
$mpdf->setHTMLHeader('<div class="row">
    <div class="col s6 l6 m6">
        <p align="left" style="font-size:15px;">Tablas de amortización</p>
    </div>
    <div class="col s5 l6 m6">
        <p align="right" style="font-size:13px;">Pago Bullet</p>
    </div>
</div>
');
////////////////////////////////////////////////////////////////////////////////////////////////
//Calculos
date_default_timezone_set("America/Mexico_City");
$NvaFecha=str_replace('/','-',$fecha);//cambiar el formato 01/08/2022 por 01-08-2022 para que no haya problema en las operaciones

$arreglo["pagoCadaPeriodo"] = [];
$arreglo["fechas"] = [];//lista de las fechas
$TasaInteres=0.0;

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
////////////////////////////////////////////////////////////////////////////////////////////////
$html.='<h5 class="teal-text text-accent-4"><b>Datos</b></h5>';
    $html.='<h6><b>Prestamista: </b>'.$prestamista.'</h6>
    <h6><b>Monto de la deuda: </b>$'.number_format($montoNecesitado,2).'</h6>
    <h6><b>Tasa de interes anual: </b>'.$interes.'%</h6>
    <h6><b>Tasa efectiva: </b>'.$tasaEfec.'</h6>';
    //Checar si es 1 periodo para poner 1 mes/1 semana/1 año/1 trimestre etc.
    
    if($meses==1)
        {
            switch($group1)
            {
            case "Semanas":
                {
                    $html.='<h6><b>Periodos: </b>1 Semana</h6>';
                    break;
                }
            case "Quincenas":
                {
                    $html.='<h6><b>Periodos: </b>1 Quincena</h6>';
                    break;
                }
            case "Meses":
                {
                    $html.='<h6><b>Periodos: </b>1 Mes</h6>';
                    break;
                }
            case "Trimestres":
                {
                    $html.='<h6><b>Periodos: </b>1 Trimestre</h6>';
                    break;
                }
            case "Anios":
                {
                    $html.='<h6><b>Periodos: </b>1 Año</h6>';

                    break;
                }

        }
    }
    else
    {
        if($group1=="Anios")
        {
            $html.='<h6><b>Periodos: </b>'.$meses.' Años</h6>';
        }
        else
        {
            $html.='<h6><b>Periodos: </b>'.$meses.' '.$group1.'</h6>';
        }
    }
    $html.='<h6><b>Fecha de adquisición de la deuda: </b>'.$fecha.'</h6>';
    $html.='<h6><b>Fecha del primer pago: </b>'.$arreglo["fechas"][0].'</h6>';
    $html.='<h6><b>Fecha del último pago: </b>'.$arreglo["fechas"][sizeof($arreglo["fechas"])-1].'</h6>';
    $html.='<hr><br>';
$html.='<h4 class="teal-text text-darken-2 center">Tabla de amortización de pago tipo Bullet</h4>';
//$html.='<h6>Lista de los activos fijos, activos diferidos y el capital de trabajo con su respectivo total.</h6><br>';
//////////////////////////////////////////////
$html.='
  <table class="highlight responsive-table centered">
      <thead>
        <tr>
            <th colspan="5"><h6 class="blue-text center">Total de pagos</h6></th>
        </tr>
        <tr>
          <th># Pago</th>
          <th>Fecha</th>
          <th>Intereses</th>
          <th>Pago al final del período</th>
          <th>Deuda después del pago</th>
        </tr>
      </thead>
      <tbody>';

//PAGO DE INTERESES AL FINAL DE CADA PERIODO Y PAGO DEL PRINCIPAL AL FINAL DEL PLAZO - Bullet
$pagoFinalPeriodoTotal=0;
$interesPeriodo=$montoNecesitado*$TasaInteres;
$totalIntereses=0;
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
        
        $totalIntereses=$totalIntereses+$interesPeriodo;
    }
    $arreglo["pagoCadaPeriodo"][]=$arrayAux;
}
$arrayAux=[];
$arrayAux["periodo"]=$meses;
$arrayAux["intereses"]=$interesPeriodo;
$arrayAux["pagoFinalPeriodo"]=$interesPeriodo+$montoNecesitado;
$arrayAux["deudaDespuesPago"]=0;
$arreglo["pagoCadaPeriodo"][]=$arrayAux;
$totalIntereses=$totalIntereses+$interesPeriodo;
for($j=1;$j<sizeof($arreglo["pagoCadaPeriodo"]);$j++)
{
    $html.='<tr>
                <td>'.$arreglo["pagoCadaPeriodo"][$j]["periodo"].'</td>
                <td>'.$arreglo["fechas"][$j-1].'</td>
                <td align="right">$'.number_format($arreglo["pagoCadaPeriodo"][$j]["intereses"],2).'</td>
                <td align="right">$'.number_format($arreglo["pagoCadaPeriodo"][$j]["pagoFinalPeriodo"],2).'</td>
                <td align="right">$'.number_format($arreglo["pagoCadaPeriodo"][$j]["deudaDespuesPago"],2).'</td>
            </tr>';
}
$html.='</tbody>
</table>
<br>
';
////////////////////////////////////////////////////////////////////////////////////////////////
//TABLA RESUMEN
$html.='<h5 class="teal-text text-darken-2">Resumen de tu deuda</h5>';
$html.='
  <table class="highlight responsive-table centered">
      <thead>
        <tr>
            <th colspan="4"><h6 class="blue-text center">Resumen de deuda</h6></th>
        </tr>
        <tr>
          <th>Fecha de liquidación</th>
          <th>Total de intereses</th>
          <th>Pago principal</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>';

    
$html.='<tr>
    <td>'.$arreglo["fechas"][sizeof($arreglo["fechas"])-1].'</td>
    <td align="right">$'.number_format($totalIntereses,2).'</td>
    <td align="right">$'.number_format($arreglo["pagoCadaPeriodo"][1]["deudaDespuesPago"],2).'</td>
    <td align="right">$'.number_format($pagoFinalPeriodoTotal + $interesPeriodo + $montoNecesitado,2).'</td>
</tr>
</tbody>
</table>
<br><br>
';

////////////////////////////////////////////////////////////////////////////////////////////////
// Write some HTML code:
$mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);

// Output a PDF file directly to the browser
$mpdf->Output();
?>