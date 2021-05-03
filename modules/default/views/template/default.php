<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="public/css/bootstrap.css">	
    <link rel="stylesheet" href="public/css/dataTables.bootstrap.css">
    <link rel="stylesheet" href="public/css/autocomplete.css">
    <link rel="stylesheet" href="public/js/jsTree/themes/default/style.min.css">
    <link rel="stylesheet" href="public/css/datepicker.css">
    <?php  echo View::print_css(); ?>
    <link rel="stylesheet" href="public/css/label.error.css">
    <link rel="shortcut icon" href="public/img/eval.ico" type="image/x-icon" /> 
    <title>Eval</title>
</head>
<body class="fuelux">
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span>
                    <span class="icon-bar"></span><span class="icon-bar"></span>
                </button>

                <a class="navbar-brand" href="#"><img src="public/img/logo.png"></a>
            </div>        
            
            <!-- MENU -->  
            <div id="navigation" class="navbar-collapse collapse"></div>                     
        </div>      
    </div>    	
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-sm-3"></div>
            
            <div class="col-lg-9 col-sm-9">
                <ol class="breadcrumb">
                    <li>
                        <a href="#" onclick="history.back(-1)">
                          <img src="public/img/iconos/origami/btn-atras.png" width="20" height="20" title="<?php echo $t->__('Atrás', Auth::info_usuario('idioma')); ?>">
                        </a>
                    </li>
                    <li>
                        <a href="index.php?controlador=index_sieva&accion=index">
                            <img src="public/img/iconos/origami/inicio.png" width="20" height="20" title="<?php echo $t->__('Inicio', Auth::info_usuario('idioma')); ?>" >
                        </a>
                    </li>                    
                 </ol>
               <?php echo View::content() ?>
            </div>
     </div>	
    <!-- FOOTER    --- FINAL  -->
    <footer class="white navbar-fixed-bottom">
       <?php //include_once('help/footer.php'); ?>
    </footer>

</div>
 <!-- Latest compiled and minified JavaScript -->
 <script src="public/js/jquery.js"></script>
 <script src="public/js/bootstrap.min.js"></script> 
 <script src="public/js/bootbox.min.js"></script>
 
 <script src="public/js/jquery.dataTables.min.js"></script>
 <script src="public/js/jquery.dataTables.reloadAjax.js"></script>
 <script src="public/js/dataTables.bootstrap.js"></script>
 <script src="public/js/jquery.dataTables.editable.js"></script>
 <script src="public/js/jquery.jeditable.mini.js"></script>
 <script src="public/css/dataTables.bootstrap.css"></script>
 
 <script src="public/js/bootstrap-datepicker.js"></script> 
 <script src="public/js/jquery.autocomplete.min.js"></script>
 <script src="public/js/utils.js"></script>

 <script src="public/js/jsTree/jstree.min.js"></script>
 <script src="public/js/jsTree/themes/default/style.min.css"></script>

 <script src="public/js/jquery.validate.js"></script>
 <script src="public/js/select.js"></script>
 <script src="public/js/checkbox.js"></script>
<!-- <script src="public/js/menu.js"></script>-->
 
 <script>
    var tipo_locacion=<?php echo Auth::info_usuario('tipo_locacion');?>;
    var cod_locacion=<?php echo Auth::info_usuario('cod_locacion');?>;
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
 
</body>
</html>