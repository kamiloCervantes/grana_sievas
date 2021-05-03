<style>
    .table thead>tr>th,.table tbody>tr>th,.table tfoot>tr>th,.table thead>tr>td,.table tbody>tr>td,.table tfoot>tr>td{
        border: 1px solid #F5A9BC; 
    }
    
    tbody tr:nth-child(even) td{
        background: #fedbdb;
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

<h4>
 Gráficas de tablas estadísticas
</h4>
<hr/>
<div class="controls">
    <button class="btn btn-default" id="graficar">Graficar</button>
    <button class="btn btn-default" id="restablecer">Restablecer lista</button>
    
    <select name="filtro" id="filtro" style="width: 250px" class="pull-right">
        <option value="0">Filtrar lista...</option>
        <?php foreach($tablas as $tab){  ?>
        <option value="<?php echo $tab['id'] ?>"><?php echo $tab['nombre'] ?></option>
        <?php } ?>
    </select>
    
</div>
<br/>
<table class="table" style="border-top: 1px solid #F5A9BC">
    <thead>
        <tr>
            <th>Indicador</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($indicadores as $ind){  ?>
        <tr data-tabla="<?php echo $ind['cod_tablaestadistica'] ?>">
            <td><?php echo $ind['nombre_indicador'] ?></td>
            <td><input type="checkbox" name="indicadores" value="<?php echo $ind['id'] ?>"></td>
        </tr>
        <?php } ?>
    </tbody>
    
</table>