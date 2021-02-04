
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

<h4 class="sub-header"><a href="#" target="_blank">
	<i class="glyphicon glyphicon-print"></i></a> Avances</h4>
<hr/>

<table class='table'>
  <thead>
    <tr>
        <th width="7%" height="40">Tipo</th>     
        <th width="33%">Lineamiento</th>
        <th width="12%">Estadísticas</th>
        <th width="12%">Fortalezas</th>           
        <th width="12%">Debilidades</th>
        <th width="12%">Acciones</th>
        <th width="12%">Anexos</th>
    </tr>
  </thead>

<tbody><?php 

if($linea != null){ 
	foreach($linea as $key=>$c){?>
      <tr>
          <td><?php echo $c['tipo_lineamiento'] ?></td>
          <td><?php echo $c['nom_lineamiento'] ?></td>
          <td><?php if ($c['estadistica'] != null){ ?>
			  	<img src="public/img/logos/flag_green.png" alt="Estadísticas ingresadas"/><?php
			  } ?></td>
          <td><?php if ($c['fortalezas'] != null){ ?>
			  	<img src="public/img/logos/flag_green.png" alt="Fortalezas diligenciadas"/><?php
			  } ?></td>
          <td><?php if ($c['debilidades'] != null){ ?>
			  	<img src="public/img/logos/flag_green.png" alt="Debilidades diligenciadas"/><?php
			  } ?></td>
          <td><?php if ($c['acciones'] != null){ ?>
			  	<img src="public/img/logos/flag_green.png" alt="Acciones diligenciadas"/><?php
			  } ?></td>
          <td><?php if ($c['anexos'] != null){ ?>
			  	<img src="public/img/logos/flag_green.png" alt="Anexos cargados"/><?php
			  } ?></td>
      </tr><?php 
   }
} ?>   
</tbody>
</table>

          

          
            



