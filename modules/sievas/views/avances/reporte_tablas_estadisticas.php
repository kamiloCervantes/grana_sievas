
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
   Resumen tablas estadísticas
</h4>
<hr/>


<table class="table">
    <thead>
        <tr>
            <th>Rubro</th>
            <th>Tablas estadísticas</th>
        </tr>
    </thead>
    
    <tbody>
        <?php $i=0; foreach ($rubros as $r){ $i++; ?>
        <tr>
            <td><?php echo $i ?>. <?php echo $r['nom_lineamiento'] ?></td>
            <td>
              <?php  foreach($r['anexos'] as $a){ ?>
                	<a href="<?php echo $a['ruta']?>" target="_blank"><span class="label label-primary">
                    	<i class="glyphicon glyphicon-file"></i> <?php echo $a['nombre']?></span></a><?php 
				}   ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
    
</table> 
          
            



