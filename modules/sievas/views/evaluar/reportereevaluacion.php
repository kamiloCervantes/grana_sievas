
<style>
    .table thead>tr>th,.table tbody>tr>th,.table tfoot>tr>th,.table thead>tr>td,.table tbody>tr>td,.table tfoot>tr>td{
        border: 1px solid #F5A9BC; 
    }
    
	
    .table ul.lista{		
        padding: 0;
        margin-left: 11px;
    }
    
    .table ul.lista li{
        list-style: square;
    }
     
    td{
        font-size: 12px;
		padding: 0;
                overflow-x: auto;
    }
	   
    th{
        font-size: 12px;
		text-align:center;
		color: #fff;
    }
    
    thead{
		background: #800000;
		font-size: 14px; 
		vertical-align:middle; 
		font-weight:bold; 
		color:#FFFFFF; 
		text-align:center;
    }   
    
    .popover{
        max-width: 750px;
    }
    #btn_up {
        display: none;
        position: fixed;
        bottom: 60px;
        right: 30px;
        z-index: 99;
        font-size: 18px;
        border: none;
        outline: none;
        background-color: red;
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 4px;
      }

      #btn_up:hover {
        background-color: #555;
      }
</style>


<h4 class="sub-header">    
	Ficha de evaluacion anterior <?php echo $cod_momento_actual == 1 ? 'interna' : 'externa' ?> :: <?php echo $evaluacion_data['etiqueta'] ?>
</h4>
<hr/>

<?php if($acceso_rapido == 1 || true){ $k = 1; ?>
<button id="btn_up" title="Ir arriba">Arriba</button>
<div class='pull-right'>
<label for='acceso_rapido'>Acceso rápido</label>
<select id="acceso_rapido" style="width: 300px">
  <?php foreach ($rubros as $r){ ?>
    <option>Seleccione una opción...</option>
    <optgroup label="<?php echo Auth::info_usuario('ev_cna') > 0 ? 'FACTOR ': 'RUBRO '?><?php echo "$k. ".$r['nom_lineamiento']; ?>">">
    <?php $q = 1; foreach($r['lineamientos'] as $l){ ?>
        <option value="<?php echo $l['lineamiento_id']; ?>"><?php echo $k.'.'.$q.'.'.$l['nom_lineamiento']; ?></option>
    <?php $q++; } ?>
    </optgroup>
  <?php $k++; } ?>
</select>
</div>
<br/>
<br/>
<?php } ?>

<input type="hidden" id="momento_evaluacion" value="<?php echo $momento_actual
        ; ?>">
<?php if($rol == null){ ?>

<table class="table">
    <thead>
        <tr><td colspan="5">Datos de la evaluación</td></tr>
    </thead>
    <tr>
        <td>Evaluación</td>
        <td><?php echo $evaluacion_data['etiqueta']?></td>
    </tr>
    <tr>
        <td>Evaluado</td>
        <td><?php echo $evaluacion_data['programa']?></td>
    </tr>
    <tr>
        <td>Institución</td>
        <td><?php echo $evaluacion_data['nom_institucion']?></td>
    </tr>
    <tr>
        <td>País</td>
        <td><?php echo $evaluacion_data['nom_pais']?></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align:center"><a href="index.php?mod=sievas&controlador=avances&accion=validacion_avances" class="btn btn-default btn-xs">Cambiar evaluación</a></td>
    </tr>
</table>

<?php } ?>

