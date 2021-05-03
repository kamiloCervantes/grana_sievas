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
    td{
        font-size: 12px;
		text-align:center;

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
    
    #ex1Slider .slider-selection, #ex2Slider .slider-selection, #ex3Slider .slider-selection, #ex4Slider .slider-selection,
    #ex5Slider .slider-selection, #ex6Slider .slider-selection, #ex7Slider .slider-selection, #ex8Slider .slider-selection,
    #ex9Slider .slider-selection, #ex10Slider .slider-selection{
	background: #BABABA;
    }

</style>
<h4>
    Tablero de control :: <?php echo $evaluacion ?>
</h4>   
<hr/>
<?php switch($err){
    case 1:?>
<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  Debe indicar que la conformación del comité evaluador se ha completado
</div>
<?php break; ?>
 <?php   case 2:?>
<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  El ingreso de la evaluacion interna ya se ha completado
</div>
<?php break; ?>
 <?php   case 3:?>
<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  Debe completar la información del Comité de Evaluación Externa
</div>
<?php break; ?>
<?php } ?>
<table class="table" id="control">
    <thead>
        <tr>
            <th>Número de proceso</th>
            <th>Descripción de proceso</th>
            <th>Avance (0-10)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Conformación del comité evaluador interno </td>
            <td style="padding: 20px">
                <input id="proceso1" data-proceso="1" class="slider" data-slider-id='ex1Slider' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo $etapas[1]['escala_avance'] > 0 ? $etapas[1]['escala_avance']: 0 ?>"/></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Integración de la evaluación interna </td>
            <td style="padding: 20px"><input id="proceso2" data-proceso="2" class="slider" data-slider-id='ex2Slider' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo $etapas[2]['escala_avance'] > 0 ? $etapas[2]['escala_avance']: 0 ?>"/></td>
        </tr>
        <tr>
            <td>3</td>
            <td>Información de evaluadores externos </td>
            <td style="padding: 20px"><input id="proceso3" data-proceso="3" class="slider" data-slider-id='ex3Slider' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo $etapas[3]['escala_avance'] > 0 ? $etapas[3]['escala_avance']: 0 ?>"/></td>
        </tr>
        <tr>
            <td>4</td>
            <td>Evaluación externa (primera etapa a distancia)</td>
            <td style="padding: 20px"><input id="proceso4" data-proceso="4" class="slider" data-slider-id='ex4Slider' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo $etapas[4]['escala_avance'] > 0 ? $etapas[4]['escala_avance']: 0 ?>"/></td>
        </tr>
        <tr>
            <td>5</td>
            <td>Visita de evaluadores externos a la institución </td>
            <td style="padding: 20px"><input id="proceso5" data-proceso="5" class="slider" data-slider-id='ex5Slider' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo $etapas[5]['escala_avance'] > 0 ? $etapas[5]['escala_avance']: 0 ?>"/></td>
        </tr>
        <tr>
            <td>6</td>
            <td>Entrega de predictamen </td>
            <td style="padding: 20px"><input id="proceso6" data-proceso="6" class="slider" data-slider-id='ex6Slider' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo $etapas[6]['escala_avance'] > 0 ? $etapas[6]['escala_avance']: 0 ?>"/></td>
        </tr>
        <tr>
            <td>7</td>
            <td>Evaluación externa (segunda etapa a distancia)</td>
            <td style="padding: 20px"><input id="proceso7" data-proceso="7" class="slider" data-slider-id='ex7Slider' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo $etapas[7]['escala_avance'] > 0 ? $etapas[7]['escala_avance']: 0 ?>"/></td>
        </tr>
        <tr>
            <td>8</td>
            <td>Entrega de resultados de la evaluación externa</td>
            <td style="padding: 20px"><input id="proceso8" data-proceso="8" class="slider" data-slider-id='ex8Slider' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo $etapas[8]['escala_avance'] > 0 ? $etapas[8]['escala_avance']: 0 ?>"/></td>
        </tr>
        <tr>
            <td>9</td>
            <td>Proceso de mejora continua </td>
            <td style="padding: 20px"><input id="proceso9" data-proceso="9" class="slider" data-slider-id='ex9Slider' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo $etapas[9]['escala_avance'] > 0 ? $etapas[9]['escala_avance']: 0 ?>"/></td>
        </tr>
        <tr>
            <td>10</td>
            <td>Inicio de reevaluación </td>
            <td style="padding: 20px"><input id="proceso10" data-proceso="10" class="slider" data-slider-id='ex10Slider' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo $etapas[10]['escala_avance'] > 0 ? $etapas[10]['escala_avance']: 0 ?>"/></td>
        </tr> 
    </tbody>
</table>