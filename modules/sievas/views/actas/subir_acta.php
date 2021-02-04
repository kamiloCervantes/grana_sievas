<style>
    td{
        padding: 5px;
    }
    
    .etiqueta{
        display:block;
        font-size: 12px;
        text-transform: uppercase;
        padding-bottom: 3px;
    }
    
    p.data{
        font-weight: bold;
    }
</style>
<?php //var_dump($evaluado['comite']) ?>
            <h4 class="sub-header">Subir acta</h4>
            <hr/>
          <br/>
                <div class="panel panel-default">
                    <div class="panel-body">  
                        <div class="form-group col-sm-6">
                            <label class="control-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" />
                          </div> 
                        <div class="form-group col-sm-6">
                            <label class="control-label">Radicación</label>
                            <input type="text" name="radicacion" id="radicacion" class="form-control">                        
                          </div>
                        <div class="form-group col-sm-6">
                            <label class="control-label">Fecha</label>
                            <input type="text" name="fecha" id="fecha" class="form-control">                        
                          </div>
                          
                          <div class="form-group col-sm-6">
                            <label class="control-label">Soporte acta</label>
                            <input type="file" name="soporte" id="soporte" />
                          </div>
                        
                        <div class="form-actions col-sm-12" style="text-align: center">
                            <a class="btn btn-primary" href="#" id="guardar-form">Guardar</a>
                            <a class="btn btn-danger" href="#">Cancelar</a>
                          </div>
                    </div>         
                  </div>
