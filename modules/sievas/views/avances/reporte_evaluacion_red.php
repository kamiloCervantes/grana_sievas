
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
    }    
</style>


<h4 class="sub-header"><a href="index.php?mod=sievas&controlador=avances&accion=reporte_evaluacion_pdf&momento=<?php echo $momento ?>" target="_blank">
	<i class="glyphicon glyphicon-print"></i></a>
	Informe de Evaluación
    <select id="tipo_momento">
        <option value="1">Interna</option>
        <option value="2">Externa</option>
    </select>
         de la Red
</h4>
<hr/>
<?php 
$i = 1;
foreach ($rubros_data as $r){ ?>
<table class="table print" >
  <thead>
    <tr>
        <th colspan="6">RUBRO <?php echo "$i. ".$r['rubro']['nom_lineamiento']; ?></th>
    </tr>
  </thead>

<tbody><?php 
	$c = 1;
    foreach($r['lineamientos'] as $l){ ?>
        <tr>
            <td colspan="6" align="center" style="background:#dfa5a9;" class="print">
				<strong><?php echo "$i.$c."; ?><?php echo $l['lineamiento']['nom_lineamiento']; ?></strong></td>
		</tr>
        
        <tr>
        	<td width="10%" style="padding-left:40px; background:#FEDADF;"><strong>PA</strong></td>
        	<td width="15%" style="padding-left:40px; background:#FEDADF;"><strong>Fortalezas</strong></td>
            <td width="15%" style="padding-left:40px; background:#FEDADF;"><strong>Debilidades</strong></td>
            <td width="25%" style="padding-left:40px; background:#FEDADF;"><strong>Plan de mejoramiento</strong></td>
            <td width="15%" style="padding-left:40px; background:#FEDADF;"><strong>Calificación</strong></td>
            <td width="20%" style="padding-left:40px; background:#FEDADF;"><strong>Anexos</strong></td>
            <!--<td width="10%" style="padding-left:40px; background:#FEDADF;"><strong>Anexos</strong></td>-->
        </tr>
        <?php foreach($l['resultados'] as $key=>$res){  ?>
        <tr> 
            <td class="print">
            <?php echo $res[0]['programa']?>, <?php echo $res[0]['nom_institucion']?>
            </td>
            <td class="print"><?php echo ($res[0]['fortalezas'] == null ? 'N/A' : urldecode($res[0]['fortalezas']))?></td>
			<td class="print"><?php echo ($res[0]['debilidades'] == null ? 'N/A' : urldecode($res[0]['debilidades']))?></td>      
            <td class="print"><?php echo ($res[0]['plan_mejoramiento'] == null ? 'N/A' : 
				urldecode($res[0]['plan_mejoramiento']))?></td>
            <td class="print">
            <?php echo $res[0]['desc_escala']?>
            </td>
            <td class="print">
                <a href="#" class="btn btn-default btn-sm anexos" data-lineamiento="<?php echo $l['lineamiento']['id']; ?>" data-evaluacion="<?php echo $res[0]['e_id']; ?>"><i class="glyphicon glyphicon-search"></i> Ver anexos</a>
                <div class="anexos-list"></div>
            </td>
        </tr>

        <?php } ?>
        
       <?php  
	$c++;
	} ?>  
</tbody>
</table><?php 

$i++; 
} ?>
          
            