<?php 
$i = 1;
foreach ($rubros as $r){ ?>
<table class="table">
  <thead>
    <tr>
        <th colspan="3" class="rubro-popover" data-rubro="<?php echo $r['id']; ?>"><?php echo Auth::info_usuario('ev_cna') > 0 ? 'FACTOR ': 'RUBRO '?><?php echo "$i. ".$r['nom_lineamiento']; ?></th>
    </tr>
  </thead>

<tbody><?php 
	$c = 1;
    foreach($r['lineamientos'] as $l){ ?>
       
     <tr>
            <td colspan="3" align="center" style="background:#dfa5a9;" class="print">
                <a class="item-popover acceso_rapido_<?php echo $l['lineamiento_id']; ?>" data-rubro="<?php echo $l['lineamiento_id']; ?>" style="color: #000" name="<?php echo str_replace(' ', '-', $l['nom_lineamiento']) ?>">
                    <strong><?= "$i.$c. $l[nom_lineamiento]"; ?></strong></a></td>          
		</tr>
        <tr>    
            <td width="50%" style="padding-left:40px; background:#FEDADF; border-right: 1px solid #FEDADF"><strong>Fortalezas E. Interna</strong></td>
            <td width="50%" style="padding-left:40px; background:#FEDADF; border-right: 1px solid #FEDADF"><strong>Fortalezas E. Externa</strong></td>
        </tr>
        
        <tr>
            <td class="print" style="max-width: 10em"><?php echo ($l['fortalezas'] == null ? 'N/A' : urldecode($l['fortalezas']))?></td>
            <td class="print" style="max-width: 10em"><?php echo ($l['ee']['fortalezas'] == null ? 'N/A' : urldecode($l['ee']['fortalezas']))?></td>
        </tr>
        <tr>
            <td width="20%" style="padding-left:40px; background:#FEDADF;border-right: 1px solid #FEDADF"><strong>Debilidades E. Interna</strong></td>
            <td width="20%" style="padding-left:40px; background:#FEDADF;border-right: 1px solid #FEDADF"><strong>Debilidades E. Externa</strong></td>
        </tr>
        <tr>
            <td class="print" style="max-width: 10em"><?php echo ($l['debilidades'] == null ? 'N/A' : urldecode($l['debilidades']))?></td> 
            <td class="print" style="max-width: 10em"><?php echo ($l['ee']['debilidades'] == null ? 'N/A' : urldecode($l['ee']['debilidades']))?></td> 
        </tr>
        <tr>
            <td width="20%" style="padding-left:40px; background:#FEDADF;border-right: 1px solid #FEDADF"><strong>Plan de mejoramiento E. Interna</strong></td>
            <td width="20%" style="padding-left:40px; background:#FEDADF;border-right: 1px solid #FEDADF"><strong>Plan de mejoramiento E. Externa</strong></td>
        </tr>
        <tr>
            <td class="print" style="max-width: 10em"><?php echo ($l['plan_mejoramiento'] == null ? 'N/A' : 
				urldecode($l['plan_mejoramiento']))?></td>
            <td class="print" style="max-width: 10em"><?php echo ($l['ee']['plan_mejoramiento'] == null ? 'N/A' : 
				urldecode($l['ee']['plan_mejoramiento']))?></td>
        </tr>
        
        
        <tr>
            <!--<td width="10%" style="padding-left:40px; background:#FEDADF;"><strong>Anexos</strong></td>-->
        </tr>
        <tr>
            <?php if($mostrar_ee == 1){ ?>
            <td colspan="2"> 
            <?php }else{ ?>
            <td> 
            <?php } ?>   
               <div class="row">
               <div class="col-sm-6">
               <p>Promedio general <?php echo ($mostrar_ee == 1 ? 'E. Externa': '') ?></p>
               <div class="progress">
               <div class="progress-bar <?php echo $l['estadisticas']['puntaje'] <= $l['gradacion']['nivel_bajo'] ? 'progress-bar-danger' : $l['estadisticas']['puntaje'] <= $l['gradacion']['nivel_medio'] && $l['estadisticas']['puntaje'] > $l['gradacion']['nivel_bajo'] ? 'progress-bar-warning' : $l['estadisticas']['puntaje'] <= $l['gradacion']['nivel_alto'] && $l['estadisticas']['puntaje'] > $l['gradacion']['nivel_medio'] ? 'progress-bar-success' : '' ?> 
" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $l['estadisticas']['puntaje']*10?>%;">
               <?php echo $l['estadisticas']['puntaje']?> 
               </div>
               </div>
               </div>
               <div class="col-sm-6">
               <p>Calificación rubro <?php echo ($mostrar_ee == 1 ? 'E. Externa': '') ?></p>
               <div class="progress">
               <div class="progress-bar  <?php echo $l['estadisticas']['calificacion_rubro']['valor'] <= $l['gradacion']['nivel_bajo'] ? 'progress-bar-danger' : $l['estadisticas']['calificacion_rubro']['valor'] <= $l['gradacion']['nivel_medio'] && $l['estadisticas']['calificacion_rubro']['valor'] > $l['gradacion']['nivel_bajo'] ? 'progress-bar-warning' : $l['estadisticas']['calificacion_rubro']['valor'] <= $l['gradacion']['nivel_alto'] && $l['estadisticas']['calificacion_rubro']['valor'] > $l['gradacion']['nivel_medio'] ? 'progress-bar-success' : '' ?> " role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $l['estadisticas']['calificacion_rubro']['porcentaje']?>%">
               <?php echo $l['estadisticas']['calificacion_rubro']['valor']?> 
               </div>                       
               </div>
               </div>                   
               </div>
            </td>
           
        </tr>
        <tr>
            <?php if($mostrar_ee == 1){ ?>
            <td>
                <strong>Calificación E.Interna: </strong> <?php echo ($l['desc_escala'] == null ? 'N/A' : 
				$l['desc_escala'])?>
                
                
                
            </td>
            <td>
                <strong>Calificación E.Externa: </strong> <?php echo ($l['ee']['desc_escala'] == null ? 'N/A' : 
				$l['ee']['desc_escala'])?>
                
                
                
            </td>
            <?php }else{ ?>
            <td>
                <strong>Calificación: </strong> <?php echo ($l['desc_escala'] == null ? 'N/A' : 
				$l['desc_escala'])?>
                
                
                
            </td>
            <?php } ?>
                
            <td data-lineamiento="<?php echo $l['lineamiento_id']; ?>"><strong>Nueva calificación: </strong>
                <input type="hidden" class="cal form-control" value="<?php echo ($l['evaluacion_actual']['escala_id'] == null ? '' : $l['evaluacion_actual']['escala_id'])?>">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="alert alert-info">
                    <i class="glyphicon glyphicon-file"></i> <?php echo $t->__('Documentos', Auth::info_usuario('idioma')); ?>:
                    <div id="documentos"><?php echo urldecode($l['documentos']); ?></div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="print"><strong>Anexos: </strong>
                <div class="listado-anexos" style="display: inline" data-lineamiento="<?php echo $l['lineamiento_id']; ?>">
                <?php 
                foreach($l['anexos'] as $a){ ?>
                	<a href="<?php echo $a['ruta']?>" target="_blank"><span data-documento="<?php echo $a['id']?>" class="label <?php echo $a['nuevo'] > 0 ? 'label-success' : 'label-primary' ?>">
                    	<i class="glyphicon glyphicon-file"></i> <?php echo $a['nombre']?></span></a><?php 
				}   ?>
                </div>
                    	<i class="glyphicon glyphicon-plus-sign anexos-popover" data-rubro="<?php echo $l['lineamiento_id']; ?>" style="top: 3px;color: green;"></i>
                    	<i class="glyphicon glyphicon-trash rm-anexos-popover" data-rubro="<?php echo $l['lineamiento_id']; ?>" style="top: 3px;color: red;"></i>
            </td>
		</tr>
        
        
       <?php  
	$c++;
	} ?>  
 </table>
</tbody>
<br/><br/><br/><br/><br/><br/>
</table><?php 

$i++; 
} ?>
          
            
<div id="rubro-popover-tpl" class="hide">
    <div class="btn-group">                    
        <a href="#" class="btn btn-default dato-rubro" data-campo="significado"><?php echo $t->__('Significado', Auth::info_usuario('idioma')); ?></a>
        <a href="#" class="btn btn-default dato-rubro" data-campo="contexto"><?php echo $t->__('Contexto', Auth::info_usuario('idioma')); ?></a>
        <a href="#" class="btn btn-default dato-rubro" data-campo="referencia"><?php echo $t->__('Referencia', Auth::info_usuario('idioma')); ?></a>
        <a href="#" class="btn btn-default dato-rubro" data-campo="glosario"><?php echo $t->__('Glosario', Auth::info_usuario('idioma')); ?></a>                
        <a href="#" class="btn btn-default anexos-rubro"><?php echo $t->__('Anexos', Auth::info_usuario('idioma')); ?></a>                
    </div> 
