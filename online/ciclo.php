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
    	<h3 class="dotted_line" style="font-size: 18px; color: #888; padding-left:32px;">Ciclo de evaluación</h3>
    </div>

    	<img width="660" height="500" src="./images/ciclo.png" alt="Ciclo" title="Ciclo" />
        
        
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