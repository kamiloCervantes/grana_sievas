
<h3 style="color:#F36" class="notranslate"></h3><br />
<div class="row">
    <div class="col-sm-4">
        <img src="public/img/modulos/sievas.png" class="img-responsive">
    </div>
    <div class="col-sm-8">
        <h5 style="color: #666666"><?php  echo $t->__('Bienvenid@', Auth::info_usuario('idioma')); ?>,</h5>
        <h4 style="color:#0033CC"><a href="index.php?mod=auth&controlador=usuarios&accion=actualizar">
            <img src="public/img/iconos/origami/user.png" width="20" height="20"></a> <?= $nombre_usuario; ?> </h4>
        <h4 style="color:#0033CC"><a href="index.php?mod=auth&controlador=usuarios&accion=actualizar">
            <img src="public/img/iconos/origami/identificar.png" width="20" height="20"></a><?php echo $t->__('Rol', Auth::info_usuario('idioma')); ?>: <?= $nombre_rol;?> <?php echo Auth::info_usuario('comite_centro') == 1 ? 'DE CENTRO': '' ?></h4>
        <?php if(isset($evaluado['evaluado'])){ ?>
        
        <h4 style="color:#0033CC"><a href="#">
            <i class="glyphicon glyphicon-screenshot"></i></a> <?php echo $t->__($tipo_evaluado, Auth::info_usuario('idioma')); ?>: <?php echo $evaluado['evaluado']; ?> 
            <a href="index.php?mod=auth&controlador=usuarios&accion=locacion" class="btn btn-default btn-xs"><?php echo $t->__('Cambiar', Auth::info_usuario('idioma')); ?> </a>
            
        </h4>
        <?php } ?>
        <?php if(Auth::info_usuario('ev_red') == 1){ ?>
        <h4 style="color:#0033CC"><img src="public/img/iconos/origami/identificar.png" width="20" height="20"> Evaluación en red</h4>
         <?php } ?>
        <br/>
        <p style="margin-right:30px; text-align:justify; font-size:14px"><?php echo $t->__('SIEVAS está diseñado para fomentar en la Instituciones Educativas el manejo y control de la gestión toda la información académica que tributa en calidad de la formación', Auth::info_usuario('idioma')); ?>.<br/><br/></p>
    </div>  
</div>