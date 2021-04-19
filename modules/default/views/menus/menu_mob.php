<?php 
//*******	SE CARGA EL MENÚ DE ACUERDO AL ROL	 **********//

session_start();

//cargar archivos de la base de datos
if(file_exists('../../../../core/db/config.php') and file_exists('../../../../core/db/eval.php')){
    require_once '../../../../core/db/config.php';
    require_once '../../../../core/db/eval.php';
}

$db = BD::$db;

//cargar clase Session
if(file_exists('../../../../core/Session.php')){
    require_once '../../../../core/Session.php';
}
?>

<ul class="nav navbar-nav navbar-right">
    <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff"><?php echo $t->__('Grafindicámetros', Auth::info_usuario('idioma')); ?> <b class="caret"></b></a>
        <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
            <li><a href="index.php?mod=sievas&controlador=avances&accion=calificaciones_evaluacion_graficas">
                <i class="glyphicon glyphicon-resize-small"></i> <?php echo $t->__('Rubros', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=avances&accion=calificaciones_evaluacion_graficas_consolidados">
            <i class="glyphicon glyphicon-resize-full"></i> <?php echo $t->__('Consolidados', Auth::info_usuario('idioma')); ?></a></li>
        </ul>
    </li>
    <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff"><?php echo $t->__('Reportes gráficos', Auth::info_usuario('idioma')); ?> <b class="caret"></b></a>
        <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
            <li><a href="index.php?mod=sievas&controlador=avances&accion=tablas_estadisticas_graficas">
                <i class="glyphicon glyphicon-resize-small"></i> <?php echo $t->__('Tablas estadísticas', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=avances&accion=reporte_linea_calificaciones">
            <i class="glyphicon glyphicon-resize-full"></i> <?php echo $t->__('Comparativa ítems', Auth::info_usuario('idioma')); ?></a></li>
        </ul>
    </li>
    <!--    3. REPORTES    -->   
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff"><?php echo $t->__('Avances', Auth::info_usuario('idioma')); ?> <b class="caret"></b></a>
       <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
           <?php if(Auth::info_usuario('ev_cna') == 0) { ?>
           <?php if(Auth::info_usuario('ev_anterior') > 0) { ?>
           <?php if(Auth::info_usuario('ev_red') == 0) { ?>
        <li><a href="index.php?mod=sievas&controlador=avances&accion=avances_reevaluacion">
        <?php } else { ?>
        <li><a href="index.php?mod=sievas&controlador=avances&accion=avances_evaluacion_r">
        <?php } ?>
           
            <?php } else { ?>
            <li><a href="index.php?mod=sievas&controlador=avances&accion=avances_evaluacion">
            <?php }?>
                <i class="glyphicon glyphicon-dashboard"></i> <?php echo $t->__('Avances', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=avances&accion=resultados_evaluacion">
                <i class="glyphicon glyphicon-list"></i> <?php echo $t->__('Evaluación', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=evaluar&accion=tablas_estadisticas">
                <i class="glyphicon glyphicon-list"></i> <?php echo $t->__('Tablas estadísticas', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=avances&accion=calificaciones_evaluacion">
                <i class="glyphicon glyphicon-cog"></i> <?php echo $t->__('Resultados', Auth::info_usuario('idioma')); ?></a></li> 
                <?php if(Auth::info_usuario('ev_anterior') > 0) { ?>
                <li><a href="index.php?mod=sievas&controlador=avances&accion=evaluaciones_anteriores">
                <i class="glyphicon glyphicon-cog"></i> <?php echo $t->__('Evaluaciones anteriores', Auth::info_usuario('idioma')); ?></a></li> 
                <?php } ?>
<!--            <li><a href="index.php?mod=sievas&controlador=avances&accion=calificaciones_evaluacion_graficas">
                <i class="glyphicon glyphicon-info-sign"></i> Gráficos</a></li>  -->
           <?php if ($tablet_browser > 0 || $mobile_browser > 0) { ?>
                <li class="divider"></li>
                <li class="nav-header">Gráficos</li>  
                <li><a href="index.php?mod=sievas&controlador=avances&accion=calificaciones_evaluacion_graficas"><?php echo $t->__('Rubros', Auth::info_usuario('idioma')); ?></a></li>                               
                <li><a href="index.php?mod=sievas&controlador=avances&accion=calificaciones_evaluacion_graficas_consolidados"><?php echo $t->__('Consolidados', Auth::info_usuario('idioma')); ?></a></li>                               
                <li><a href="index.php?mod=sievas&controlador=avances&accion=select_graficas_estadisticas"><?php echo $t->__('Tablas estadisticas', Auth::info_usuario('idioma')); ?></a></li>                               
                <li><a href="index.php?mod=sievas&controlador=avances&accion=reporte_linea_calificaciones"><?php echo $t->__('Comparativa Ítems', Auth::info_usuario('idioma')); ?></a></li>     
           <?php } else { ?>
           
            <li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-info-sign"></i> <?php echo $t->__('Gráficos', Auth::info_usuario('idioma')); ?></a>
                            <ul class="dropdown-menu">
                                <li><a href="index.php?mod=sievas&controlador=avances&accion=calificaciones_evaluacion_graficas"><?php echo $t->__('Rubros', Auth::info_usuario('idioma')); ?></a></li>                               
                                <li><a href="index.php?mod=sievas&controlador=avances&accion=calificaciones_evaluacion_graficas_consolidados"><?php echo $t->__('Consolidados', Auth::info_usuario('idioma')); ?></a></li>                               
                                <li><a href="index.php?mod=sievas&controlador=avances&accion=select_graficas_estadisticas"><?php echo $t->__('Tablas estadisticas', Auth::info_usuario('idioma')); ?></a></li>                               
                                <li><a href="index.php?mod=sievas&controlador=avances&accion=reporte_linea_calificaciones"><?php echo $t->__('Comparativa Ítems', Auth::info_usuario('idioma')); ?></a></li>                               
                            </ul>
                        </li>
             <?php }?>
            <?php if(Auth::info_usuario('ev_red') == 1){ ?>
                        <li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-eye-open"></i> <?php echo $t->__('Ev. Red', Auth::info_usuario('idioma')); ?></a>
                            <ul class="dropdown-menu">
                                <li><a href="index.php?mod=sievas&controlador=avances&accion=resultados_evaluacion_red"><?php echo $t->__('Resultados', Auth::info_usuario('idioma')); ?></a></li>                                                          
                            </ul>
                        </li>
            <?php } ?>
            
            <li class="divider"></li>
            <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=ver_predictamen" target="_blank">
                <i class="glyphicon glyphicon-file"></i> <?php echo $t->__('Predictamen', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=ver_dictamen" target="_blank">
                <i class="glyphicon glyphicon-certificate"></i> <?php echo $t->__('Dictamen', Auth::info_usuario('idioma')); ?></a></li> 
            <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=ver_historico" target="_blank">
                <i class="glyphicon glyphicon-floppy-disk"></i> <?php echo $t->__('Histórico', Auth::info_usuario('idioma')); ?></a></li> 
        <?php } else {?>
                <li><a href="index.php?mod=sievas&controlador=avances&accion=avances_evaluacion">
                <i class="glyphicon glyphicon-dashboard"></i> <?php echo $t->__('Avances', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=avances&accion=resultados_evaluacion">
                <i class="glyphicon glyphicon-list"></i> <?php echo $t->__('Evaluación', Auth::info_usuario('idioma')); ?></a></li>
        <?php } ?>
       </ul>
   </li>
    <li><a href='index.php?mod=auth&controlador=usuarios&accion=logout' style="color:#fff;">.: <?php echo $t->__('Salir', Auth::info_usuario('idioma')); ?> :.</a></li>
</ul>