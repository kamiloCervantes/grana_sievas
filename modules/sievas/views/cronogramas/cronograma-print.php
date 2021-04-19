
<head>
<style>
    .table thead>tr>th,.table tbody>tr>th,.table tfoot>tr>th,.table thead>tr>td,.table tbody>tr>td,.table tfoot>tr>td{
        border: 1px solid #FCCDD9; 
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
</head>

<body onLoad="window.print()">
<h4 class="sub-header">Cronograma de evaluación</h4>
<hr/>


<table class='table'>
  <thead>
    <tr>
        <th width="27%" height="40">Actividad</th>     
        <th width="7%">Inicia</th>
        <th width="7%">Finaliza</th>           
        <th width="22%">Medio</th>
        <th width="22%">Notas</th>
        <th width="15%">Responsable(s)</th>
    </tr>
  </thead>

<tbody><?php 

if($cronograma != null){ 
	foreach($cronograma as $key=>$c){?>
      <tr>
          <td><?php echo ($key+1).'. '.$c['actividad'] ?></td>
          <td><?php echo $c['fecha_inicia'] ?></td>
          <td><?php echo $c['fecha_fin'] ?></td>
          <td><?php echo $c['medio'] ?></td>
          <td><?php echo $c['anotaciones'] ?></td>
          <td><ul class="lista"><?php foreach($c['responsables'] as $r){ ?>
                  <li><?php echo "$r[titulo] $r[nombres] $r[primer_apellido] $r[segundo_apellido]"; } ?>    
              </ul></td>
      </tr><?php 
   }
} ?>   
</tbody>
</table>

</body>
          

          
            



