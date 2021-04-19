<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >

<head>

<link rel="stylesheet" href="./css/style.css" type="text/css" media="screen,projection" />

<link rel="shortcut icon" href="images/grana.ico" type="image/x-icon" /> 
<title>ISTEC - GRANA</title>

<script type='text/javascript' src='./js/cufon-yui.js'></script>
<script type="text/javascript" src="./js/Futura_Bk_BT_400.font.js"></script>
<script type="text/javascript" src="./js/jquery.js"></script>

<meta charset="UTF-8">
</head>

<body>
<div id="container">
	<div class="main">
		<div class="content-border">

<div id="header">
    <div class="main-2">
        <div class="logoheader"><a href="./index.php" id="logo" title="GRANA"></a></div>
        <div id="navigation"></div>
    </div>
</div>

<div class="wrapper pages">

<ul id="filterNav" class="clearfix">

		<li><h3>Equipo</h3></li>
		<li class="no-border"><span class="description">Directivos</span></li>
</ul>

</div>
	
        <!-- end header -->
    <div id="content">
            <div class="wrapper">
			
<div id="maincolbck" style="width: 100%;">
	<div id="maincolumn" style="width: 100%;">
    
		<div class="one_non_half services">
            <img src="../public/img/iconos/origami/x-user.png" width="120" height="139" alt="FOTO" />	
            <span><h4>Dr. Donato Vallín</h4></span>
            <p>
               Director General de GRANA - ISTEC
			   <br/>Universidad de Guadalajara Centro Universitario de la Costa
               <br/>Jalisco, México 
               <br/>+52-317-3-5-23-08, +52-317-38-5-12-03
               <br/>dvallin@istec.org | dvallin@cucsur.udg.mx
               <br/>www.istec.org
            </p>						
        </div>

		<div class="one_non_half services" style="margin-top:50px">
            <img src="../public/img/iconos/origami/x-user.png" width="120" height="139" alt="FOTO" />	
            <span><h4>Juan Ricardo Gutiérrez Cardona</h4></span>
            <p>Junta directiva ISTEC - GRANA
			   <br/>Universidad de Guadalajara Centro Universitario de la Costa
               <br/>Jalisco, México
               <br/>jcardona@cucsur.udg.com
               <br/>
            </p>						
        </div>
        
        <div class="one_non_half services" style="margin-top:50px">
            <img src="../public/img/iconos/origami/x-user.png" width="120" height="139" alt="FOTO" />	
            <span><h4>Ramiro Jordán</h4></span>
            <p>Junta directiva ISTEC - GRANA
			   <br/>Iberoamerican Sciencie Tecnology Education Consortium
               <br/>Nuevo México, USA
               <br/>rjodan@istec.org
               <br/>
            </p>						
        </div>
  
        <div class="one_non_half services" style="margin-top:50px">
            <img src="./images/foto_dulce.jpg" width="120" height="139" alt="FOTO" />	
            <span><h4>Dulce Garcia</h4></span>
            <p>Junta directiva ISTEC - GRANA
               <br/>President of Iberoamerican Sciencie Tecnology Education Consortium
               <br/>Nuevo México, USA
               <br/>dgarcia@istec.org
               <br/><br/>
               <br/>
            </p>						
        </div> 
        

<div class="clear"></div>



</div>
	</div>
		</div>
			</div>

<div class="footer">
	<div id="copyright"></div>
</div>

		</div>
	</div>
</div>


<script type="text/javascript" charset="utf-8">

jQuery(document).ready( function () {
    jQuery.ajax({
        url: "./menu/menu.php",
        success: function(str){
			//$params = { 'actual' : 'index.php' };
            jQuery("#navigation").html(str);
        }
    });   

    jQuery.ajax({
        url: "./menu/footer.html",
        type: 'post',
        success: function(str){
            jQuery("#copyright").html(str);
        }    });
    
    /*jQuery.ajax({
        url: "./menu/menu2.html",
        type: 'post',
        success: function(str){
            jQuery("current-menu-item").html(str);
        }
    });*/
});
        
</script>

<script type="text/javascript" src="./js/custom.js"></script>
<script type="text/javascript">Cufon.now();</script>

</body>
</html>