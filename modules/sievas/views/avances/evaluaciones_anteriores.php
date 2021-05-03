
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


<h4>Evaluaciones anteriores</h4>
<hr/>
            

<table class="table">
    <thead>
    <tr>
        <th>Evaluación</th>
        <th>Opciones</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($anteriores as $a){ ?>
    <tr>
        <td><?php echo $a['etiqueta'] ?></td>
        <td><a href="<?php echo $a['ruta'] ?>" target="_blank">Descargar "<?php echo $a['nombre']?>"</a></td>
    </tr>
    <?php } ?>
    </tbody>
</table>

