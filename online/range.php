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

<div class="wrapper border" style="height: 320px;">
    <div id="map-canvas"></div>
</div>
<!-- end header -->

<div id="content">
    <div class="wrapper">

    <div id="maincolbck">
        <div id="maincolumn">
    
    <div class="navigation1">
        <div id="block_title">
            <h3 class="dotted_line" style="font-size: 18px; color: #888; padding-left:32px;">CERTIFICACIONES</h3>
        </div>
        <ul>
            <li><a href="" title="">Ingeniería Electrónica y  Telecomunicaciones :: UTPL, Ecuador, ESPE. Ecuador</a></li>
            <li><a href="" title="">Ingeniería en Electrónica y Comunicaciones :: Universidad de Cundinamarca, Colombia</a></li>
            <li><a href="" title="">Licenciatura en Ciencias de la Comunicación :: UANL, México</a></li>
            <li><a href="" title="">Enfermería :: UANL, México</a></li>
            <li><a href="" title="">Trabajo Social y Desarrollo Humano :: UANL, México</a></li>
            <li><a href="" title="">Ingeniería Informática :: UNITEC, Honduras</a></li>    
        </ul>
    </div>

	</div>
	</div>

    <div id="sidebar">
        <div class="wrapper">
								
	
        <div class="block_right1">
            <div id="block_title">
                <h3 class="dotted_line" style="font-size: 18px; color: #888;">Presencia</h3>
            </div>
    
            <div class="navigation1">
                <ul>
                    <li><a href="" title="">Colombia</a></li>
                    <li><a href="" title="">Ecuador</a></li>
                    <li><a href="" title="">Honduras</a></li>
                    <li><a href="" title="">México</a></li>
                    <li><a href="" title="">El Salvador</a></li>
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