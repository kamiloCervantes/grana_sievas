<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="EVAL AUTOEVALUACION" />
    <meta name="keywords" content="EVAL, AUTOEVALUACION, PROGRAMAS, IES" />
    <meta name="author" content="Eval Ingenieria">
    <meta content="EVAL Sistema de informacion para la autoevaluación de programas academicos e institucional" name="keywords">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="css/bootstrap.css">
    <link href="css/typica/bootstrap-responsive.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/typica/typica-login.css">
    <link rel="stylesheet" href="css/autocomplete.css">    
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le favicon -->
    <link rel="shortcut icon" href="img/eval.ico" type="image/x-icon" /> 
    <title>Eval</title>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="index.php"><img src="img/eval.png" alt="Eval logo"></a>
        </div>
      </div>
    </div>

    <div class="container">
        <div class="row">

        	<div class="span6">
        		<div class="register-info-wraper">
        			<div id="register-info">
        			
        				<h1>Eval Acad&eacute;mico</h1>
        			
        				<ul dir="rtl">
                            <li>Acad&eacute;mico</li>
        					<li>Caracterizaci&oacute;n social</li>
        					<li>Civismo y Sexualidad</li>                            
                            <li>Gestión académica</li>
                            <li>Talento humano</li>
        				</ul>
        				
        			</div>
        		</div>
        	</div>

        	<div class="span6">
        		<div id="register-wraper">
        		    <form id="register-form" class="form" method="POST">
                        <div style="color:#00F; font-size:12px; text-align:center;" id="mensaje_reg">
                        </div>
        		        <legend>Registro&nbsp;&nbsp;<span class="blue">Eval</span></legend>
        		    
        		        <div class="body">
        		        	<!-- first name -->
                                        <div class="form-group">
                                            <label for="nombres_tmp">Persona</label>
                                            <input id="nombres_tmp" class="form-control" type="text">
                                            <input type="hidden" id="cod_persona" name="cod_persona" value="">
                                            <input type="hidden" id="nombres" name="nombres" value="">
                                        </div>
        		        	<!-- username -->
                                        <div class="form-group">
                                            <label>Usuario</label>
                                            <input id="username" name="username" class="form-control" type="text" required>
                                        </div>
        		        	<!-- email -->
                                        <div class="form-group">
                                            <label>E-mail</label>
                                            <input id="email" name="email" class="form-control" type="email" required>
                                        </div>
        		        	<!-- password -->
                                        <div class="form-group">
                                            <label>Contrase&ntilde;a</label>
                                            <input id="passwd" name="passwd" class="form-control" type="password" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Repita la contrase&ntilde;a</label>
                                            <input id="passwd2" name="passwd2" class="form-control" type="password" required>
                                        </div>
<!--                                        <div class="form-group">
                                            <label>Rol</label>
                                            <input id="rol" class="form-control" type="text">
                                            <input id="cod_rol" name="cod_rol" class="form-control" type="text">
                                        </div>-->
        		        </div>
        		    
        		        <!--<div class="footer">
        		            <label class="checkbox inline">
        		                <input type="checkbox" id="chkterm" name="chkterm" value="option1"> He leido los 
                                <a href="#">terminos y condiciones.</a>
        		            </label>
                        </div>-->
                        <button id="submit-reg" name="submit-reg" type="submit" class="btn btn-success">Registrar</button>
                    </form>
                    
        		</div>
        	</div>

        </div>
    </div>

    <footer class="white navbar-fixed-bottom">
      Ya tienes una cuenta? <a href="index.php" class="btn btn-black">Inicia sesi&oacute;n</a>
        <?php include_once('help/footer.php');?>
    </footer>


    <!-- Le javascript
    ================================================== -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>    
    <script src="js/jquery.autocomplete.min.js"></script>
    <script src="js/bootbox.min.js"></script>
    <script src="jquery/jquery.validate.js"></script>
    <script src="scripts/usuarios/registrar.js"></script>

    <div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 684px; width: 1452px; z-index: -999999; position: fixed;">
        <img src="img/typica/bg2.png" style="position: absolute; margin: 0px; padding: 0px; border: none; width: 1452px; height: 816.3513909224012px; max-width: none; z-index: -999999; left: 0px; top: -66.17569546120058px;" class="deleteable">
        <img src="img/typica/bg1.png" style="position: absolute; margin: 0px; padding: 0px; border: none; width: 1452px; height: 816.3513909224012px; max-width: none; z-index: -999999; left: 0px; top: -66.17569546120058px; opacity: 0.3316166986618059;">
    </div>
</body>
</html>