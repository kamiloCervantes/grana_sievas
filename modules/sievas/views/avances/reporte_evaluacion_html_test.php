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
    <?php if(!(Auth::info_usuario('ev_cna') > 0 || $evaluacion_data['ev_cna'] > 0)){ ?>
    <a href="index.php?mod=sievas&controlador=avances&accion=reporte_evaluacion_pdf&momento=<?php echo $momento ?>" class="guardarpdf">Guardar como PDF</a>
    <?php } ?>
    <a href="#" class="imprimir">Imprimir</a>
    <a href="index.php?mod=sievas&controlador=avances&accion=resultados_evaluacion">Cancelar</a>
</div>

<div class="doc" style="border: 1px solid #ccc; background: #fff; padding: 60px;">
    <?php 
$i = 1;
$calificacion_rubros = 0;
$rubros_i = 0;
foreach ($rubros as $r){
    $rubro_calificaciones = 0;
    $items_i= 0;
    $rubros_i++;
    ?>
    <h2><strong><?php echo Auth::info_usuario('ev_cna') > 0 ? 'FACTOR ': 'RUBRO '?><?php echo "$i. ".$r['nom_lineamiento']; ?></strong></h2><br/>
    
<?php 
	$c = 1;
    foreach($r['lineamientos'] as $l){ 
      $items_calificaciones = 0;  
      $evaluaciones_i = 0;  
      
     $data = array(
     'fortalezas' => '',
     'debilidades' => '',
     'plan_mejoramiento' => '',
     'puntuaciones' => ''
 ) ?>
         <?php if($l['comun'] > 0){  $items_i++;?>       
                  <h2><strong><?= "$i.$c. $l[nom_lineamiento]"; ?></strong></h2><br/>
 <?php foreach($l['evaluacion_data'] as $ed){ 

     $items_calificaciones += $ed['valor_escala']; 
     $evaluaciones_i++;
     $data['fortalezas'] .= ($ed['fortalezas'] == null ? 'N/A<br/>' : 'Se percibe que '.urldecode($ed['fortalezas']));
     $data['debilidades'] .= ($ed['debilidades'] == null ? 'N/A<br/>' : 'Se observa que '.urldecode($ed['debilidades']));
     $data['plan_mejoramiento'] .= ($ed['plan_mejoramiento'] == null ? 'N/A<br/>' : 'Se propone que '.urldecode($ed['plan_mejoramiento']));
     $data['puntuaciones'] .= $ed['programa'].' : '.($ed['desc_escala'] == null ? 'N/A' : $ed['desc_escala']).'<br/>';
     ?>
 
 

<?php } ?>   
 <strong>Fortalezas observadas</strong> <br/>
 <?php echo $data['fortalezas']?> 
<strong>Debilidades observadas</strong> <br/>
<?php echo $data['debilidades']?>
<strong>Plan de mejoramiento propuesto</strong> <br/>
<?php echo $data['plan_mejoramiento']?>
<strong>Calificaciones</strong> <br/>
<?php echo $data['puntuaciones']?><br/>
<strong>Calificación promedio del ítem: <?php $rubro_calificaciones += $items_calificaciones/$evaluaciones_i; echo $items_calificaciones/$evaluaciones_i ?></strong>  <br/>

<?php } else { ?> 


<?php } ?> 
<br/>
       <?php  
	$c++;
	} ?>  
<br/>
<strong>Calificación promedio del rubro: <?php $calificacion_rubros += $rubro_calificaciones/$items_i; echo $rubro_calificaciones/$items_i ?> </strong>  <br/>
<?php 
$i++; 
} ?>

<h3>Calificación promedio de la evaluación: <?php echo $calificacion_rubros/$rubros_i ?></h3>  
</div>
