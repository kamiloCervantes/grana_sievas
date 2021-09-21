
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
</style>


<h4 class="sub-header">
    
    <?php if(Auth::info_usuario('ev_cna') == 0 ){ ?>
    <a href="index.php?mod=sievas&controlador=avances&accion=resultados_evaluacion_html&momento=<?php echo $momento ?>" target="_blank">
	<i class="glyphicon glyphicon-print"></i></a>
    <?php } ?>
    <?php if(Auth::info_usuario('ev_cna') == 1 ){ ?>
    <a href="index.php?mod=sievas&controlador=avances&accion=resultados_evaluacion_html&momento=<?php echo $momento ?>">
	<i class="glyphicon glyphicon-print"></i></a>
    <?php } ?>
	<?php echo $t->__('Resumen Evaluación', Auth::info_usuario('idioma')); ?>
    <select id="tipo_momento" style="width:120px" data-evaluacion="<?php echo $_GET['evaluacion']?>">
        <option value="1" <?php echo ($momento == "1" ? 'selected':'') ?>><?php echo $t->__('Interna', Auth::info_usuario('idioma')); ?></option>
        <option value="2" <?php echo ($momento == "2" ? 'selected':'') ?>><?php echo $t->__('Externa', Auth::info_usuario('idioma')); ?></option>
    </select>
        <?= " :: $_SESSION[evaluado]"; ?>
</h4>
<hr/>

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
        <td colspan="2" style="text-align:center"><a href="index.php?mod=sievas&controlador=avances&accion=validacion_avances" class="btn btn-default btn-xs">Cambiar evaluación</a></td>
    </tr>
</table>

<?php } ?>

<?php 
$i = 1;
foreach ($rubros as $r){ ?>
<table class="table">
  <thead>
    <tr>
        <th colspan="3"><?php echo Auth::info_usuario('ev_cna') > 0 ? 'FACTOR ': $t->__('RUBRO', Auth::info_usuario('idioma')); ?> <?php echo "$i. ".$r['nom_lineamiento']; ?></th>
    </tr>
  </thead>

<tbody><?php 
	$c = 1;
    foreach($r['lineamientos'] as $l){ ?>
        <tr>
            <td colspan="2" align="center" style="background:#dfa5a9;" class="print">
                <a style="color: #000" name="<?php echo str_replace(' ', '-', $l['nom_lineamiento']) ?>">
                    <strong><?= "$i.$c. $l[nom_lineamiento]"; ?></strong></a></td>
            <td align="center" style="background:#dfa5a9;">
                <?php if($rol == null){ ?>
                <a href="index.php?mod=sievas&controlador=avances&accion=validar_avance&momento_evaluacion=<?php echo $momento_evaluacion; ?>&lineamiento=<?php echo $l['lineamiento_id']; ?>" class="btn btn-default btn-xs">Validar ítem</a>
                <?php } else{ ?>
                <a href="index.php?mod=sievas&controlador=avances&accion=validar_avance&momento_evaluacion=<?php echo $momento_evaluacion; ?>&lineamiento=<?php echo $l['lineamiento_id']; ?>" class="btn btn-default btn-xs"><?php echo $t->__('Comprobar validez', Auth::info_usuario('idioma')); ?></a>
                <?php if($rol == 2 && $momento == 1){ ?>
                <a href="index.php?mod=sievas&controlador=avances&accion=retroalimentar_avance&momento_evaluacion=<?php echo $momento_evaluacion; ?>&lineamiento=<?php echo $l['lineamiento_id']; ?>" class="btn btn-default btn-xs"><?php echo $t->__('Retroalimentar', Auth::info_usuario('idioma')); ?></a>                
                <?php } ?>
                <?php } ?>
            </td>
		</tr>
        
        <tr>
        	<td width="54%" style="padding-left:40px; background:#FEDADF;"><strong><?php echo $t->__('Fortalezas', Auth::info_usuario('idioma')); ?></strong></td>
            <td width="22%" style="padding-left:40px; background:#FEDADF;"><strong><?php echo $t->__('Debilidades', Auth::info_usuario('idioma')); ?></strong></td>
            <td width="24%" style="padding-left:40px; background:#FEDADF;"><strong><?php echo $t->__('Plan de mejoramiento', Auth::info_usuario('idioma')); ?></strong></td>
            <!--<td width="10%" style="padding-left:40px; background:#FEDADF;"><strong>Anexos</strong></td>-->
        </tr>
        <tr> 
            <td class="print" style="max-width: 10em"><?php echo ($l['fortalezas'] == null ? 'N/A' : urldecode($l['fortalezas']))?></td>
			<td class="print" style="max-width: 10em"><?php echo ($l['debilidades'] == null ? 'N/A' : urldecode($l['debilidades']))?></td>      
            <td class="print" style="max-width: 10em"><?php echo ($l['plan_mejoramiento'] == null ? 'N/A' : 
				urldecode($l['plan_mejoramiento']))?></td>
        </tr>
        <tr>
            <td colspan="3"><strong><?php echo $t->__('Calificación', Auth::info_usuario('idioma')); ?>: </strong> <?php echo ($l['desc_escala'] == null ? 'N/A' : 
				$l['desc_escala'])?></td>
        </tr>
        <tr>
            <td colspan="3" class="print"><strong><?php echo $t->__('Anexos', Auth::info_usuario('idioma')); ?>: </strong><?php 
                foreach($l['anexos'] as $a){ ?>
                    <a href="<?php echo $a['ruta']?>" target="_blank"><span data-documento="<?php echo $a['id']?>" class="label <?php echo $a['nuevo'] > 0 ? 'label-success' : 'label-primary' ?>">

                    	<i class="glyphicon glyphicon-file"></i> <?php echo $a['nombre']?></span></a><?php 
				}   ?></td>
		</tr>
        <?php if(Auth::info_usuario('ev_cna') > 0 || $evaluacion_data['ev_cna'] > 0){ ?>

        <tr>
            <td colspan="3" style="padding-left:40px; background:#dfa5a9;"><strong>Análisis de indicadores</strong></td>
        </tr>
        <tr> 
            <td width="54%" class="print" style="padding-left:40px; background:#FEDADF;"><strong>Indicador</strong></td>
            <td width="22%" class="print" style="padding-left:40px; background:#FEDADF;"><strong>Análisis</strong></td>      
            <td width="22%" class="print" style="padding-left:40px; background:#FEDADF;"><strong>Anexos</strong></td>
        </tr>
        <?php foreach($l['analisis_indicador'] as $a){ ?>
        <tr> 
            <td class="print"><?php echo $a['nom_lineamiento'] ?></td>
            <td class="print"><?php echo urldecode($a['analisis']) ?></td>      
            <td class="print">
            <?php 
                foreach($a['anexos'] as $an){ ?>
                	<a href="<?php echo $an['ruta']?>" target="_blank"><span class="label label-primary">
                    	<i class="glyphicon glyphicon-file"></i> <?php echo $an['nombre']?></span></a><?php 
				}   ?>
            </td>
        </tr>
        <?php } ?>
        <?php } ?>
        
       <?php  
	$c++;
	} ?>  
 </table>
</tbody>
</table><?php 

$i++; 
} ?>
          
            



