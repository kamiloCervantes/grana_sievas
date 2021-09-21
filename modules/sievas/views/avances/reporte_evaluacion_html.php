<style>
    td {
        padding: 5px;
    }
    
    .controls a{
        color: #000;
        font-size: 12px;
        padding: 10px;
    }
</style>
<div class="controls" style="background: #ccc; margin-top: 50px; padding: 5px">
    <form id="generate_word" method="post" action="index.php?mod=sievas&controlador=avances&accion=word_gen">
        <input type="hidden" id="word_data" name="word_data">
    </form>
    <a href="#" class="guardarword">Guardar como Word</a>
    <a href="index.php?mod=sievas&controlador=avances&accion=resultados_evaluacion">Cancelar</a>
</div>
<div class="word_version" style="border: 1px solid #ccc; background: #fff; padding: 60px;">
<canvas id="cvs" width="500" height="400" style="display: none"></canvas>
    <?php 
$i = 1;
foreach ($rubros as $r){ ?>
<strong><?php echo Auth::info_usuario('ev_cna') > 0 ? 'FACTOR ': 'RUBRO '?><?php echo "$i. ".$r['nom_lineamiento']; ?></strong><br/>

<?php 
	$c = 1;
    foreach($r['lineamientos'] as $l){ ?>
                    <strong><?= "$i.$c. $l[nom_lineamiento]"; ?></strong><br/>

<strong>Fortalezas</strong>
<p><?php echo ($l['fortalezas'] == null ? 'N/A' : urldecode($l['fortalezas']))?></p>
<strong>Debilidades</strong>
<p><?php echo ($l['debilidades'] == null ? 'N/A' : urldecode($l['debilidades']))?><p>
<strong>Plan de mejoramiento</strong>
<p><?php echo ($l['plan_mejoramiento'] == null ? 'N/A' : urldecode($l['plan_mejoramiento']))?></p>
<strong>Calificación: </strong> <?php echo ($l['desc_escala'] == null ? 'N/A' : $l['desc_escala'])?><br/>
    <?php if(Auth::info_usuario('ev_cna') > 0 || $evaluacion_data['ev_cna'] > 0){ ?>
<br/><strong>Análisis de indicadores</strong><br/>
<?php foreach($l['analisis_indicador'] as $key=>$a){ ?>
<strong><?php echo ($key+1).'. '.$a['nom_lineamiento'] ?></strong><br/>
    <p><?php echo urldecode($a['analisis']) ?></p> 
        <?php } ?>
        <?php } ?>
       <?php  
	$c++;
	} ?>  
    
    <br/>
    <p><strong>Grafindicámetro del rubro</strong></p>
<<<<<<< HEAD
    <img src="index.php?mod=sievas&controlador=avances&accion=generar_radar&rid=<?php echo $r['id'] ?>&momento=<?php echo $momento ?>" />
=======
    <img class="grafindicametro-rubros" data-datos='<?php echo $r['calificaciones'] == null ? '0,0,0,0,0,0,0,0,0,0' : implode(',',$r['calificaciones'])?>'>                  
>>>>>>> 791f598d53ac44486b288a7d0830f5957d18fa86
    <br/>
<?php 
$i++; 
} ?>
</div>
<div class="doc hide" style="border: 1px solid #ccc; background: #fff; padding: 60px;">
    <?php 
