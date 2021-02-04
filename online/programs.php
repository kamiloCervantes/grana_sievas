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

		<li><h3>Programas</h3></li>
		<li class="no-border"><span class="description"></span></li>
</ul>

</div>
	
        <!-- end header -->
    <div id="content">
            <div class="wrapper">
			
<div id="maincolbck" style="width: 100%;">
	<div id="maincolumn" style="width: 100%;">


    

<strong>Subprograma D-5</strong> 
<p>La dimensión internacional de la educación superior que está vinculada a globales de los 5 continentes y regiones entre las instituciones y / o programas educativos.</p> 

<strong>Subprograma PROFEESUP</strong> 
<p>Este es el módulo para la formación de evaluadores, cuya función es vincular la formación con expertos académicos sobre el tema de la evaluación, la acreditación,  la acreditación internacional.</p>

<strong>Subprograma SIEVAS</strong>
<p>Acreditación Sistema de Evaluación y seguimiento dedicada a la gestión, distribución, generación  y operación de los procesos de evaluación interna, externa, competencias  y especialidades.</p> 

<strong>Subprograma  I + D + I</strong>
<p>Investigación, Desarrollo e Innovación GRANA -ISTEC. Este subprograma es responsable sistemáticamente las propuestas para la actualización de los indicadores internacionales de la calidad de la educación superior del mundo de ancho. La información generada por diversos medios electrónicos o impresos, conferencias,  charlas, seminarios, cumbres y académicas y la global de la educación.</p>

<strong>Calidad Sub LibLink</strong>


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