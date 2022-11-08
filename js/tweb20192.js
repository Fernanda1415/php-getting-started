$(document).ready(function(){
    $('.fixed-action-btn').floatingActionButton();

    $(".borrar").on("click", function(){
        var boleta = $(this).attr("data-borrar");
        $.confirm({
            title:"<h3 align='center'>TWeb 20192</h3>",
            content:"<h5 align='center'>Desea eliminar la boleta: "+boleta+"</h5>",
            icon: "fas fa-code fa-2x",
            type:"blue",
            boxWidth:"50%",
            useBootstrap:false,
            buttons: {
                Sí:function() {
                    $.ajax({
                        method:"post",
                        url:"./tweb20192Borrar_AX.php",
                        data:{boleta:boleta},
                        cache:false,
                        success:function(respAX){
                            var respJson = JSON.parse(respAX);
                            var color = (respJson.status == 1) ? "green" : "red"; 
                            $.alert({
                                title:"<h3 align='center'>TWeb 20192</h3>",
                                content:respJson.mensaje,
                                type:color,
                                theme: "supervan",
                                icon: "fas fa-code fa-2x",
                                boxWidth: "50%",
                                useBootstrap: false,
                                onDestroy:function(){
                                    if(respJson.status == 1){
                                        window.location.reload(true);
                                    }
                                }
                            });
                        }
                    });
                },
                No:function () {
                    //
                }
            }
        });
    });

    $(".editar").on("click", function(e){
        var boleta = $(this).attr("data-editar");
        window.location.href = "./tweb20192Editar.php?boleta="+boleta;
    });

    $(".pdf").on("click", function(e){
        var boleta = $(this).attr("data-pdf");
        window.location.href = "./tweb20192PDF.php?boleta="+boleta;
    });

    $(".correo").click(function(){
        var boleta = $(this).attr("data-correo");
        window.location.href = "./tweb20192Correo.php?boleta="+boleta;
    });
});