$i = 1;
foreach ($rubros as $r){ ?>
<strong><?php echo Auth::info_usuario('ev_cna') > 0 ? 'FACTOR ': 'RUBRO '?><?php echo "$i. ".$r['nom_lineamiento']; ?></strong><br/>

<?php 
	$c = 1;
    foreach($r['lineamientos'] as $l){ ?>
                    <strong><?= "$i.$c. $l[nom_lineamiento]"; ?></strong><br/>

<strong>Fortalezas</strong>
<p><?php echo ($l['fortalezas'] == null ? 'N/A' : urldecode($l['fortalezas']))?></p>
<strong>Debilidades</strong>
<p><?php echo ($l['debilidades'] == null ? 'N/A' : urldecode($l['debilidades']))?><p>
<strong>Plan de mejoramiento</strong>
<p><?php echo ($l['plan_mejoramiento'] == null ? 'N/A' : urldecode($l['plan_mejoramiento']))?></p>
<strong>Calificación: </strong> <?php echo ($l['desc_escala'] == null ? 'N/A' : $l['desc_escala'])?><br/>
    <?php if(Auth::info_usuario('ev_cna') > 0 || $evaluacion_data['ev_cna'] > 0){ ?>
<br/><strong>Análisis de indicadores</strong><br/>
<?php foreach($l['analisis_indicador'] as $key=>$a){ ?>
<strong><?php echo ($key+1).'. '.$a['nom_lineamiento'] ?></strong><br/>
    <p><?php echo urldecode($a['analisis']) ?></p> 
        <?php } ?>
        <?php } ?>
       <?php  
	$c++;
	} ?>  
    <br/>
    <p><strong>Grafindicámetro del rubro</strong></p>
    <img src="index.php?mod=sievas&controlador=avances&accion=generar_radar&rid=<?php echo $r['id'] ?>" />
    <br/>
<?php 
$i++; 
} ?>
</div>
<div class="document hide" style="border: 1px solid #ccc; background: #fff; padding: 60px;">


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
<table>
  <thead>
    <tr>
        <th colspan="3"><?php echo Auth::info_usuario('ev_cna') > 0 ? 'FACTOR ': 'RUBRO '?><?php echo "$i. ".$r['nom_lineamiento']; ?></th>
    </tr>
  </thead>

<tbody><?php 
	$c = 1;
    foreach($r['lineamientos'] as $l){ ?>
        <tr>
            <td colspan="2">
                <a style="color: #000" name="<?php echo str_replace(' ', '-', $l['nom_lineamiento']) ?>">
                    <strong><?= "$i.$c. $l[nom_lineamiento]"; ?></strong></a></td>
            
		</tr>
        
        <tr>
        	<td width="54%"><strong>Fortalezas</strong></td>
            <td width="22%"><strong>Debilidades</strong></td>
            <td width="24%"><strong>Plan de mejoramiento</strong></td>
            <!--<td width="10%" style="padding-left:40px; background:#FEDADF;"><strong>Anexos</strong></td>-->
        </tr>
        <tr> 
            <td class="print"><?php echo ($l['fortalezas'] == null ? 'N/A' : urldecode($l['fortalezas']))?></td>
			<td class="print"><?php echo ($l['debilidades'] == null ? 'N/A' : urldecode($l['debilidades']))?></td>      
            <td class="print"><?php echo ($l['plan_mejoramiento'] == null ? 'N/A' : 
				urldecode($l['plan_mejoramiento']))?></td>
        </tr>
        <tr>
            <td colspan="3"><strong>Calificación: </strong> <?php echo ($l['desc_escala'] == null ? 'N/A' : 
				$l['desc_escala'])?></td>
        </tr>
     
        <?php if(Auth::info_usuario('ev_cna') > 0 || $evaluacion_data['ev_cna'] > 0){ ?>

        <tr>
            <td colspan="3"><strong>Análisis de indicadores</strong></td>
        </tr>
        <tr> 
            <td width="54%" class="print"><strong>Indicador</strong></td>
            <td width="22%" class="print"><strong>Análisis</strong></td>      
        </tr>
        <?php foreach($l['analisis_indicador'] as $a){ ?>
        <tr> 
            <td class="print"><?php echo $a['nom_lineamiento'] ?></td>
            <td class="print"><?php echo urldecode($a['analisis']) ?></td>      

        </tr>
        <?php } ?>
        <?php } ?>
        
       <?php  
	$c++;
	} ?>  
 </table>
<?php 

$i++; 
} ?>
          
            



    
</div>