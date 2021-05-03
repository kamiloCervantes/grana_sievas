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
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Le favicon -->
    <link rel="shortcut icon" href="public/img/eval.ico" type="image/x-icon" /> 
    <title>Sievas</title>
</head>

  <body>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
          </a>
        </div>
      </div>
    </div>

    <div class="container">
        <div id="login-wraper">
            <legend><span class="blue">Acceso</span></legend>            
            <div class="body">
                <?php foreach ($roles as $rol):?>
                    <div class="row">
                        <a href="index.php?mod=auth&controlador=usuarios&accion=cargar_permisos&cr=<?php echo $rol['id'];  ?>"><?php echo $rol['nombre'];  ?></a>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
    
    <footer class="white navbar-fixed-bottom">
       <div class="footer"><p style="font-style:normal; font-size:11px; font-weight:100;">
            <strong>&copy; <?php date_default_timezone_set('America/Bogota'); echo date('Y');?></strong> :: Sistema de Información para la gestión de los procesos académicos |
            <a target="_blank" href="http://www.evalingenieria.com.co/">EVAL INGENIERÍA S.A.S.</a> .::. eval@evalingenieria.com.co :: <strong>COLOMBIA</strong></p>
        </div>
    </footer>
   <div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 684px; width: 1452px; z-index: -999999; position: fixed;">
        <img src="public/img/bg2.png" style="position: absolute; margin: 0px; padding: 0px; border: none; width: 1452px; height: 816.3513909224012px; max-width: none; z-index: -999999; left: 0px; top: -66.17569546120058px;" class="deleteable">
        <img src="public/img/bg1.png" style="position: absolute; margin: 0px; padding: 0px; border: none; width: 1452px; height: 816.3513909224012px; max-width: none; z-index: -999999; left: 0px; top: -66.17569546120058px; opacity: 0.3316166986618059;">
    </div>
</body>
</html>