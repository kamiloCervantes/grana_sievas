<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >

<head>

<link rel="shortcut icon" href="images/grana.ico" type="image/x-icon" /> 
<title>ISTEC - GRANA</title>


<link rel="stylesheet" href="./css/style.css" type="text/css" media="screen,projection" />

<!-- Javascripts -->

<script src="./js/jquery-1.6.4.min.js" type="text/javascript"></script>
<script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script src="./js/miscellaneous.js" type="text/javascript"></script>

<script type="text/javascript">
	$(document).ready(function(){
		
		init_google_map();
		jquery_miscellaneous();
	});
</script>
	
<script type='text/javascript' src='./js/cufon-yui.js'></script>
<script type="text/javascript" src="./js/Futura_Bk_BT_400.font.js"></script>

<script type="text/javascript" src="./js/jquery.js"></script>
<script type="text/javascript" src="./js/jquery.form.js"></script>

<meta charset="UTF-8"></head>

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

        <!-- end header -->
    <div id="content">
            <div class="wrapper">
		
          		
<div id="maincolbck">
	<div id="maincolumn">
	
	<div id="block_title">
    	<h3 class="dotted_line" style="font-size: 18px; color: #888; padding-left:32px;">Autenticación</h3>
    </div>

<div id="commentform">
	<p class="error wrong_name">nombre incorrecto</p>
	<p class="error wrong_email">email incorrecto</p>
	<p class="error wrong_message">Escribe tu mensaje</p>

	<form id="contact_us" name="contact_us">
    
    <table width="100%">
        <tr>
            <td height="38"><label for="nombre">Usuario *</label></td>
            <td colspan="3"><input type="text" name="nombre" id="nombre" size="50" required/></td>
        </tr>
        <tr>
            <td height="38"><label for="nombre">Contraseña</label></td>
            <td colspan="3"><input type="text" name="nombre" id="nombre" size="50" required/></td>
        </tr>
        
    </table>

<br/>
       
    <p><input type="submit"  id="enviar" name="enviar" class="button orange" value="Enviar Mensaje" /></p>
	
    </form>
	<p id="success"></p>
</div>

	</div>
		</div>
		
		<div id="sidebar">
			<div class="wrapper">
								
	
<div class="block_right1">
<div id="block_title">
<h3 class="dotted_line" style="font-size: 18px; color: #888;">Historial</h3>
</div>

<div class="navigation1">

            <ul>
                
                <p>Acceso a toda la información relacionada con una institución, programa o profesor que se ha registrado en nuestro sistema SIEVAS.</p>
			</ul>
        </div>

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

<script type="text/javascript" src="./js/init_form.js"></script>

<script type="text/javascript" src="./js/custom.js"></script>
<script type="text/javascript">Cufon.now();</script>

</body>
</html>