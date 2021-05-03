<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-translate-customization" content="c65841e6d03e8e8e-8e9f2074f7f2964d-g02aa234ba2dddd3b-1f">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <link rel="stylesheet" href="public/css/bootstrap.css">	
    <link rel="stylesheet" href="public/css/dataTables.bootstrap.css">
    <link rel="stylesheet" href="public/css/dropdown.submenu.css">
    <link rel="stylesheet" href="public/css/autocomplete.css">
    <link rel="stylesheet" href="public/js/jsTree/themes/default/style.min.css">
    <link rel="stylesheet" href="public/css/datepicker.css">
    <link rel="stylesheet" href="public/css/bs3.dropdown.multilevel.css">
    <link rel="stylesheet" href="public/css/sievas.css">
    <link rel="stylesheet" href="public/css/print.css" media="print">
    <?php echo View::print_css(); ?>
    <link rel="stylesheet" href="public/css/label.error.css">
    <link rel="shortcut icon" href="public/img/eval.ico" type="image/x-icon" /> 
    <title class="notranslate">Sievas</title>
    <style>
        #encabezado-print{
            display:none;
        }
        .goog-te-banner-frame.skiptranslate {display: none !important;} 

    </style>
</head>
<body style="background: url('public/img/crossword.png');" >
    <!-- navbar-fixed-top -->
    <?php if ($tablet_browser > 0 || $mobile_browser > 0) { ?>
    <div class="navbar navbar-inverse navbar-sievas navbar-fixed-top" role="navigation" style="border: 1px solid #800000">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span>
                    <span class="icon-bar"></span><span class="icon-bar"></span>
                </button>

                <a class="navbar-brand" href="#"><img src="public/img/logo.png"></a>
            </div>        
            
            <!-- MENU -->  
            <div id="navigation" class="navbar-collapse collapse">
                <?php include_once APP_PATH.'/modules/default/views/menus/menu_mob.php'; ?>
            </div>                     
        </div>      
    </div>    
    <?php } else { ?>
    <div class="navbar navbar-inverse navbar-sievas navbar-fixed-top" role="navigation" style="border: 1px solid #800000">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span>
                    <span class="icon-bar"></span><span class="icon-bar"></span>
                </button>

                <a class="navbar-brand" href="#"><img src="public/img/logo.png"></a>
            </div>        
            
            <!-- MENU -->  
            <div id="navigation" class="navbar-collapse collapse">
                <?php include_once APP_PATH.'/modules/default/views/menus/menu.php'; ?>
            </div>                     
        </div>      
    </div>    	
     <?php }  ?>
    <div class="container">
        <div class="row">
            <div id="encabezado-print" class="print">
                <table width="100%">
                    <tr>
                        <td width="25%"><img src="public/img/logos/sievas.png" class="print"></td>
                        <td width="50%" align="center"><p class="print">Titulo de prueba</p></td>
                        <td width="25%"><img src="public/img/logos/eval.png" class="print"></td>
                    </tr>
                </table>
            </div>
            <div id="titulo" class="col-lg-3 col-sm-3"></div>
            <?php if (!($tablet_browser > 0 || $mobile_browser > 0)) { ?>
            <div class="col-lg-9 col-sm-9 no-print">
                
                    <ol class="breadcrumb">
                        <li>
                            <a href="#" onclick="history.back(-1)">
                              <img src="public/img/iconos/origami/btn-atras.png" width="20" height="20" title="<?php echo $t->__('Atrás', Auth::info_usuario('idioma')); ?>">
                            </a>
                        </li>
                        <li>
                            <a href="index.php?mod=sievas&controlador=index_sieva&accion=index">
                            <img src="public/img/iconos/origami/inicio.png" width="20" height="20" title="<?php echo $t->__('Inicio', Auth::info_usuario('idioma')); ?>">
                            <?php echo $t->__('Inicio', Auth::info_usuario('idioma')); ?></a>
                        </li>                    
                     </ol>
                </div>
            <?php } ?>
                <div class="row">
                    <div class="col-sm-12">
                        <?php if (!($tablet_browser > 0 || $mobile_browser > 0)) { ?>
                        <div class="input-group col-sm-2 pull-right">
                            <input type="text" class="form-control" id="fecha_actual">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                          </div>
                        <?php } ?>
                        <?php echo View::content() ?>
                    </div>
                </div>
            </div>
     </div>
        
    <!-- FOOTER    --- FINAL  -->
    <footer class="white navbar-fixed-bottom">
<!--        <div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'es', includedLanguages: 'de,en,es,fr,it,pt,zh-CN', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, multilanguagePage: true}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>-->
       <?php //include_once('help/footer.php'); ?>
    </footer>

</div>
 <!-- Latest compiled and minified JavaScript -->
 <script src="public/js/jquery.js"></script>
 <script src="public/js/bootstrap.min.js"></script> 
 <script src="public/js/bootbox.min.js"></script>
 
 <script src="public/js/jqUI/jquery-ui-1.10.4.custom.min.js"></script>

 <script src="public/js/jquery.dataTables.min.js"></script>
 <script src="public/js/dataTables.bootstrap.js"></script>
 <script src="public/css/dataTables.bootstrap.css"></script> 
 <script src="public/js/jquery.dataTables.reloadAjax.js"></script>
 
 <script src="public/js/jquery.validate.js"></script>
 <script src="public/js/select.js"></script>
 <script src="public/js/checkbox.js"></script>
 <!--<script src="public/js/menu.js"></script>-->
 <script>
//    var tipo_locacion=<?php echo Auth::info_usuario('tipo_locacion'); ?>;
//    var cod_locacion=<?php echo Auth::info_usuario('cod_locacion'); ?>;
 </script>
 <?php  echo View::print_js(); ?>
 
 <script src="public/js/jsTree/jstree.min.js"></script> 
 <script>      
	$(function(){        
 		$('#arbol-menu').jstree().on('changed.jstree', function(e,data){            
	 		var target = data.instance.get_node(data.selected[0]).a_attr.href;            
			 window.location = target;         
		});     
 	});  
 </script>
 <script>
 var date = new Date();
 $('#fecha_actual').val(date.toLocaleString());
 setInterval(function(){
      var date = new Date();
      $('#fecha_actual').val(date.toLocaleString());
 },1000);
 </script>
 
</body>
</html>