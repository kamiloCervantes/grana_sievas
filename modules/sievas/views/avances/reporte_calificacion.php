
<style>
    .table thead>tr>th,.table tbody>tr>th,.table tfoot>tr>th,.table thead>tr>td,.table tbody>tr>td,.table tfoot>tr>td{
        border: 1px solid #F5A9BC; 
    }
/*    
    tbody tr:nth-child(even) td{
        background: #dfa5a9;
    }
	*/
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
    Reporte de resultados de Evaluación
</h4>
<hr/>
<table class="table resultados">
    <tr>
        <td>&nbsp;</td>
    <?php 
    $k=0;
    
    foreach ($rubros[0]['lineamientos'] as $l){ ?>
        <td colspan="2" <?php echo ($k%2 == 0 ? "style='background:#dfa5a9; color: #000;text-align:center'" : "style='background:#fff; color: #000;text-align:center'")?>><?php echo $k+1; ?></td>  
    <?php $k++; }?>
        <td colspan="2" <?php echo (0 == 0 ? "style='background:#dfa5a9; color: #000;text-align:center'" : "style='background:#fff; color: #000;text-align:center'")?>>PR</td>  
    </tr>
    <tr>
        <td>&nbsp;</td>
    <?php 
    $i=0;
    foreach ($rubros[0]['lineamientos'] as $l){ ?>
        <td <?php echo ($i%2 == 0 ? "style='background:#dfa5a9; color: #000'" : "style='background:#fff; color: #000'")?>>I</td>
        <td <?php echo ($i%2 == 0 ? "style='background:#dfa5a9; color: #000'" : "style='background:#fff; color: #000'")?>>E</td> 
        
    <?php $i++; }?>
        <td <?php echo (0 == 0 ? "style='background:#dfa5a9; color: #000'" : "style='background:#fff; color: #000'")?>>I</td>
        <td <?php echo (0 == 0 ? "style='background:#dfa5a9; color: #000'" : "style='background:#fff; color: #000'")?>>E</td> 
    </tr>
     <?php $j=0; $promedios_gen = array(1 => 0, 2 => 0);foreach ($rubros as $kr=>$r){ ?>
    <tr>
     <td><?php echo $r['nom_lineamiento'] ?></td>
     <?php $promedios = array(1 => 0, 2 => 0); foreach ($r['lineamientos'] as $kl=>$l){ $promedios[1] += ($l['calificaciones'][1]['valor']>0 ? $l['calificaciones'][1]['valor'] : 0);$promedios[2] += ($l['calificaciones'][2]['valor']>0 ? $l['calificaciones'][2]['valor'] : 0);?>
        <td <?php echo ($j%2 == 0 ? "style='background:#dfa5a9; color: #000'" : "style='background:#fff; color: #000'")?> data-coord="<?php echo $kr.','.$kl ?>" data-tipo="1" data-momento="1">  <?php echo ($l['calificaciones'][1]['valor']>0 ? $l['calificaciones'][1]['valor'] : '-') ?></td>
        <td <?php echo ($j%2 == 0 ? "style='background:#dfa5a9; color: #000'" : "style='background:#fff; color: #000'")?> data-coord="<?php echo $kr.','.$kl ?>" data-tipo="1" data-momento="2"><?php echo ($l['calificaciones'][2]['valor']>0 && $momento == 2 || $rol == 1 ? $l['calificaciones'][2]['valor'] : '-') ?></td>
     <?php $j++;}?>
        <td data-coord="<?php echo $kr.',PR' ?>" data-tipo="2" data-momento="1" <?php echo (0 == 0 ? "style='background:#dfa5a9; color: #000'" : "style='background:#fff; color: #000'")?>><?php 

        if($evaluaciones_esp[$e_id] > 0 && $r['id'] == '12'){
            echo round(($promedios[1]/8), 2); 
        }
        else{
            echo round(($promedios[1]/10), 2); 
        }
        
        ?></td>
        <td data-coord="<?php echo $kr.',PR' ?>" data-tipo="2" data-momento="2" <?php echo (0 == 0 ? "style='background:#dfa5a9; color: #000'" : "style='background:#fff; color: #000'")?>><?php 
//        var_dump($r['id']);
        if($evaluaciones_esp[$e_id] > 0 && $r['id'] == '12'){
            echo $momento == 2 || $rol == 1 ? round(($promedios[2]/8), 2): '-'; 
        }
        else{
            echo $momento == 2 || $rol == 1 ? round(($promedios[2]/10), 2): '-'; 
        }
        ?>
        </td>
    </tr>
    <?php $promedios_gen[1] += $promedios[1]; $promedios_gen[2] += $promedios[2]; }?>
    <tr>
        <td colspan="21">&nbsp;</td>
        <td class="pr-gen" data-tipo="3" data-momento="1" <?php echo (0 == 0 ? "style='background:#dfa5a9; color: #000'" : "style='background:#fff; color: #000'")?>><?php 
        
        if($evaluaciones_esp[$e_id] > 0){
            echo round(($promedios_gen[1]/98), 2); 
        }
        else{
            echo round(($promedios_gen[1]/100), 2); 
        }
        
        ?>
        </td>
        <td class="pr-gen" data-tipo="3" data-momento="2" <?php echo (0 == 0 ? "style='background:#dfa5a9; color: #000'" : "style='background:#fff; color: #000'")?>><?php 
        
        
         if($evaluaciones_esp[$e_id] > 0){
            echo $momento == 2 || $rol == 1  ? round(($promedios_gen[2]/98), 2): '-'; 
        }
        else{
            echo $momento == 2 || $rol == 1  ? round(($promedios_gen[2]/100), 2): '-'; 
        }
        ?></td>
    </tr>
</table>    
 
          
            



