<?php
$tablet_browser = 0;
        $mobile_browser = 0;
        $body_class = 'desktop';

        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $tablet_browser++;
            $body_class = "tablet";
        }

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobile_browser++;
            $body_class = "mobile";
        }

        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            $mobile_browser++;
            $body_class = "mobile";
        }

        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
            'newt','noki','palm','pana','pant','phil','play','port','prox',
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
            'wapr','webc','winw','winw','xda ','xda-');

        if (in_array($mobile_ua,$mobile_agents)) {
            $mobile_browser++;
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
            $mobile_browser++;
            //Check for tablets on opera mini alternative headers
            $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
              $tablet_browser++;
            }
        }
        if ($tablet_browser > 0) {
        // Si es tablet has lo que necesites
//           print 'es tablet';
        }
        else if ($mobile_browser > 0) {
        // Si es dispositivo mobil has lo que necesites
//           print 'es un mobil';
        }
        else {
        // Si es ordenador de escritorio has lo que necesites
//           print 'es un ordenador de escritorio';
        }  
?>
<!DOCTYPE html>
<html lang="es"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="SIEVAS CERTIFICATION GRANA" />
    <meta name="keywords" content="SIEVAS, EVAL, INSTITUCIONES EDUCATIVAS, CERTIFICACION, CALIDAD" />
    <meta name="author" content="Eval Ingenieria SAS">
    <meta content="SIEVAS Permanent Evaluation System" name="keywords">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="public/css/typica/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/typica/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="public/css/typica/typica-login.css" rel="stylesheet">
    <link href="public/js/select2/select2.css" rel="stylesheet" type="text/css" >
    <link href="public/js/select2/select2-bootstrap.css" rel="stylesheet" type="text/css" >
    <script src="public/js/jquery.js"></script>
    <script type="text/javascript" src="public/js/select2/select2.min.js" ></script>
    <script type="text/javascript" src="public/js/select2/select2_locale_es.js" ></script>   
    <?php if ($tablet_browser > 0 || $mobile_browser > 0) { ?>
    <script src="modules/sievas/scripts/acceso/main_mob.js"></script>
    <?php } else { ?>
    <script src="modules/sievas/scripts/acceso/main.js"></script>
    <?php }  ?>
    
    
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements 
	[if lt IE 9]>
      <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Le favicon -->
    <link rel="shortcut icon" href="public/img/eval.ico" type="image/x-icon" /> 
    <title>Sievas</title>
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
        .location-mob{
            background: #fff;
            padding: 5px;
            border-radius: 4px;
        }
        
        select.mobile{
            width: 100%;
        }
    </style>
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
<?php if ($tablet_browser > 0 || $mobile_browser > 0) { ?>
      <div class="container">
          <div class="row">
              <div class="col-sm-12">
                  <br/><br/>
                  <div class="location-mob">
                      <legend><span class="blue">Datos de acceso</span><a href="#" class="btn btn-default" id="continuar">Continuar</a></legend> 
                  <form id="formLoginData">     
                    <table style="width:100%">
                        <tr>
                            <td class="mobile"><div class="form-group">
                                <label class="control-label">Rol de usuario</label>
                                <select class="form-control mobile" style="width:100%" name="rol" id="rol">
                                    <option>Seleccione...</option>
                                </select>
                            </div></td>                            
                        </tr>
                        <tr>
                            <td class="mobile"><br><div class="form-group">
                                <label class="control-label">País</label>
                                <select class="form-control mobile" name="pais" id="pais">
                                    <option>Seleccione...</option>
                                </select>
                            </div></td>
                        </tr>
                        <tr>
                            <td class="mobile"><br><div class="form-group">
                                <label class="control-label">Tipo de evaluado</label>
                                <select class="form-control mobile" name="tipo_evaluado" id="tipo_evaluado">
                                    <option>Seleccione...</option>
                                </select>
                            </div></td>
                        </tr>
                        <tr>
                            <td class="mobile"><br><div class="form-group">
                                <label class="control-label">Evaluado</label>
                                <select class="form-control mobile" name="evaluado" id="evaluado" >
                                    <option>Seleccione...</option>
                                </select>
                            </div></td>
                        </tr>
                        <tr>
                            <td class="mobile"><br><div class="form-group">
                                <label class="control-label">Evaluación</label>
                                <select class="form-control mobile" name="evaluacion" id="evaluacion">
                                    <option>Seleccione...</option>
                                </select>
                            </div></td>
                        </tr>   
                        <tr>
<!--                            <td colspan="2" style="text-align: center"><div class="form-group">
                                    <br><a href="#" class="btn btn-default" id="continuar">Continuar</a>
                            </div></td>                            -->
                        </tr>
                    </table>
                </form>
              </div>
              </div>
          </div>
      </div>
<?php } else { ?>

    <div class="container">
        <div id="login-wraper" style="overflow:auto;width:600px;left:45%">
            <legend><span class="blue">Datos de acceso</span></legend>            
            <div class="body">
                
                <form id="formLoginData">     
                    <table style="width:100%">
                        <tr>
                            <td colspan="2"><div class="form-group">
                                <label class="control-label">Rol de usuario</label>
                                <select class="form-control" style="width:520px" name="rol" id="rol">
                                    <option>Seleccione...</option>
                                </select>
                            </div></td>                            
                        </tr>
                        <tr>
                            <td><br><div class="form-group">
                                <label class="control-label">País</label>
                                <select class="form-control" name="pais" id="pais">
                                    <option>Seleccione...</option>
                                </select>
                            </div></td>
                            <td><br><div class="form-group">
                                <label class="control-label">Tipo de evaluado</label>
                                <select class="form-control" name="tipo_evaluado" id="tipo_evaluado">
                                    <option>Seleccione...</option>
                                </select>
                            </div></td>
                        </tr>
                        <tr>
                            <td><br><div class="form-group">
                                <label class="control-label">Evaluado</label>
                                <select class="form-control" name="evaluado" id="evaluado" >
                                    <option>Seleccione...</option>
                                </select>
                            </div></td>
                            <td><br><div class="form-group">
                                <label class="control-label">Evaluación</label>
                                <select class="form-control" name="evaluacion" id="evaluacion">
                                    <option>Seleccione...</option>
                                </select>
                            </div></td>
                        </tr>   
                        <tr>
                            <td colspan="2" style="text-align: center"><div class="form-group">
                                    <br><a href="#" class="btn btn-default" id="continuar">Continuar</a>
                            </div></td>                            
                        </tr>
                    </table>
                </form>
        
            </div>
        </div>
    </div>
    <?php }  ?>
      <footer class="white navbar-fixed-bottom">
          <div class="footer"><p style="font-style:normal; font-size:11px; font-weight:100;">
            <strong>&copy; <?php date_default_timezone_set('America/Bogota'); echo date('Y');?></strong> :: Sistema de Información para la gestión de los procesos académicos |
            <a target="_blank" href="https://www.evalingenieria.com.co/">EVAL INGENIERÍA S.A.S.</a> .::. eval@evalingenieria.com.co :: <strong>COLOMBIA</strong></p>
          </div>
      </footer>
    
    
    <div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; 
        padding: 0px; height: 684px; width: 1452px; z-index: -999999; position: fixed;">
        <img src="public/img/bg2.png" style="position: absolute; margin: 0px; padding: 0px; 
        	border: none; width: 1452px; height: 816.3513909224012px; max-width: none; 
            z-index: -999999; left: 0px; top: -66.17569546120058px;" 
        	class="deleteable">
        <img src="public/img/bg1.png" style="position: absolute; margin: 0px; padding: 0px; 
        	border: none; width: 1452px; height: 816.3513909224012px; max-width: none; 
            z-index: -999999; left: 0px; top: -66.17569546120058px; opacity: 0.3316166986618059;">
    </div>
    
</body>

</html>