<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css">	
    <link rel="stylesheet" href="css/dataTables.bootstrap.css">	
    
    <link rel="shortcut icon" href="img/eval.ico" type="image/x-icon" /> 
    <title>Eval</title>
</head>

<body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span>
                <span class="icon-bar"></span><span class="icon-bar"></span>
            </button>
          
            <a class="navbar-brand" href="#"><img src="img/logo.png"></a>
        </div>
        
        <!-- MENU -->  
        <div id="navigation" class="navbar-collapse collapse"></div>             
        
    </div>      
    </div>
    	
    <div class="container">
        <div class="row">
            <h4><button class="btn btn-default btn-sm" id="add-generic">
            	<i class="glyphicon glyphicon-plus"></i></button> Usurios</h4>              

            <br/>
            <table width="100%" border="0" cellspacing="0" cellpadding="1" id="data_tabla" class="table table-striped table-bordered" >
                <thead>
                    <tr>
                        <td width="5%">Usuario</td>
                        <td width="10%">Rol</td>
                        <td width="15%">Sede</td>
                        <td width="10%">Activo</td>
                        <td width="10%">&nbsp;</td>
                    </tr>
                </thead>
                <tfoot>
                    <tr style="background:#CCC;">
                        <td width="5%">Usuario</td>
                        <td width="10%">Rol</td>
                        <td width="15%">Sede</td>
                        <td width="10%">Activo</td>
                        <td width="10%">&nbsp;</td>
                    </tr>
                </tfoot>
                <tbody>
                </tbody>
            </table>
            
     </div>
	<!-- FOOTER    --- FINAL  -->

	<footer class="white navbar-fixed-bottom">
       <?php include_once('help/footer.php'); ?>
    </footer>
</div>


 <!-- Latest compiled and minified JavaScript -->
 <script src="js/jquery.js"></script>
 <script src="js/bootstrap.min.js"></script> 
 <script src="js/bootbox.min.js"></script>

 <script src="js/jquery.dataTables.min.js"></script>
 <script src="js/jquery.dataTables.reloadAjax.js"></script>
 <script src="js/dataTables.bootstrap.js"></script>
 <script src="jquery/jquery.dataTables.editable.js"></script>
 <script src="jquery/jquery.jeditable.mini.js"></script>
 
 <script src="jquery/jquery.validate.js"></script>
 <script src="scripts/select.js"></script>
 <script src="scripts/checkbox.js"></script>
 <script src="scripts/usuarios.js"></script>
 
</body>
</html>