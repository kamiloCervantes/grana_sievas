<!DOCTYPE html>
<html lang="es"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="EVAL AUTOEVALUACION" />
    <meta name="keywords" content="EVAL, GESTION ACADEMICA, INSTITUCIONES EDUCATIVAS, IES" />
    <meta name="author" content="Eval Ingenieria SAS">
    <meta content="EVAL Sistema de gestión académica" name="keywords">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="public/css/typica/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/typica/bootstrap-responsive.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/typica/typica-login.css">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements 
	[if lt IE 9]>
      <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <style type="text/css">       
        img {
            /*border-radius:      50% 0;  Borde redondeado */
            box-shadow:         0px 0px 15px #000; /* Sombra */
            padding:            5px; /* Espacio entre la imagen y el borde */
            background:         #EEEEEE; /* Color de fondo que se ve entre el espacio */
/*            -moz-transition:    all 1s;
            -webkit-transition: all 1s;
            -o-transition:      all 1s;*/
        }
        img:hover {
            border-radius:      0; /* Con esto quitamos el borde redondeado */
/*            -moz-transition:    all 1s;
            -webkit-transition: all 1s;
            -o-transition:      all 1s;*/
            cursor:             pointer;
        }
    </style>

    <!-- Le favicon -->
    <link rel="shortcut icon" href="public/img/eval.ico" type="image/x-icon" /> 
    <link rel="apple-touch-icon" sizes="152x152" href="public/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="public/img/favicons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="public/img/favicons/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="public/img/favicons/manifest.json">
    <link rel="mask-icon" href="public/img/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="theme-color" content="#ffffff">
    <title>Sievas</title>
</head>
  <body>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="https://www.certification-grana.org"><img src="public/img/logo2.png" alt="Grana logo"></a>
        </div>
      </div>
    </div>

    <div class="container">
        <div id="login-wraper">
            <form class="form login-form" name="form1" method="POST">
                <div style="color:#00F; font-size:12px; text-align:center;" id="mensaje_login"><?php 
                    /*if($_GET['failed'] == true) echo "Usuario o contrase&ntilde;a incorrectas.";
                    if($_GET['doLogout'] == true) echo "Gracias por usar el sistema";
                    if($_GET['expired'] == true) echo "Su sesion ha expirado. Por favor ingrese nuevamente";
                    if($_GET['unautorized'] == true) echo "No tiene privilegios para acceder a la pagina";
                    if($_GET['nocreado'] == true) echo "Su cuenta no ha sido activada en EVAL";
                    if($_GET['errordb'] == true) echo "Error en la conexión a la base de datos";*/ ?>
                </div>
                <legend><span class="blue">SIEVAS</span></legend>            
                <div class="body">
                    <label>Usuario</label>
                    <input type="text" id="username" name="username" size="30" required>                    
                    <label>Contrase&ntilde;a</label>
                    <input type="password" id="passwd" name="passwd" size="30" maxlength="30" required>
                </div>
            
                <div class="footer">
                    <label class="checkbox inline">
                        <input type="checkbox" id="inlineCheckbox1" value="option1"> Recu&eacute;rdame
                    </label>                        
                    <button type="submit" name="submit" class="btn btn-success">Ingresar</button>
                </div>
            </form>
        </div>
    </div>
    
    <footer class="white navbar-fixed-bottom">
      No tienes una cuenta? <a href="index.php?mod=sievas&controlador=usuarios&accion=registro" class="btn btn-black">Regístrate</a>
       <?php //include_once('help/footer.php');?>
    </footer>
    <div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 684px; width: 1452px; z-index: -999999; position: fixed;">
        <img src="public/img/bg2.png" style="position: absolute; margin: 0px; padding: 0px; border: none; width: 1452px; height: 816.3513909224012px; max-width: none; z-index: -999999; left: 0px; top: -66.17569546120058px;" class="deleteable">
        <img src="public/img/bg1.png" style="position: absolute; margin: 0px; padding: 0px; border: none; width: 1452px; height: 816.3513909224012px; max-width: none; z-index: -999999; left: 0px; top: -66.17569546120058px; opacity: 0.3316166986618059;">
    </div>
</body>
</html>