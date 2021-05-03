
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


<h4 class="sub-header">
    Validación de avances 
</h4>
<hr/>
    
<div id="selector_evaluacion">
    <div class="col-sm-3">&nbsp;</div>
    <div class="col-sm-6">
        <form method="post" id="reporte_avance">
            <div class="form-group">
                <label>País</label>
                <input type="hidden" class="form-control" id="pais">     
            </div>
            <div class="form-group">
                <label>Institución</label>
                <input type="hidden" class="form-control" id="institucion"> 
            </div>
            <div class="form-group">
                <label>Programa</label>
                <input type="hidden" class="form-control" id="programa">
            </div>
            <div class="form-group">
                <label>Evaluación</label>
                <input type="hidden" class="form-control" id="evaluacion">
            </div>
            <div class="form-actions" style="text-align: center">
                <br/>
                <input type="submit" value="Ver reporte" class="btn btn-primary"/>
            </div>
        </form>
        
    </div>
    <div class="col-sm-3">&nbsp;</div>
    
</div> 
    
 
    
            



