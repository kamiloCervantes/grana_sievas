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
    
<?php
if (Session::get('rol') > 0){ ?>
<!--    ROL EXPERTO    --><?php
switch(Session::get('rol')){
    
default:
if (Session::get('rol') == 4){ ?>

	<li><a href="index.php?mod=sievas&controlador=observatorio" style="color: #fff"><?php echo $t->__('Observatorio', Auth::info_usuario('idioma')); ?></a></li>
    <!--<li><a href='index.php?mod=auth&controlador=usuarios&accion=logout' style="color:#fff;">Rubros</a></li>-->

<?php
} else { ?>

   <!--    TODOS LOS ROLES    -->
   
   <!--    1. EVALUACIÓN    -->
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff"><?php echo $t->__('Evaluación', Auth::info_usuario('idioma')); ?> <b class="caret"></b></a>
       <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu"><?php
            
if (Session::get('rol') == 1) {?>       
                <li><a href="index.php?mod=sievas&controlador=evaluar&accion=tablero_control">
                <i class="glyphicon glyphicon-dashboard"></i> <?php echo $t->__('Tablero de control', Auth::info_usuario('idioma')); ?></a></li>
                <?php switch(Auth::info_usuario('ev_version')){ 
                    case 1: ?>
                <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar&cod_momento=1">
                <?php
                        break;
                    case 2: ?>
                <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar_v2&cod_momento=1">
                <?php
                        break;
                    default: ?>
                <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar_v2&cod_momento=1">
                <?php
                        break;
                } 
                ?>
			
                <i class="glyphicon glyphicon-resize-small"></i> <?php echo $t->__('Evaluación Interna', Auth::info_usuario('idioma')); ?></a></li>
                <?php switch(Auth::info_usuario('ev_version')){ 
                    case 1: ?>
                <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar&cod_momento=2">
                <?php
                        break;
                    case 2: ?>
                <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar_v2&cod_momento=2">
                <?php
                        break;
                    default: ?>
                <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar_v2&cod_momento=2">
                <?php
                        break;
                } 
                ?>
			
                <i class="glyphicon glyphicon-resize-full"></i> <?php echo $t->__('Evaluación Externa', Auth::info_usuario('idioma')); ?></a></li>
			<li><a href="index.php?mod=sievas&controlador=evaluar&accion=tablas_estadisticas">
                <i class="glyphicon glyphicon-resize-full"></i> <?php echo $t->__('Tablas estadísticas', Auth::info_usuario('idioma')); ?></a></li>
			<li><a href="index.php?mod=sievas&controlador=foro&accion=index&momento=1">
                <i class="glyphicon glyphicon-bullhorn"></i> <?php echo $t->__('Foro Comité Interno', Auth::info_usuario('idioma')); ?></a></li>
			<li><a href="index.php?mod=sievas&controlador=foro&accion=index&momento=2">
                <i class="glyphicon glyphicon-bullhorn"></i> <?php echo $t->__('Foro Comité Externo', Auth::info_usuario('idioma')); ?></a></li>
			<li><a href="index.php?mod=sievas&controlador=foro&accion=index&momento=2">
                <i class="glyphicon glyphicon-file"></i> <?php echo $t->__('Generar predictamen', Auth::info_usuario('idioma')); ?></a></li>
			<li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=generar_dictamen">
                <i class="glyphicon glyphicon-file"></i> <?php echo $t->__('Generar dictamen', Auth::info_usuario('idioma')); ?></a></li>
                <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar_plan_mejoramiento&cod_momento=1">
                <i class="glyphicon glyphicon-tasks"></i> <?php echo $t->__('PMP Interno', Auth::info_usuario('idioma')); ?></a></li>
                <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar_plan_mejoramiento&cod_momento=2">
                <i class="glyphicon glyphicon-tasks"></i> <?php echo $t->__('PMP Externo', Auth::info_usuario('idioma')); ?></a></li>
                    
                    <?php                            
} else {   // si la evaluacion es de tipo cna el menu cambia
    if(Auth::info_usuario('ev_cna') > 0){
    
    
    ?>	
<!--    <li><a href="index.php?mod=sievas&controlador=evaluar&accion=datos_programa">
        <i class="glyphicon glyphicon-screenshot"></i> <?php echo $t->__('Datos del programa', Auth::info_usuario('idioma')); ?></a></li>-->
    <li><a href="index.php?mod=sievas&controlador=evaluar&accion=analisis_indicadores">
        <i class="glyphicon glyphicon-screenshot"></i> <?php echo $t->__('Análisis de indicadores', Auth::info_usuario('idioma')); ?></a></li>
    <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar_v2">
        <i class="glyphicon glyphicon-screenshot"></i> <?php echo $t->__('Gradación', Auth::info_usuario('idioma')); ?></a></li>
    <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar_encuestas">
        <i class="glyphicon glyphicon-screenshot"></i> <?php echo $t->__('Encuestas', Auth::info_usuario('idioma')); ?></a></li>
    <li><a href="index.php?mod=sievas&controlador=evaluar&accion=cuadros_maestros">
        <i class="glyphicon glyphicon-screenshot"></i> <?php echo $t->__('Cuadros Maestros', Auth::info_usuario('idioma')); ?></a></li>
    
    <?php } else { ?>
            <?php if(Auth::info_usuario('ev_anterior') > 0) { ?>
        <li><a href="index.php?mod=sievas&controlador=evaluar&accion=reevaluar&mostrar_ee=1">
                <i class="glyphicon glyphicon-screenshot"></i> <?php echo $t->__('Evaluar', Auth::info_usuario('idioma')); ?></a></li>
        <?php } else { ?>
        <?php switch(Auth::info_usuario('ev_version')){ 
                    case 1: ?>
                <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar">
                <?php
                        break;
                    case 2: ?>
                <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar_v2">
                <?php
                        break;
                    default: ?>
                <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar_v2">
                <?php
                        break;
                } 
                ?>
                <i class="glyphicon glyphicon-screenshot"></i> <?php echo $t->__('Evaluar', Auth::info_usuario('idioma')); ?></a></li>
        <?php } ?>
            
            <li><a href="index.php?mod=sievas&controlador=evaluar&accion=tablero_control">
                <i class="glyphicon glyphicon-dashboard"></i> <?php echo $t->__('Tablero de control', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=evaluar&accion=tablas_estadisticas">
                <i class="glyphicon glyphicon-screenshot"></i> <?php echo $t->__('Tablas estadísticas', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=evaluar&accion=evaluacion_complementaria">
                <i class="glyphicon glyphicon-screenshot"></i> <?php echo $t->__('Ev. Complementaria', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=foro&accion=index">
                <i class="glyphicon glyphicon-bullhorn"></i> <?php echo $t->__('Foro', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=generar_predictamen">
                <i class="glyphicon glyphicon-file"></i> <?php echo $t->__('Generar predictamen', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar_plan_mejoramiento">
                <i class="glyphicon glyphicon-tasks"></i> <?php echo $t->__('Plan de Mejoramiento Permanente', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=cronograma&accion=ver_cronograma">
                <i class="glyphicon glyphicon-calendar"></i> <?php echo $t->__('Cronograma', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=cronograma&accion=ver_cronograma_comite">
                <i class="glyphicon glyphicon-calendar"></i> <?php echo $t->__('Cronograma del comité', Auth::info_usuario('idioma')); ?></a></li>
            
            <li class="divider"></li>
            <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=evaluado">
                <i class="glyphicon glyphicon-eye-open"></i>
                <?php echo $t->__('Ficha básica', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=evaluador">
                <i class="glyphicon glyphicon-user"></i> <?php echo $t->__('Perfil', Auth::info_usuario('idioma')); ?></a></li> 
    <?php }
}?>               
            

       </ul>
   </li>
   <?php if(Auth::info_usuario('ev_cna') == 0) { ?>
   <!--    2. OBSERVATORIO    -->   
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff"><?php echo $t->__('Observatorio', Auth::info_usuario('idioma')); ?> <b class="caret"></b></a>
       <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
            <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=metaevaluacion">
                <i class="glyphicon glyphicon-comment"></i> <?php echo $t->__('Metaevaluación', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=observatorio">
                <i class="glyphicon glyphicon-picture"></i> <?php echo $t->__('Observatorio', Auth::info_usuario('idioma')); ?></a></li>
             <?php if (Session::get('rol') == 1) {?>  
            <li><a href="index.php?mod=sievas&controlador=observatorio&accion=categorias">
                <i class="glyphicon glyphicon-tasks"></i> <?php echo $t->__('Categorías', Auth::info_usuario('idioma')); ?></a></li>
            <?php }?> 
       </ul>
   </li>
   <?php } ?>
   <!--    3. REPORTES    -->   
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff"><?php echo $t->__('Reportes', Auth::info_usuario('idioma')); ?> <b class="caret"></b></a>
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
   
   <!--    4. TUTORÍAS    -->
   <?php if(Auth::info_usuario('ev_cna') == 0) { ?>
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff"><?php echo $t->__('Tutorías', Auth::info_usuario('idioma')); ?>
   		<b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=codigo_etica_v1" target="_blank">
                <?php echo $t->__('Código de ética', Auth::info_usuario('idioma')); ?></a></li>
<!--            <li><a href="public/files/tutorias/Metodología.pdf" target="_blank">
                <?php echo $t->__('Metodología', Auth::info_usuario('idioma')); ?></a></li>-->
            <li><?php echo Auth::info_usuario('tipo_evaluado') == 10 ? '<a href="index.php?mod=sievas&controlador=evaluaciones&accion=manual_sievas_v2" target="_blank">' : '<a href="index.php?mod=sievas&controlador=evaluaciones&accion=manual_sievas_v2" target="_blank">' ?>
                <?php echo $t->__('Manual Sievas', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=guia_ee_v1" target="_blank">
                <?php echo $t->__('Guía Evaluadores Externos', Auth::info_usuario('idioma')); ?></a></li>
        </ul>
   </li>
   <?php } ?>
 <?php
} 

//   ADMIN    ::	ADMINISTRACIÓN Y PARAMETRIZACIÓN
if (Session::get('rol') == 1) {?>  
   <!--    5. ADMINISTRACIÓN   -->
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff"><?php echo $t->__('Administración', Auth::info_usuario('idioma')); ?>
   		<b class="caret"></b></a>
        <ul class="dropdown-menu">
           <li><a href="index.php?mod=sievas&controlador=usuarios&accion=listar_evaluadores">
                <i class="glyphicon glyphicon-eye-open"></i> <?php echo $t->__('Evaluadores', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=instituciones&accion=index">
                <i class="glyphicon glyphicon-map-marker"></i> <?php echo $t->__('Instituciones', Auth::info_usuario('idioma')); ?> </a></li>
            <li><a href="index.php?mod=sievas&controlador=programas&accion=index">
                <i class="glyphicon glyphicon-compressed"></i> <?php echo $t->__('Programas', Auth::info_usuario('idioma')); ?> </a></li>
            <li><a href="index.php?mod=sievas&controlador=usuarios&accion=listar_docentes">
                <i class="glyphicon glyphicon-user"></i> <?php echo $t->__('Docentes', Auth::info_usuario('idioma')); ?></a></li>
            
            <li class="divider"></li>
            <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=listar_evaluaciones">
                <i class="glyphicon glyphicon-bookmark"></i> <?php echo $t->__('Iniciar Evaluación', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=cronograma&accion=crear">
                <i class="glyphicon glyphicon-calendar"></i> <?php echo $t->__('Definir cronograma', Auth::info_usuario('idioma')); ?></a></li>
            
            
            <li class="divider"></li>
            <li><a href="index.php?mod=sievas&controlador=usuarios&accion=autorizar">
                <i class="glyphicon glyphicon-user"></i> <?php echo $t->__('Autorizar Usuarios', Auth::info_usuario('idioma')); ?></a></li>
        </ul>
   </li>
   
   <!--    6. PARAMETRIZACIÓN    -->
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff"><?php echo $t->__('Parametrización', Auth::info_usuario('idioma')); ?>
   		<b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="index.php?mod=sievas&controlador=lineamientos&accion=conjuntos_lineamientos"> <?php echo $t->__('Definir Rubros', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=lineamientos&accion=asociar_lineamientos"> <?php echo $t->__('Asociar Lineamientos', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=gen_paises"> <?php echo $t->__('Países', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=gen_idiomas"> <?php echo $t->__('Idiomas', Auth::info_usuario('idioma')); ?></a></li>
                
            <li class="divider"></li>    
            <li><a href="index.php?mod=sievas&controlador=usuarios&accion=listar_usuarios"> <?php echo $t->__('Usuarios del sistema', Auth::info_usuario('idioma')); ?></a></li>
            <li><a href="index.php?mod=sievas&controlador=roles"> <?php echo $t->__('Roles del sistema', Auth::info_usuario('idioma')); ?></a></li>
        </ul>
   </li>
   <?php
} 
?>
   <!--    7. CERRAR SESIÓN    -->
   <li><a href='index.php?mod=auth&controlador=usuarios&accion=logout' style="color:#fff;">.: <?php echo $t->__('Salir', Auth::info_usuario('idioma')); ?> :.</a></li>
     <?php
     break;
     case 6: ?>
   <!--    6. PARAMETRIZACIÓN    -->
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff"><?php echo $t->__('Parametrización', Auth::info_usuario('idioma')); ?>
   		<b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="index.php?mod=sievas&controlador=lineamientos&accion=conjuntos_lineamientos"> <?php echo $t->__('Definir Rubros', Auth::info_usuario('idioma')); ?></a></li>
        </ul>
   </li>
   
      <!--    7. CERRAR SESIÓN    -->
      <li><a href='index.php?mod=auth&controlador=usuarios&accion=logout' style="color:#fff;">.: <?php echo $t->__('Salir', Auth::info_usuario('idioma')); ?> :.</a></li>
   <?php
}

    
}
//el rol es null cuando se accede como revisor de cee
else  {?>
    <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff"><?php echo $t->__('Evaluación', Auth::info_usuario('idioma')); ?>
   		<b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="index.php?mod=sievas&controlador=avances&accion=validacion_avances"><i class="glyphicon glyphicon-dashboard"></i> <?php echo $t->__('Validación', Auth::info_usuario('idioma')); ?></a></li>
        </ul>
   </li>
    <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff"><?php echo $t->__('Reportes', Auth::info_usuario('idioma')); ?>
   		<b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="index.php?mod=sievas&controlador=avances&accion=monitor_avances"><i class="glyphicon glyphicon-eye-open"></i> <?php echo $t->__('Avances', Auth::info_usuario('idioma')); ?></a></li>
        </ul>
   </li>
     <li><a href='index.php?mod=auth&controlador=usuarios&accion=logout' style="color:#fff;">.: <?php echo $t->__('Salir', Auth::info_usuario('idioma')); ?> :.</a></li>
      <?php
}
?>
   
  </ul>