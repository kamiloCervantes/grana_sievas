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

		<li><h3>Servicios</h3></li>
		<li class="no-border"><span class="description">Certificación Internacional, Capacitación y Actualización</span></li>
</ul>

</div>
	
        <!-- end header -->
    <div id="content">
            <div class="wrapper">
			
<div id="maincolbck" style="width: 100%;">
	<div id="maincolumn" style="width: 100%;">
	
    <div class="navigation1">
        <ul>
            <li><a href="#" title="">1.	 Certificación Internacional de programas educativos</a></li>
            <li><a href="#" title="">2.	 Certificación Internacional de profesores docentes</a></li>
            <li><a href="#" title="">3.	 Certificación Internacional a Instituciones de Educación Superior</a></li>
            <li><a href="#" title="">4.	 Certificación Internacional a Investigadores</a></li>
            <li><a href="#" title="">5.  Certificación a Instituciones o dependencias de Investigación, Desarrollo e Innovación (I+D+I)</a></li>
            <li><a href="#" title="">6.  Certificación de cursos especializados</a></li>
            <li><a href="#" title="">7.	 Certificación de distintas especialidades</a></li>
            <li><a href="#" title="">8.  Certificación, Capacitación y Actualización en Calidad a Profesores</a></li>
            <li><a href="#" title="">9.	 Certificación, Capacitación y Actualización en Calidad a Instituciones</a></li>
            <li><a href="#" title="">10. Certificación, Capacitación y Actualización en Internacionalización a Profesores</a></li>
        </ul>
    </div>

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