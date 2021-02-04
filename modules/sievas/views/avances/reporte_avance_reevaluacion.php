
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
    Avances Reevaluación 
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
foreach ($rubros as $r){  ?>
<table class="table print" >
  <?php if($r['padre_lineamiento'] == 0){ ?>
  <thead>
  <tr><td colspan="10">RUBRO <?php echo $r['num_orden'].'. '.$r['nom_lineamiento']; ?></td></tr>
  </thead>
    <tr>
        <th width="37%" height="30" class="print">Lineamiento</th>     
        <th width="7%" class="print">Fort. opción</th>
        <th width="7%" class="print">Fort. data</th>
        <th width="7%" class="print">Deb. opcion</th>           
        <th width="7%" class="print">Deb. data</th>           
        <th width="7%" class="print">PDM opcion</th>
        <th width="7%" class="print">PDM data</th>
        <th width="7%" class="print">Cal.</th>
        <th width="7%" class="print">Val.</th>
        <th width="7%" class="print">Retr.</th>
        
    </tr>
    <tbody>
  <?php } else { ?>
<?php 
	$c = 1;
 ?>
        <tr>
            <td class="print" width="37%"><?php echo $r['atributos_lineamiento'].$r['num_orden'].' '.$r['nom_lineamiento']; ?></td>
            <td align="center" class="print"><?php echo ($r['fortalezas_opcion'] == null ? 
				'<i class="glyphicon glyphicon-remove" style="color:red"></i>' : 
				'<i class="glyphicon glyphicon-ok" style="color:green"></i>')?></td>    
            <td align="center" class="print"><?php echo ($r['fortalezas_data'] == null ? 
				'<i class="glyphicon glyphicon-remove" style="color:red"></i>' : 
				'<i class="glyphicon glyphicon-ok" style="color:green"></i>')?></td>    
            <td align="center" class="print"><?php echo ($r['debilidades_opcion'] == null ? 
				'<i class="glyphicon glyphicon-remove" style="color:red"></i>' : 
				'<i class="glyphicon glyphicon-ok" style="color:green"></i>')?></td>           
            <td align="center" class="print"><?php echo ($r['debilidades_data'] == null ? 
				'<i class="glyphicon glyphicon-remove" style="color:red"></i>' : 
				'<i class="glyphicon glyphicon-ok" style="color:green"></i>')?></td>           
            <td align="center" class="print"><?php echo ($r['planesmejora_opcion'] == null ? 
				'<i class="glyphicon glyphicon-remove" style="color:red"></i>' : 
				'<i class="glyphicon glyphicon-ok" style="color:green"></i>')?></td>
            <td align="center" class="print"><?php echo ($r['planesmejora_data'] == null ? 
				'<i class="glyphicon glyphicon-remove" style="color:red"></i>' : 
				'<i class="glyphicon glyphicon-ok" style="color:green"></i>')?></td>
            <td align="center" class="print"><?php echo ($r['cod_gradacion_escala'] == null ? 
				'<i class="glyphicon glyphicon-remove" style="color:red"></i>' : 
				'<i class="glyphicon glyphicon-ok" style="color:green"></i>')?></td>
            <td align="center" class="print"><?php echo ($r['validacion'] > 0 ? 
				'<i class="glyphicon glyphicon-ok" style="color:green"></i>' : 
				'<i class="glyphicon glyphicon-remove" style="color:red"></i>')?></td>
            <td align="center" class="print"><?php echo ($r['retroalimentacion'] > 0 ? 
				'<i class="glyphicon glyphicon-ok" style="color:green"></i>' : 
				'<i class="glyphicon glyphicon-remove" style="color:red"></i>')?></td>
            
            
        </tr><?php 
		$c++;
	
	
   } ?> 
        
</tbody>
</table><?php 

} ?>
          
            



