
<style>
    .table thead>tr>th,.table tbody>tr>th,.table tfoot>tr>th,.table thead>tr>td,.table tbody>tr>td,.table tfoot>tr>td{
        border: 1px solid #F5A9BC; 
    }
    
    tbody tr:nth-child(even) td{
        background: #dfa5a9;
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
		vertical-align:middle;
		padding: 0;
    }
	   
    th{
        background: #FBEFF2;
		font-size: 12px;
		text-align:center;
		color: #800000;
    }
    
    thead{
		background: #800000;
		font-size: 14px; 
		vertical-align:middle; 
		font-weight:bold; 
		color:#FFFFFF; 
		text-align:center;
    }    
</style>


<h4 class="sub-header">
    Retroalimentar avance
</h4>
<hr/>
    
<table class="table">
    <thead>
        <tr><td colspan="2">Datos actuales del lineamiento seleccionado</td></tr>
    </thead>
    <tr>
        <td>Lineamiento</td>
        <td><?php echo $lineamiento_data['atributos_lineamiento'].$lineamiento_data['num_orden']." ".$lineamiento_data['nom_lineamiento'] ?></td>
    </tr>
    <tr>
        <td>Fortalezas</td>
        <td id="fortalezas"><?php echo urldecode($lineamiento_data['fortalezas']) ?></td>
    </tr>
    <tr>
        <td>Debilidades</td>
        <td id="debilidades"><?php echo urldecode($lineamiento_data['debilidades']) ?></td>
    </tr>
    <tr>
        <td>Plan de mejoramiento</td>
        <td id="plan_mejoramiento"><?php echo urldecode($lineamiento_data['plan_mejoramiento']) ?></td>
    </tr>
    <tr>
        <td>Calificación</td>
         <td id="calificacion"><?php echo $lineamiento_data['desc_escala'] ?></td>
    </tr>
    <tr>
        <td>Anexos</td>
        <td id="anexos">
            <?php 
            foreach($anexos as $a){ ?>
                	<a href="<?php echo $a['ruta']?>" target="_blank"><span class="label label-primary">
                    	<i class="glyphicon glyphicon-file"></i> <?php echo $a['nombre']?></span></a><?php 
				}   ?></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:center"><a href="index.php?mod=sievas&controlador=avances&accion=resultados_evaluacion&evaluacion=<?php echo $evaluacion?>#<?php echo str_replace(' ', '-', $lineamiento_data['nom_lineamiento']) ?>" class="btn btn-default btn-xs">Seleccionar otro lineamiento</a></td>
    </tr>
</table>
    
    <h4 style="display: inline-block">Revisiones</h4>
    <?php if($rol == 2 && $momento == 2) { ?>
    <a href="#" class="btn btn-primary pull-right" id="add_revision"><i class="glyphicon glyphicon-plus-sign"></i> Nueva revisión</a>
    <?php } ?>
    <table id="revisiones" class="table">
        <tr>
            <th>Índice</th>
            <th>Fecha</th>
            <th>Comentarios</th>
            <th>Validez</th>
            <th>Revisor</th>
            <th>&nbsp;</th>
        </tr>
        <?php foreach($revisiones as $r){ ?>
        <tr>
            <td><?php echo ($r['indice_revision']+1) ?></td>
            <td><?php echo $r['fecha_creacion'] ?></td>
            <td><?php echo $r['comentarios'] ?></td>
            <td><?php echo $r['validez'] > 0 ? 'SI':'NO' ?></td>
            <td><?php echo $r['revisor'] ?></td>
            <td>
                <a href="#" class="btn btn-xs btn-default evaluacion_item" data-id="<?php echo $r['revision_id'] ?>" title="Ver evaluacion de ítem">
                    <i class="glyphicon glyphicon-search"></i>
                </a>
                <?php if($r['cod_revisor'] == $cod_usuario) {?>
                <a href="#" class="btn btn-xs btn-default editar_revision" data-id="<?php echo $r['revision_id'] ?>" title="Editar evaluacion de ítem">
                    <i class="glyphicon glyphicon-edit"></i>
                </a>
                
                <a href="#" class="btn btn-xs btn-default eliminar_revision" data-id="<?php echo $r['revision_id'] ?>" title="Eliminar revisión">
                    <i class="glyphicon glyphicon-trash"></i>
                </a>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
    
    
    <div class="add_revision_tpl hide">
        <form>
            <div class="form-group">
                <label>Comentarios y consideraciones</label>
                <textarea class="form-control comentario" rows="15"></textarea>
            </div>
            <div class="checkbox">
                <label>
                  <input type="checkbox" class="validar"> Validar información
                </label>
              </div>
            <input type="hidden" class="cod_lineamiento" value="<?php echo $_GET['lineamiento'] ?>"> 
            <input type="hidden" class="cod_momento_evaluacion" value="<?php echo $_GET['momento_evaluacion'] ?>"> 
            <input type="hidden" class="cod_gradacion" value="<?php echo $lineamiento_data['gradacion_id'] ?>"> 
            <input type="hidden" class="tipo_revision" value="2"> 
        </form>
    </div>
    

    
    


            



