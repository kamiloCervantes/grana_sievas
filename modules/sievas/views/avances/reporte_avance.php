
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


<h4 class="sub-header"><a href="#" id="imprimir">
	<i class="glyphicon glyphicon-print"></i></a> 
    Avances Evaluación 
    <select id="tipo_momento" style="width:120px" data-evaluacion="<?php echo $_GET['evaluacion']?>">
        <option value="1">Interna</option>
        <option value="2">Externa</option>
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
        <td colspan="2" style="text-align:center"><a href="index.php?mod=sievas&controlador=avances&accion=monitor_avances" class="btn btn-default btn-xs">Cambiar evaluación</a></td>
    </tr>
</table>

<?php } ?>
    
    <?php 

$i = 1; 
foreach ($rubros as $r){ ?>
<table class="table print" >
  <thead>
  <tr><td colspan="7">RUBRO <?php echo "$i. ".$r['nom_lineamiento']; ?></td></tr>
  </thead>
    <tr>
        <th width="40%" height="30" class="print">Lineamiento</th>     
        <th width="10%" class="print">Fortalezas</th>
        <th width="10%" class="print">Debilidades</th>           
        <th width="10%" class="print">Plan de mejoras</th>
        <th width="10%" class="print">Anexos</th>
        <th width="10%" class="print">Validación</th>
        <th width="10%" class="print">Retroalimentación</th>
    </tr>

<tbody><?php 
	$c = 1;
    foreach($r['lineamientos'] as $l){?>
        <tr>
            <td class="print"><?php echo "$i.$c. $l[nom_lineamiento]"; ?></td>
            <td align="center" class="print"><?php echo ($l['fortalezas'] == 0 ? 
				'<i class="glyphicon glyphicon-remove" style="color:red"></i>' : 
				'<i class="glyphicon glyphicon-ok" style="color:green"></i>')?></td>    
            <td align="center" class="print"><?php echo ($l['debilidades'] == 0 ? 
				'<i class="glyphicon glyphicon-remove" style="color:red"></i>' : 
				'<i class="glyphicon glyphicon-ok" style="color:green"></i>')?></td>           
            <td align="center" class="print"><?php echo ($l['plan_mejoramiento'] == 0 ? 
				'<i class="glyphicon glyphicon-remove" style="color:red"></i>' : 
				'<i class="glyphicon glyphicon-ok" style="color:green"></i>')?></td>
            <td align="center" class="print"><?php echo ($l['anexos'] == 0 ? 
				'<i class="glyphicon glyphicon-remove" style="color:red"></i>' : 
				'<i class="glyphicon glyphicon-ok" style="color:green"></i>')?></td>
            <td align="center" class="print"><?php echo ($l['validacion'] > 0 ? 
				'<i class="glyphicon glyphicon-ok" style="color:green"></i>' : 
				'<i class="glyphicon glyphicon-remove" style="color:red"></i>')?></td>
            <td align="center" class="print"><?php echo ($l['retroalimentacion'] > 0 ? 
				'<i class="glyphicon glyphicon-ok" style="color:green"></i>' : 
				'<i class="glyphicon glyphicon-remove" style="color:red"></i>')?></td>
        </tr><?php 
		$c++;
	} 
	
	$i++; ?>  
</tbody>
</table><?php 

} ?>
          
            