</div>
            
<div id="item-popover-tpl" class="hide">      
    <div>
    <button type="button" class="btn btn-default dato-item"><?php echo $t->__('Indicadores', Auth::info_usuario('idioma')); ?></button>
    </div>
</div>
<div id="anexos-popover-tpl" class="hide">      
    <div>
    <button type="button" class="btn btn-default subir-anexo"><?php echo $t->__('Subir anexo', Auth::info_usuario('idioma')); ?></button>
    <button type="button" class="btn btn-default insertar-url"><?php echo $t->__('Insertar URL', Auth::info_usuario('idioma')); ?></button>
    </div>
</div>
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
<div class="anexo-tpl hide">
    <a href="#" target="_blank"><span class="label label-success">
                    	<i class="glyphicon glyphicon-file"></i> </span></a>
</div>

<div class="anexo2-tpl hide">
    <div>
    <a href="#" target="_blank"><span class="label label-success">
    <i class="glyphicon glyphicon-file"></i> </span></a>
    <a href="#" class="eliminar-anexo" style="color: red"><i class="glyphicon glyphicon-minus-sign"></i></a>
    </div>
</div>

<div class="anexos-tablas-est-tpl hide">
    <div>
        <div class="tablas-estadisticas-list" style="display: inline">
            
        </div>
       
    </div>
</div>

<div class="anexos-tablas-est-tpl-no-data hide">
    <p>Puede gestionar las tablas estadisticas haciendo click <a href="index.php?mod=sievas&controlador=evaluar&accion=tablas_estadisticas">aquí</a></p>
</div>

