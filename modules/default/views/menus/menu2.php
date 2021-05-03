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
  
<ul class="nav navbar-nav navbar-right"><?php

	switch(Session::get('rol')){ 
	
	//ADMIN - ADMINISTRADOR DEL SISTEMA, ACCESO TOTAL	
case "1": ?>
	
	<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff">Actas 
        <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="index.php?mod=sievas&controlador=predictamen">
                Predictamen</a></li>
            <li><a href="index.php?mod=sievas&controlador=dictamen">
                Dictamen</a></li>
            <li class="divider"></li>
            <li><a href="index.php?mod=sievas&controlador=acta">
                <i class="glyphicon glyphicon-bookmark"></i> Actas</a></li> 
        </ul>
   </li>
   
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff">Evaluar <b class="caret"></b></a>
       <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
            <li><a href="index.php?mod=sievas&controlador=cronograma&accion=ver_cronograma">
                <i class="glyphicon glyphicon-road"></i>
                Cronograma</a></li>
            <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar">
                <i class="glyphicon glyphicon-road"></i>
                Evaluación</a></li>
            <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar_plan_mejoramiento">
                <i class="glyphicon glyphicon-road"></i>
                Plan de mejoras</a></li>
            <li class="dropdown-submenu">
                <a tabindex="-1" href="#"><i class="glyphicon glyphicon-road"></i> Seguimiento</a>
                <ul class="dropdown-menu">
                    <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=evaluado">
                        <i class="glyphicon glyphicon-road"></i>
                        Evaluación</a></li>
                    <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=evaluado">
                        <i class="glyphicon glyphicon-road"></i>
                        Plan de mejoras</a></li>                    
                </ul>
             </li>
            </ul>

   </li>
   
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff">Ficha básica
   		<b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=evaluado">
                <i class="glyphicon glyphicon-road"></i>
                Evaluación</a></li>
                
            <li class="divider"></li>
            <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=evaluador">
                <i class="glyphicon glyphicon-user"></i> Perfil</a></li> 
        </ul>
   </li>
   
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff">Informes
   		<b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="index.php?mod=sievas&controlador=info_documento">
                Documento maestro<!--Rubros, Gráficas y documentos--></a></li>
            <li><a href="index.php?mod=sievas&controlador=info_evaluaciones">
                Evaluaciones</a></li>
            <li><a href="index.php?mod=sievas&controlador=info_resultados">
                Resultados Rubros</a></li>
        </ul>
   </li>
   
   <li><a href="index.php?mod=sievas&controlador=observatorio" style="color: #fff">
   		Megaobservatorio</a></li>
   
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff">Tutorías
   		<b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="index.php?mod=sievas&controlador=">
                Código de ética</a></li>
            <li><a href="index.php?mod=sievas&controlador=">
                Manual compendio</a></li>
            <li><a href="index.php?mod=sievas&controlador=">
                Tutorial Sievas</a></li>
            <li><a href="index.php?mod=sievas&controlador=">
                Tutorial asesoría y consultoría</a></li>
                  
            <li class="divider"></li>
            <li><a href="index.php?mod=sievas&controlador=">
                <i class="glyphicon glyphicon-leaf"></i> Glosario</a></li> 
            <li><a href="index.php?mod=sievas&controlador=">
                <i class="glyphicon glyphicon-leaf"></i> Referencia bibliográfica</a></li>
        </ul>
   </li>
   
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff">Administración
   		<b class="caret"></b></a>
        <ul class="dropdown-menu">
           <li><a href="index.php?mod=sievas&controlador=usuarios&accion=listar_evaluadores">
                <i class="glyphicon glyphicon-th-list"></i> Evaluadores</a></li>
            <li><a href="index.php?mod=sievas&controlador=instituciones&accion=index">
                <i class="glyphicon glyphicon-map-marker"></i> Instituciones </a></li>
            <li><a href="index.php?mod=sievas&controlador=programas&accion=index">
                <i class="glyphicon glyphicon-compressed"></i> Programas </a></li>
            <li><a href="index.php?mod=sievas&controlador=usuarios&accion=listar_maestros">
                <i class="glyphicon glyphicon-user"></i> Profesores</a></li>
            
            <li class="divider"></li>
            <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=listar_evaluaciones">
                <i class="glyphicon glyphicon-bookmark"></i> Iniciar Evaluación</a></li>
            <li><a href="index.php?mod=sievas&controlador=cronograma&accion=crear">
                <i class="glyphicon glyphicon-certificate"></i> Definir cronograma</a></li>
            <li><a href="index.php?mod=sievas&controlador=rubros">
                <i class="glyphicon glyphicon-certificate"></i> Finalizar Evaluación</a></li>
            
            
            <li class="divider"></li>
            <li><a href="index.php?mod=sievas&controlador=usuarios">
                <i class="glyphicon glyphicon-user"></i> Autorizar Usuarios</a></li>
        </ul>
   </li>
   
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff">Parametrización
   		<b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="index.php?mod=sievas&controlador=rubros"> Definir Rubros</a></li>
            <li><a href="index.php?mod=sievas&controlador=lineamientos&accion=asociar_lineamientos"> Asociar lineamientos</a></li>
            <li><a href="index.php?mod=sievas&controlador=gen_paises"> Países</a></li>
                
            <li class="divider"></li>    
            <li><a href="index.php?mod=sievas&controlador=roles_acceso"> Accesibilidad</a></li>
            <li><a href="index.php?mod=sievas&controlador=roles"> Roles</a></li>
            <li><a href="index.php?mod=sievas&controlador=paginas"> Paginas</a></li>
            <li><a href="index.php?mod=sievas&controlador=config_rol"> Configurar Rol</a></li>
                
            <li class="divider"></li>
            <li><a href="index.php?mod=sievas&controlador=glosario"> Glosario</a></li> 
            <li><a href="index.php?mod=sievas&controlador=referencia"> Referencia bibliográfica</a></li> 
            <li><a href="index.php?mod=sievas&controlador=subir"> Tutorías</a></li>
        </ul>
   </li>
   
   <li><a href='index.php?mod=auth&controlador=usuarios&accion=logout' style="color:#fff;">.: Salir :.</a></li><?php   
   
break;  

case "2":	?>
          
   <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff">Actas 
        <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="index.php?mod=sievas&controlador=predictamen">
                Predictamen</a></li>
            <li><a href="index.php?mod=sievas&controlador=dictamen">
                Dictamen</a></li>
            <li class="divider"></li>
            <li><a href="index.php?mod=sievas&controlador=actas">
                <i class="glyphicon glyphicon-bookmark"></i> Actas</a></li> 
        </ul>
   </li>
   
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff">Evaluar <b class="caret"></b></a>
       <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
            <li><a href="index.php?mod=sievas&controlador=cronograma&accion=ver_cronograma">
                <i class="glyphicon glyphicon-road"></i>
                Cronograma</a></li>
            <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar">
                <i class="glyphicon glyphicon-road"></i>
                Evaluación</a></li>
            <li><a href="index.php?mod=sievas&controlador=evaluar&accion=guardar_plan_mejoramiento">
                <i class="glyphicon glyphicon-road"></i>
                Plan de mejoras</a></li>
            <li class="dropdown-submenu">
                <a tabindex="-1" href="#"><i class="glyphicon glyphicon-road"></i> Seguimiento</a>
                <ul class="dropdown-menu">
                    <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=evaluado">
                        <i class="glyphicon glyphicon-road"></i>
                        Evaluación</a></li>
                    <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=evaluado">
                        <i class="glyphicon glyphicon-road"></i>
                        Plan de mejoras</a></li>                    
                </ul>
             </li>
            </ul>

   </li>
   
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff">Ficha básica
   		<b class="caret"></b></a>
        <ul class="dropdown-menu">
<!--            <li><a href="index.php?mod=sievas&controlador=institucion">
                Institución</a></li>
            <li><a href="index.php?mod=sievas&controlador=programa">
                Programa</a></li>-->


            <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=evaluado">
                <i class="glyphicon glyphicon-road"></i>
                Evaluación</a></li><!-- DATOS DE LA EVALUACIÓN FECHA Y HORA-->
<!--            <li><a href="index.php?mod=sievas&controlador=comite">
                <i class="glyphicon glyphicon-eye-open"></i> Comité evaluador</a></li>-->
                
            <li class="divider"></li>
            <li><a href="index.php?mod=sievas&controlador=evaluaciones&accion=evaluador">
                <i class="glyphicon glyphicon-user"></i> Perfil</a></li> 
        </ul>
   </li>
   
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff">Informes
   		<b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="index.php?mod=sievas&controlador=info_documento">
                Documento maestro<!--Rubros, Gráficas y documentos--></a></li>
            <li><a href="index.php?mod=sievas&controlador=info_evaluaciones">
                Evaluaciones</a></li>
            <li><a href="index.php?mod=sievas&controlador=info_resultados">
                Resultados Rubros</a></li>
        </ul>
   </li>
   
   <li><a href="index.php?mod=sievas&controlador=observatorio" style="color: #fff">
   		Megaobservatorio</a></li>
   
   <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff">Tutorías
   		<b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="index.php?mod=sievas&controlador=">
                Código de ética</a></li>
            <li><a href="index.php?mod=sievas&controlador=">
                Manual compendio</a></li>
            <li><a href="index.php?mod=sievas&controlador=">
                Tutorial Sievas</a></li>
            <li><a href="index.php?mod=sievas&controlador=">
                Tutorial asesoría y consultoría</a></li>
                  
            <li class="divider"></li>
            <li><a href="index.php?mod=sievas&controlador=">
                <i class="glyphicon glyphicon-leaf"></i> Glosario</a></li> 
            <li><a href="index.php?mod=sievas&controlador=">
                <i class="glyphicon glyphicon-leaf"></i> Referencia bibliográfica</a></li>
        </ul>
   </li>   
   
   <li><a href='index.php?mod=auth&controlador=usuarios&accion=logout' style="color:#fff;">.: Salir :.</a></li><?php  
	
break;

case "3": //EVALUADOR INTERNO ?>

	<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff">Evaluación
		<b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a href="index.php?mod=sievas&controlador=">
				<i class="glyphicon glyphicon-flag"></i> Interna</a></li>
			<li><a href="index.php?mod=sievas&controlador=">
				<i class="glyphicon glyphicon-flash"></i> Externa</a></li>
			<li><a href="index.php?mod=sievas&controlador=">
				<i class="glyphicon glyphicon-globe"></i> Interna y Externa</a></li>
		</ul>
   	</li>
   	<li><a href="index.php?mod=sievas&controlador=" style="color: #fff">
		Resultados</a></li><!-- Contiene el texto ingresado en evaluar -->
	<li><a href="index.php?mod=sievas&controlador=" style="color: #fff">
		Documento maestro</a></li><!-- Documento que incluye graficas y soportes INTERNA Y EXTERNA-->
	<li><a href="index.php?mod=sievas&controlador=" style="color: #fff">
		Rubros</a></li>

	<li><a href='index.php?mod=auth&controlador=usuarios&accion=logout' style="color:#fff;">.: Salir :.</a></li><?php   
	
break;

case "4": //EVALUADOR EXTERNO	?>
  
	<li><a href="index.php?mod=sievas&controlador=" style="color: #fff">
		Instituciones y Programas</a></li>
	<li><a href="index.php?mod=sievas&controlador=" style="color: #fff">
		Usuarios</a></li>
	<li><a href="index.php?mod=sievas&controlador=" style="color: #fff">
		Rubros</a></li>
	
	<li><a href="index.php?mod=sievas&controlador=" style="color: #fff">
		Referencias</a></li> 
	<li><a href="index.php?mod=sievas&controlador=" style="color: #fff">
		Tutorías</a></li>
		
	<li><a href="index.php?mod=sievas&controlador=" style="color: #fff">
		Glosario</a></li> 
	 
	<li><a href='index.php?mod=auth&controlador=usuarios&accion=logout' style="color:#fff;">.: Salir :.</a></li><?php   
	
break;
   	
case "5": //ACREDITADOR ?>
		
    <li><a href="index.php?mod=sievas&controlador=" style="color: #fff">
        Dictamen</a></li>
        
    <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff">Evaluación
        <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="index.php?mod=sievas&controlador=">
                <i class="glyphicon glyphicon-flag"></i> Interna</a></li>
            <li><a href="index.php?mod=sievas&controlador=">
                <i class="glyphicon glyphicon-flash"></i> Externa</a></li>
            <li><a href="index.php?mod=sievas&controlador=">
                <i class="glyphicon glyphicon-globe"></i> Interna y Externa</a></li>
        </ul>
    </li>
   
    <li><a href="index.php?mod=sievas&controlador=" style="color: #fff">
        Documento maestro</a></li><!-- Documento que incluye graficas y soportes INTERNA Y EXTERNA-->
    

    <li><a href='index.php?mod=auth&controlador=usuarios&accion=logout' style="color:#fff;">.: Salir :.</a></li><?php   
	
break;

case "7": //CONSULTOR ?>

    <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #fff">Evaluación
        <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="index.php?mod=sievas&controlador=">
                <i class="glyphicon glyphicon-flag"></i> Interna</a></li>
            <li><a href="index.php?mod=sievas&controlador=">
                <i class="glyphicon glyphicon-flash"></i> Externa</a></li>
            <li><a href="index.php?mod=sievas&controlador=">
                <i class="glyphicon glyphicon-globe"></i> Interna y Externa</a></li>
        </ul>
   	</li>
   	<li><a href="index.php?mod=sievas&controlador=" style="color: #fff">
        Resultados</a></li><!-- Contiene el texto ingresado en evaluar -->
    <li><a href="index.php?mod=sievas&controlador=" style="color: #fff">
        Documento maestro</a></li><!-- Documento que incluye graficas y soportes INTERNA Y EXTERNA-->
    <li><a href="index.php?mod=sievas&controlador=" style="color: #fff">
        Rubros</a></li>

    <li><a href='index.php?mod=auth&controlador=usuarios&accion=logout' style="color:#fff;">.: Salir :.</a></li><?php   

break; ?>


   </ul><?php    
} ?>