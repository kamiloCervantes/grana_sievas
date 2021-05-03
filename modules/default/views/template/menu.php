<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="public/css/bootstrap.css">	
    <link rel="stylesheet" href="public/css/style.css">	
    <link rel="stylesheet" href="public/css/dataTables.bootstrap.css">	
    <link rel="shortcut icon" href="public/img/eval.ico" type="image/x-icon" /> 
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
              
                <a class="navbar-brand" href="index.php?mod=auth&controlador=usuarios&accion=locacion">
                	<img src="public/img/logo.png"></a>
            </div>            
            <!-- MENU -->  
            <div id="navigation" class="navbar-collapse collapse"></div>             
        </div>      
    </div>    	
    <?php echo View::content()?>
 <script src="js/jquery.js"></script>
</body>
</html>