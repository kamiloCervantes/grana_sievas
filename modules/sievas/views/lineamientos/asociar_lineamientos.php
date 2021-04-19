<style>
	.ventanaAgregar .modal-dialog{
		width: 90%;
	}
        
        .modal70 > .modal-dialog {
            width:70% !important;
        }
</style> 


<h4>Asociar conjuntos de lineamientos</h4>
<hr/>

<div class="row">
	<!-- Arbol de lineamientos -->
    <div class="col-sm-6">
       <label class="control-label">Conjunto 1</label>
       <input type="hidden" name="conjunto_lineamientos" id="conjunto_lineamientos" class="form-control" value="<?php echo $cod_conjunto ?>" />
       <br/>
       <br/>
       <div id="arbol" class="arbol"></div>
    </div>

    <!-- Formulario -->
    <div class="col-sm-6">
        <label class="control-label">Conjunto 2</label>
        <input type="hidden" name="conjunto_lineamientos2" id="conjunto_lineamientos2" class="form-control"  value="<?php echo $cod_conjunto ?>" />
       <br/>
       <br/>
        <div id="arbol2" class="arbol"></div>
           
    </div>
</div>
<br/>
<div class="controls" style="text-align: center;">
    <a href="#" class="btn btn-primary" id="agregar_asoc">Agregar asociación</a>
</div>

<br/>
<h4>Asociaciones  <span class="label label-danger eliminar_asociaciones" style="font-size: 10px; cursor: pointer"><i class="glyphicon glyphicon-trash"></i> Eliminar todas</span></h4>

<table class="table" id="asociaciones">
    <thead>
    <tr>
        <th>Item Conjunto 1</th>
        <th>Item Conjunto 2</th>
    </tr>
    </thead>
    <tbody>
        
    </tbody>
</table>