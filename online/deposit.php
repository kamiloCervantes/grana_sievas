<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >

<head>

<link rel="stylesheet" href="./css/style.css" type="text/css" media="screen,projection" />

<link rel="shortcut icon" href="images/grana.ico" type="image/x-icon" /> 
<title>ISTEC - GRANA</title>

<script type="text/javascript" src="./js/jquery.js"></script> 
<script type='text/javascript' src='./js/cufon-yui.js'></script>
<script type="text/javascript" src="./js/Futura_Bk_BT_400.font.js"></script>

<meta charset="UTF-8">
</head>

<body>

<div id="container">
	<div class="main">
		<div class="content-border">

<div id="header">
    <div class="main-2">
        <div class="logoheader"><a href="./index.php" id="logo" title="GRANA"></a></div>
        <div id="navigation">
        	<ul id="dropmenu" class="menu">
                <li class="current-menu-item"><a href="./index.php">inicio</a></li>
            </ul>
        </div>
    </div>
</div>
	
        <!-- end header -->
    <div id="content">
            <div class="wrapper">
			
<div id="maincolbck">
	<div id="maincolumn">
	
	<div id="block_title">
    	<h3 class="dotted_line" style="font-size: 18px; color: #888; padding-left:32px;">Flujo de trabajo</h3>
    </div>
	
    <p>1. <a href="./images/formatos/Formato 2 - Solicitud de evaluación.pdf" target="_blank">Formato ::</a>
       Solicitud de evaluación</p>
      
    <p>2. <a href="./images/formatos/Formato 3 - Contrato del servicio.docx">Formato :: </a>
       Contrato del servicio</p>
    
    <p>3. <a href="./images/formatos/Formato 4 - Comite Evaluadores Internos.doc">Formato :: </a>
       CEI - Comité Evaluador Interno</p> 
       
    <p>4. <a href="./images/formatos/Formato 5.1. Laminas de apoyo para taller.ppt">Diapositiva 1 :: </a>
       <a href="./images/formatos/Formato 5.2. Laminas de apoyo.ppt">Diapositiva 2 :: </a>
       <a href="./images/formatos/Formato 5.3. Laminas de apoyo.pptx">Diapositiva 3 :: </a>
       Capacitación CEI</p>
      
    <p>5. <a href="./images/formatos/Formato 5.4. Manual evaluador interno.pdf" target="_blank">Formato :: </a>
       Manual del Evaluador Interno</p>
    
    <p>6. <a href="./images/formatos/Formato 6 - Conclusión evaluación interna.docx">Formato :: </a>
       Conclusión evaluación interna  &nbsp;&nbsp;|&nbsp;&nbsp; Solicitud de Evaluación Externa</p> 
    
    <p>7. <a href="./images/formatos/Formato 7 - Invitación como EE.docx">Formato :: </a>
       Invitación a Evaluadores Externos</p> 
       
    <p>8. <a href="./images/formatos/Formato 8 - Evaluadores Externos.docx" target="_blank">Formato ::</a>
       CEE - Comité Evaluador Externo</p>
      
    <p>9. <a href="./images/formatos/Formato 9 - Evaluacion grupal.xlsx">Formato :: </a>
       Evaluación grupal</p>
    
    <p>10. <a href="./images/formatos/Formato 10 - Programa de Visita evaluacion grana.docx">Formato :: </a>
       Programa de Visita evaluacion GRANA</p> 
       
    <p>11. <a href="./images/formatos/grana.docx">Formato :: </a>
       GRANA</p> 
       
    <p>12. <a href="./images/formatos/grana.docx">Formato :: </a>
       GRANA</p> 
    
    <p>13. <a href="./images/formatos/grana.docx">Formato :: </a>
       GRANA</p> 
       
    <p>14. <a href="./images/formatos/grana.docx">Formato :: </a>
       GRANA</p> 
        
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
        url: "./menu/footer.html",
        type: 'post',
        success: function(str){
            jQuery("#copyright").html(str);
        }    
	});
    
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