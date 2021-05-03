<style>
	.ventanaAgregar .modal-dialog{
		width: 90%;
	}
        
        .modal70 > .modal-dialog {
            width:70% !important;
        }
</style> 

<?php if($evaluacion > 0) {?>
<h4><?php 
	if($cod_momento == 1) echo $t->__('Evaluación Interna', Auth::info_usuario('idioma'))." :: Análisis de indicadores";
	else if ($cod_momento == 2) echo $t->__('Evaluación Externa', Auth::info_usuario('idioma'))." :: "; 
	
//	echo $_SESSION['evaluado']; ?>
</h4><hr/>

<div class="row">
	<!-- Arbol de lineamientos -->
    <div class="col-sm-6">
        <div style="overflow:auto">       
        <div class="btn-group">                    
            <a href="#" class="btn btn-default dato-rubro" data-campo="significado"><?php echo $t->__('Significado', Auth::info_usuario('idioma')); ?></a>
            <a href="#" class="btn btn-default dato-rubro" data-campo="contexto"><?php echo $t->__('Contexto', Auth::info_usuario('idioma')); ?></a>
            <a href="#" class="btn btn-default dato-rubro" data-campo="referencia"><?php echo $t->__('Referencia', Auth::info_usuario('idioma')); ?></a>
            <a href="#" class="btn btn-default dato-rubro" data-campo="glosario"><?php echo $t->__('Glosario', Auth::info_usuario('idioma')); ?></a>                
        </div> 

        <!--<div class="btn-group">
            <button type="button" class="btn btn-default">Tabla estadística</button>
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              <span class="caret"></span>
              <span class="sr-only">Tabla estadística</span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#" id="plantilla1">Descargar plantilla</a></li>  
                <li><a href="#" id="ver-tabla-estadistica">Tabla estadística</a></li>  
            </ul>
        </div>   -->

        <br/><br/>
        <p id="nombre-rubro" style="color: #990000"></p>       
        <div class="buscador-tipos-lineamientos">                   

<!--    <a id="cerrar-nodos" class="btn btn-default" href="#">Cerrar nodos</a>
        <a id="abrir-nodos" class="btn btn-default" href="#">Abrir nodos</a><br/> <br/>-->

           <div class="form-actions">
           <div class="input-group">                
                <input type="text" id="q" placeholder="<?php echo $t->__('Buscar', Auth::info_usuario('idioma')); ?>" class="form-control">
                <span id="buscar-tipo-lineamiento" class="input-group-addon">
                    <i class="glyphicon glyphicon-search"></i>
                </span>
            </div>
        </div>
       </div>

       <div id="arbol" class="arbol"></div>
            
        </div>
    </div>

    <!-- Formulario -->
    <div class="col-sm-6">
        <div id="opciones">                
            <div class="controls">                    
                <div class="btn-group">
                    <button type="button" class="btn btn-default"><?php echo $t->__('Antecedentes', Auth::info_usuario('idioma')); ?></button>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                      <span class="caret"></span>
                      <span class="sr-only"><?php echo $t->__('Antecedentes', Auth::info_usuario('idioma')); ?></span>
                    </button>
                    <ul class="dropdown-menu" id="lista-referencias" role="menu">            
                    </ul>
                </div>      
                <button type="button" class="btn btn-default" id="indicadores_item"><?php echo $t->__('Característica', Auth::info_usuario('idioma')); ?></button>   
                <?php if(Auth::info_usuario('ev_red')>0){ ?>
                <div class="btn-group">
                    <button type="button" class="btn btn-default"><?php echo $t->__('Resultados red', Auth::info_usuario('idioma')); ?></button>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                      <span class="caret"></span>
                      <span class="sr-only"><?php echo $t->__('Resultados red', Auth::info_usuario('idioma')); ?></span>
                    </button>
                    <ul class="dropdown-menu" id="lista-evaluaciones" role="menu">      
                        <?php foreach($evaluaciones_red as $ev){?>
                        <li><a href="#" class="resultado_red" data-evaluacion="<?php echo $ev['id'] ?>"><?php echo $ev['etiqueta'] ?></a></li>
                         <?php } ?>
                    </ul>
                </div> 
                <?php } ?>
            </div>                
        </div>
        <br/>
        <div id="datos-item"></div>
        <br/>

        <div id="calificaciones"></div>
        
        <form class="form-horizontal" autocomplete="on" data-cargo='<?= $cod_cargo?>' 
              data-momento='<?= $cod_momento?>' data-bandera-ev='<?= $bandera?>' data-comite-centro="<?php echo Auth::info_usuario('comite_centro')?>" id='formEvaluacion'>


            <div class="form-group" style="padding: 0 17px; overflow:auto">
                <label class="subtitulo"><?php echo $t->__('Análisis del indicador', Auth::info_usuario('idioma')); ?></label>
                <div id="analisis" class="summernote_modal" data-title="<?php echo $t->__('Análisis del indicador', Auth::info_usuario('idioma')); ?>" 
                	data-success="guardar_analisis" 
                    style="padding: 10px; border: 1px solid #F8E0E6; overflow:auto; max-height:170px;">
                  <input type="text" class="form-control">
                </div>
            </div>
                       
<!--            <div class="alert alert-info">
                    <i class="glyphicon glyphicon-file"></i> <?php echo $t->__('Documentos', Auth::info_usuario('idioma')); ?>:
                    <div id="documentos"></div>
                </div>-->
            
            <div id="anexos" style="border: 1px solid #E1B5AE; min-height:100px;">
                <div class="anexos-title"><label class="subtitulo"><?php echo $t->__('Anexos', Auth::info_usuario('idioma')); ?></label></div><?php 
				if($cod_cargo == 1 && $cod_momento == 1 && $bandera == 0){ ?>
                <div class="anexos-menu" style="background: #eee; padding: 0px 3px 3px 25px;">
                	<a href="#" class="agregar-anexo" style="color:#444; font-size:14px"><i class="glyphicon glyphicon-plus"></i> <?php echo $t->__('Agregar', Auth::info_usuario('idioma')); ?></a>
                	<a href="#" class="insertar-url" style="color:#444; font-size:14px"><i class="glyphicon glyphicon-link"></i> <?php echo $t->__('Insertar URL', Auth::info_usuario('idioma')); ?></a></div>
                            <?php  } ?>                
                <div class="anexos-list" style="padding: 0; max-height: 140px; overflow:auto"></div>
            </div>
             <br/>
            

            <br/>
            <div class="form-actions pull-right">
            <a href="#" class="btn btn-primary" id="guardar-item" data-momento="<?= $cod_momento ?>" ><?php echo $t->__('Guardar', Auth::info_usuario('idioma')); ?></a>
            <?php if(Auth::info_usuario('comite_centro') == 1 || $privilegio_replica_red){ ?>
            <a href="#" class="btn btn-default" id="replicar-item"><?php echo $t->__('Replicar ítem', Auth::info_usuario('idioma')); ?></a>
            <?php } ?>
            </div>                
        </form>
        
   
    </div>    
</div>
<div class="tpl_resultado_red hide">
    <table class="table table-striped">
        <tr>
            <td>Programa académico</td>
            <td class="programa_academico"></td>
        </tr>
        <tr>
            <td>Análisis del indicador</td>
            <td class="analisis_indicador"></td>
        </tr>
        <tr>
            <td>Anexos</td>
            <td class="anexos"></td>
        </tr>
    </table>
</div>
<?php } else { ?>
<p><?php echo $t->__('No tiene una evaluación asignada', Auth::info_usuario('idioma')); ?>. <?php echo $t->__('Click', Auth::info_usuario('idioma')); ?> <a href="index.php?mod=auth&controlador=usuarios&accion=locacion"><?php echo $t->__('aquí', Auth::info_usuario('idioma')); ?></a> <?php echo $t->__('para seleccionar una evaluación', Auth::info_usuario('idioma')); ?></p>
<?php } ?>
<div class="insertar-url-form-tpl hide">
    <form>
        <div class="form-group">
            <label>URL <span style="color: #ccc; font-size: 10px">(Debe empezar con http:// o https://)</span></label>
            <input class="form-control url" type="text">
        </div>
        <div class="form-group">
            <label>Nombre</label>
            <input class="form-control nombre" type="text">
        </div>
    </form>
</div>
<div class="ingresar-tabla-estadistica-tpl hide">
    <p>Hola</p>
</div>

<div class="replicar-select-form-tpl hide">
    <table>
        
        <?php foreach ($evaluaciones_centro as $e){ ?>
        <tr>
        <td><input type="checkbox" class="evaluaciones_centro" value="<?php echo $e['e_id'] ?>">  <?php echo $e['etiqueta']?></td>
        </tr>
        <?php } ?>
        
    </table>
</div>