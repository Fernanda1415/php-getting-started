$(document).ready(function(){
//Deshabilitar los botones con la clase PDFTabla
$('.PDFTabla').attr("disabled",true);

    //Iniciar el calendario
    $('.datepicker').datepicker(
        {
            format:"dd/mm/yyyy",//formato de fecha
            setDefaultDate: false,
            minDate: new Date(),//para que solo pueda escoger a partir de la fecha de hoy
            firstDay: 1,//dia en que empieza la semana domingo =0  lunes =1 , etc.
            disableWeekend: false, //para que se seleccionen o no los fines de semana
            i18n:{//Poner las fechas en español
                months:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                monthsShort:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                weekdays:['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
                weekdaysShort:['Dom','Lun','Mar','Miérc','Juev','Vier','Sáb'],
                weekdaysAbbrev:['D','L','M','M','J','V','S'],
                cancel:'Cancelar',//cambiar el texto 'cancel' por 'cancelar'
            }
        }
        
    );
    //Detectar cualquier cambio en el input de la tasa de interes
    $('#interes').on('input',function()
    {
        var valorInt=$('#interes').val();

        if(valorInt>0)//si al menos hay un numero
        {
            let tiempo=document.querySelector('input[name=group1]:checked').value;//obtener valor del radiobutton
            switch(tiempo)
            {
                case "Semanas":
                    {
                        var TasaEfectiva=(valorInt/100)/52;
                        //pasar la tasa efectiva con 3 decimales
                        document.getElementById('tasaEfec').value=TasaEfectiva.toFixed(4);
                        break;
                    }
                case "Quincenas":
                    {
                        var TasaEfectiva=(valorInt/100)/26;
                        document.getElementById('tasaEfec').value=TasaEfectiva.toFixed(4);
                        break;
                    }
                case "Meses":
                    {
                        var TasaEfectiva=(valorInt/100)/12;
                        document.getElementById('tasaEfec').value=TasaEfectiva.toFixed(4);
                        break;
                    }
                case "Trimestres":
                    {
                        var TasaEfectiva=(valorInt/100)/4;
                        document.getElementById('tasaEfec').value=TasaEfectiva.toFixed(4);
                        break;
                    }
                case "Anios":
                    {
                        var TasaEfectiva=valorInt/100;
                        document.getElementById('tasaEfec').value=TasaEfectiva.toFixed(4);
                        break;
                    }
            }
            
        }
        else
        {
            document.getElementById('tasaEfec').value="";
        }
    });
    //Detectar algun cambio en los radiobutton para la tasa efectiva
    $('input[name=group1]').on('change',function()
    {
        let tiempo=document.querySelector('input[name=group1]:checked').value;//obtener valor del radiobutton
        
        var valorInt=$('#interes').val();

        if(valorInt>0)//si al menos hay un numero
        {
            let tiempo=document.querySelector('input[name=group1]:checked').value;//obtener valor del radiobutton
            switch(tiempo)
            {
                case "Semanas":
                    {
                        var TasaEfectiva=(valorInt/100)/52;
                        //pasar la tasa efectiva con 3 decimales
                        document.getElementById('tasaEfec').value=TasaEfectiva.toFixed(4);
                        break;
                    }
                case "Quincenas":
                    {
                        var TasaEfectiva=(valorInt/100)/26;
                        document.getElementById('tasaEfec').value=TasaEfectiva.toFixed(4);
                        break;
                    }
                case "Meses":
                    {
                        var TasaEfectiva=(valorInt/100)/12;
                        document.getElementById('tasaEfec').value=TasaEfectiva.toFixed(4);
                        break;
                    }
                case "Trimestres":
                    {
                        var TasaEfectiva=(valorInt/100)/4;
                        document.getElementById('tasaEfec').value=TasaEfectiva.toFixed(4);
                        break;
                    }
                case "Anios":
                    {
                        var TasaEfectiva=valorInt/100;
                        document.getElementById('tasaEfec').value=TasaEfectiva.toFixed(4);
                        break;
                    }
            }
            
        }
        else
        {
            document.getElementById('tasaEfec').value="";
        }
    });
    //Formulario
    $('#formAdd').validetta({
        bubblePosition: 'bottom',
        bubbleGapTop: 10,
        bubbleGapLeft: -5,
        onValid : function( event ) {
            
            event.preventDefault(); // Detiene el submit
            $.ajax({
                method:"post",
                // peticion ajax 
                url:"Financiamiento.php",
                data:$('#formAdd').serialize(),
                cache:false,
                success:function(respAX){
                    dataTable.clear().draw();
                    dataTable2.clear().draw();
                    dataTable3.clear().draw();
                    dataTable4.clear().draw();
                    dataTable5.clear().draw();
                    tablaTotal1.clear().draw();
                    tablaTotal2.clear().draw();
                    tablaTotal3.clear().draw();
                    tablaTotal4.clear().draw();
                    var fechaFinal;
                    var interesesTotal = 0;
                    var abonoCapTotal = 0;
                    

                    var respJson = JSON.parse(respAX);
                    //console.log(respJson.data);
                    //Tabla de pagos iguales ------------------------------------------------------------------------
                    var amortizacionTotal = 0;
                    var totalInteresesPagosIguales = 0;
                    var pagoTotalPagosIguales = 0;
                    for(var i=1;i<respJson.pagosIguales.length;i++)
                    {
                       dataTable.row.add(
                            [
                                respJson.pagosIguales[i]["periodo"],
                                respJson.fechas[i-1],//las fechas empiezan desde la posicion 0
                                "$"+respJson.pagosIguales[i]["saldoInicial"],
                                "$"+respJson.pagosIguales[i]["intereses"],
                                "$"+respJson.pagosIguales[i]["abonoCap"],
                                "$"+respJson.pagosIguales[i]["anualidad"],
                                "$"+respJson.pagosIguales[i]["saldoFinal"]
                            ]
                        ).draw();

                        //interesesTotal= interesesTotal + parseFloat( respJson.pagosIguales[i]["intereses"]);
                        //abonoCapTotal= abonoCapTotal + parseFloat( respJson.pagosIguales[i]["abonoCap"]);
                        
                    }
                    totalInteresesPagosIguales = respJson.totalInteresesPagosIguales;
                    amortizacionTotal= respJson.amortizacionTotal;
                    pagoTotalPagosIguales = respJson.pagoTotalPagosIguales;
                   // pagoTotal = interesesTotal + amortizacionTotal;
                    //console.log("Pagos iguales- Total de intereses: "+interesesTotal);
                    //console.log("Pagos iguales- Total de abono a capital: "+abonoCapTotal);
                    //console.log("Pagos iguales- Total de pago total: "+pagoTotal);

                    tablaTotal1.row.add(
                        [
                            respJson.fechas[respJson.fechas.length-1],
                            "$"+totalInteresesPagosIguales,
                            "$"+amortizacionTotal, 
                            "$"+pagoTotalPagosIguales
                        ]
                    ).draw();


                    //Tabla de pago al finalizar el periodo ------------------------------------------------------------------------
                    dataTable2.row.add(
                        [
                            respJson.pagoIntFinPeriodo[0]["periodo"],
                            respJson.fechas[respJson.fechas.length-1],//se toma la ultima fecha porque es solo 1 pago
                            "$"+respJson.pagoIntFinPeriodo[0]["pagoCapital"],
                            "$"+respJson.pagoIntFinPeriodo[0]["intereses"],
                            "$"+respJson.pagoIntFinPeriodo[0]["pagoFinal"]
                        ]
                    ).draw();

                    //Tabla pago de intereses al final de cada periodo(Bullet) ------------------------------------------
                    var interesesTotalB=0;
                    var prestamoInicialB = 0;
                    var pagoTotalB=0;
                    for(var j=1;j<respJson.pagoCadaPeriodo.length;j++)
                    {
                       dataTable3.row.add(
                            [
                                respJson.pagoCadaPeriodo[j]["periodo"],
                                respJson.fechas[j-1],//las fechas empiezan desde la posicion 0
                                "$"+respJson.pagoCadaPeriodo[j]["intereses"],
                                "$"+respJson.pagoCadaPeriodo[j]["pagoFinalPeriodo"],
                                "$"+respJson.pagoCadaPeriodo[j]["deudaDespuesPago"]
                            ]
                        ).draw();

                        interesesTotalB= interesesTotalB + parseFloat( respJson.pagoCadaPeriodo[j]["intereses"]);
                        //prestamoInicialB= prestamoInicialB + parseFloat( respJson.pagoCadaPeriodo[j]["pagoFinalPeriodo"]);
                        //console.log("Pago al finalizar el periodo No"+j+" = "+ parseFloat( respJson.pagoCadaPeriodo[j]["pagoFinalPeriodo"])+"...\n")
                    }

                    prestamoInicialB = respJson.pagoCadaPeriodo[1]["deudaDespuesPago"];
                    pagoTotalB = respJson.bulletPagototal;
                    /*console.log("Bullet- Total de intereses: "+interesesTotalB);
                    console.log("Bullet- Prestamo : "+prestamoInicialB);
                    console.log("Bullet- Total de pago total: "+pagoTotalB);*/

                    tablaTotal2.row.add(
                        [
                            respJson.fechas[respJson.fechas.length-1],
                            "$"+interesesTotalB,
                            "$"+prestamoInicialB, 
                            "$"+pagoTotalB
                        ]
                    ).draw();

                    //Tabla pago de intereses y una parte proporcional del principal cada periodo (Decrecientes) ------------------------------------------
                    var decrecientesAmortizacionTotal = 0;
                    var decrecientesInteresesTotales = 0;
                    var decrecientesPagoTotal = 0;
                    for(var j=1;j<respJson.pagoParteProporcional.length;j++)
                    {
                       dataTable4.row.add(
                            [
                                respJson.pagoParteProporcional[j]["periodo"],
                                respJson.fechas[j-1],//las fechas empiezan desde la posicion 0
                                "$"+respJson.pagoParteProporcional[j]["saldoInicial"],
                                "$"+respJson.pagoParteProporcional[j]["intereses"],
                                "$"+respJson.pagoParteProporcional[j]["pagoCap"],
                                "$"+respJson.pagoParteProporcional[j]["pagoPeriodo"],
                                "$"+respJson.pagoParteProporcional[j]["saldoFinal"]
                            ]
                        ).draw();
                    }

                    decrecientesAmortizacionTotal = respJson.decrecientesAmortizacionTotal;
                    decrecientesInteresesTotales = respJson.decrecientesInteresesTotales;
                    decrecientesPagoTotal = respJson.decrecientesPagoTotal;

                    tablaTotal3.row.add(
                        [
                            respJson.fechas[respJson.fechas.length-1],
                            "$"+decrecientesInteresesTotales,
                            "$"+decrecientesAmortizacionTotal, 
                            "$"+decrecientesPagoTotal
                        ]
                    ).draw();
                    //Tabla pagos crecientes --------------------------------------------------------------------------
                    var crecientesAmortizacionTotal = 0;
                    var crecientesInteresesTotales = 0;
                    var crecientesPagoTotal = 0;
                    for(var i=1;i<respJson.pagoCreciente.length;i++)
                    {
                       dataTable5.row.add(
                            [
                                respJson.pagoCreciente[i]["periodo"],
                                respJson.fechas[i-1],//las fechas empiezan desde la posicion 0
                                "$"+respJson.pagoCreciente[i]["saldoInicial"],
                                "$"+respJson.pagoCreciente[i]["intereses"],
                                "$"+respJson.pagoCreciente[i]["abonoCap"],
                                "$"+respJson.pagoCreciente[i]["pago"],
                                "$"+respJson.pagoCreciente[i]["saldoFinal"]
                            ]
                        ).draw();
                    }
                    crecientesAmortizacionTotal = respJson.crecientesAmortizacionTotal;
                    crecientesInteresesTotales = respJson.crecientesInteresesTotales;
                    crecientesPagoTotal = respJson.crecientesPagoTotal;

                    tablaTotal4.row.add(
                        [
                            respJson.fechas[respJson.fechas.length-1],
                            "$"+crecientesInteresesTotales,
                            "$"+crecientesAmortizacionTotal, 
                            "$"+crecientesPagoTotal
                        ]
                    ).draw();

                    //Habilitar los botones con la clase PDFTabla
                    $('.PDFTabla').attr("disabled",false);
                    /*$.alert({
                        title:"<h3 align='center'> Tablas de amortización</h3>",
                        //mensaje desde el servidor
                        content:"Tablas de amortizacioón creadas",
                        theme: "supervan",
                        type:"green",
                        icon: "fas fa-money-bill-alt fa-2x",
                        //bootstrap, se inabilita para poder usarlo
                        boxWidth: "100%",

                        useBootstrap: false,
                        //callback para tomar deciones, recargar pagina
                        onDestroy:function(){
                            if(respJson.status == 1 && respJson.tipoU == "AD"){
                                //document.location.href = "./administrador/";    
                             }
                             else if(respJson.status == 1 && respJson.tipoU == "AL"){
                                 document.location.href = "./inicio/index.php";
                                 ////document.location.href = "./pages/tweb20192.php"
                                  
                             }
                             else{
                                 //document.location.reload(true);
                             }
                        }
                    });*/




                }
            });    
        }
      });


    var tablaTotal1= $('#TablaFinal1').DataTable({
    language: {
        "emptyTable": "No hay información",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "paginate": {
            "first": "Primero",
            "last": "Último",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    },
    "paging":true,
    "processing":true,
    "serverSide":false,
    "order": [],
    "info":true,
    "lengthChange": false,
    "bFilter":false,
        
    });
    var tablaTotal2= $('#TablaFinal2').DataTable({
        language: {
            "emptyTable": "No hay información",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "paging":true,
        "processing":true,
        "serverSide":false,
        "order": [],
        "info":true,
        "lengthChange": false,
        "bFilter":false,
            
    });

    var tablaTotal3= $('#TablaFinal3').DataTable({
    language: {
        "emptyTable": "No hay información",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "paginate": {
            "first": "Primero",
            "last": "Último",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    },
    "paging":true,
    "processing":true,
    "serverSide":false,
    "order": [],
    "info":true,
    "lengthChange": false,
    "bFilter":false,
        
    });

    var tablaTotal4= $('#TablaFinal4').DataTable({
        language: {
            "emptyTable": "No hay información",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "paging":true,
        "processing":true,
        "serverSide":false,
        "order": [],
        "info":true,
        "lengthChange": false,
        "bFilter":false,
            
    });

    var dataTable= $('#Tabla').DataTable({
    language: {
        "emptyTable": "No hay información",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "paginate": {
            "first": "Primero",
            "last": "Último",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    },
    "paging":true,
    "processing":true,
    "serverSide":false,
    "order": [],
    "info":true,
    "lengthChange": false,
    "bFilter":false,
        
    });
    var dataTable2= $('#Tabla2').DataTable({
        language: {
            "emptyTable": "No hay información",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "paging":true,
        "processing":true,
        "serverSide":false,
        "order": [],
        "info":true,
        "lengthChange": false,
        "bFilter":false,
            
        });
    var dataTable3= $('#Tabla3').DataTable({
            language: {
                "emptyTable": "No hay información",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "paging":true,
            "processing":true,
            "serverSide":false,
            "order": [],
            "info":true,
            "lengthChange": false,
            "bFilter":false,
                
            });
    var dataTable4= $('#Tabla4').DataTable({
        language: {
            "emptyTable": "No hay información",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
                }
            },
        "paging":true,
        "processing":true,
        "serverSide":false,
        "order": [],
        "info":true,
        "lengthChange": false,
        "bFilter":false,                    
    });
    var dataTable5= $('#Tabla5').DataTable({
        language: {
            "emptyTable": "No hay información",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
                }
            },
        "paging":true,
        "processing":true,
        "serverSide":false,
        "order": [],
        "info":true,
        "lengthChange": false,
        "bFilter":false,                    
    });


/////////////////////////////////////////////////////////////////////////////////////////////

});