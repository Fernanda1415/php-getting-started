<?php
    session_start();
    //Comprobar si esta permitido Ingresar
    $validacion=$_SESSION["valid"];
    require_once __DIR__ .'./../assets/mpdf/vendor/autoload.php';


   /* $pago=json_decode($_POST['pago']);
    $fecha=json_decode($_POST['fecha']);
    $saldoInicial=json_decode($_POST['saldoInicial']);
    $intereses=json_decode($_POST['intereses']);
    $amortizacion=json_decode($_POST['amortizacion']);
    $anualidad=json_decode($_POST['anualidad']);
    $saldoFinal=json_decode($_POST['saldoFinal']);*/

    /*use Dompdf\Dompdf;
    use Dompdf\Options;
    use Dompdf\Exception as DomException;
    use Dompdf\Option;
    
    // Introducimos HTML de prueba
$html = '<h1>Hola mundo!</h1>';
 
// Instanciamos un objeto de la clase DOMPDF.
$pdf = new DOMPDF();
 
// Definimos el tamaño y orientación del papel que queremos.
$pdf->set_paper("A4", "portrait");
 
// Cargamos el contenido HTML.
$pdf->load_html(utf8_decode($html));
 
// Renderizamos el documento PDF.
$pdf->render();
 
// Enviamos el fichero PDF al navegador.
//$pdf->stream('FicheroEjemplo.pdf');
$data["resp"]=1;
echo json_encode($data);*/
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

$mpdf->SetTitle('PresupuestoInversion');//titulo del documento

//$stylesheet = file_get_contents('https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css');
//$stylesheet = file_get_contents('ejemplo.css');
//$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);

$mpdf->setFooter('{PAGENO}');

$html=$validacion;
// Write some HTML code:
$mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);

// Output a PDF file directly to the browser
$dato=$mpdf->Output('filename.pdf','I');
?>