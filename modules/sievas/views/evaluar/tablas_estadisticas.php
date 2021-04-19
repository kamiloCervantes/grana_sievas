
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
    
    .popover{
        max-width: 750px;
    }
</style>


<h4 class="sub-header">    
	Tablas estadísticas :: <?php echo $evaluacion ?>
</h4>
<hr/>

<?php 
$i = 1;
switch(Auth::info_usuario('tipo_evaluado')){
    case 10:
        foreach ($rubros as $r){ ?>
        <a class="btn btn-default" href="index.php?mod=sievas&controlador=evaluar&accion=tablas_estadisticas_gestor&r=<?php echo $r['id']; ?>" role="button">RUBRO <?php echo "$i. ".$r['nom_lineamiento']; ?></a><br/><br/>
        <?php  $i++;   } 
        break;
    default:
        foreach ($rubros as $r){ ?>
   <?php     if($i == 10){ ?>
     <a class="btn btn-default" href="index.php?mod=sievas&controlador=evaluar&accion=tablas_estadisticas_gestor&r=<?php echo $r['id']; ?>&subtabla=1" role="button">RUBRO <?php echo "$i. ".$r['nom_lineamiento']; ?> Subtabla 1</a><br/><br/>
     <a class="btn btn-default" href="index.php?mod=sievas&controlador=evaluar&accion=tablas_estadisticas_gestor&r=<?php echo $r['id']; ?>&subtabla=2" role="button">RUBRO <?php echo "$i. ".$r['nom_lineamiento']; ?> Subtabla 2</a><br/><br/>
     <a class="btn btn-default" href="index.php?mod=sievas&controlador=evaluar&accion=tablas_estadisticas_gestor&r=<?php echo $r['id']; ?>&subtabla=3" role="button">RUBRO <?php echo "$i. ".$r['nom_lineamiento']; ?> Subtabla 3</a><br/><br/>
  <?php      } else{ ?>
        <a class="btn btn-default" href="index.php?mod=sievas&controlador=evaluar&accion=tablas_estadisticas_gestor&r=<?php echo $r['id']; ?>" role="button">RUBRO <?php echo "$i. ".$r['nom_lineamiento']; ?></a><br/><br/>
   <?php     } ?>
        
<?php $i++; } 
        break;
}?>
