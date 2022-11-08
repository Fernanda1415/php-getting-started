<!DOCTYPE html>
<html lang="es">
<html>
<head>
<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>Referencias</title>   
     <!-- Normalize CSS -->
	<link rel="stylesheet" href="./../../css/normalize.css">    
     <!-- Materialize CSS -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">  
     <!-- Iconos -->
     <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

        <script src="https://kit.fontawesome.com/49f2039b91.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Malihu jQuery custom content scroller CSS -->
	<link rel="stylesheet" href="./../../css/jquery.mCustomScrollbar.css">    
    <!-- Confirm -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <!-- MaterialDark CSS -->
	<link rel="stylesheet" href="./../../css/style.css">
    <!--version Materialize to collapsible menu--> 
     <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">-->
</head>
<body class="stretched sticky-responsive-menu" data-loader="7">
     <!-- Nav Lateral -->
    <section class="NavLateral full-width">
        <?php include("./../../html/comunes/lateral.html"); ?>
    </section>
    <!-- Page content -->
    <section class="ContentPage full-width ">
        <!-- Nav Info -->
        <div class="ContentPage-Nav full-width">
           <?php include("./../../html/comunes/nav.html"); ?>       
        </div>
        <!-- Notifications area -->
        <section class="z-depth-3 NotificationArea">
            <?php include("./../../html/comunes/notification.html"); ?>      
        </section>


        <!--Content-->
        <div class="row">
            <?php include("index.html");?>
        </div>
        <!-- Footer -->   
        <footer class="footer-MaterialDark grey darken-4">
             <?php include("./../../html/comunes/footer.html"); ?> 
        </footer>
    </section>
    <!--  Alert JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <!-- jQuery  -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="./../../js/jquery-2.2.0.min.js"><\/script>')</script>
    <!-- Materialize JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script> 
    <!-- Malihu jQuery custom content scroller JS -->
	<script src="./../../js/jquery.mCustomScrollbar.concat.min.js"></script>  
    <!-- MaterialDark JS  -->
	<script src="./../../js/main.js"></script>
  <script src="./../../js/index.js"></script>
</body>
</html>