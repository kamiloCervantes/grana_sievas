<style>
    .popover{
       width: 360px !important;
    }
</style>
            <h4 class="sub-header">Agregar categoría de observatorio</h4>
            <hr/>
          <br/>
          <form id="formCategoria" autocomplete="on">
          
            <!-- rector o maxima autoridad de la institucion -->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Datos de categoría</h3>
              </div>
              <div class="panel-body">               
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" name="nom_categoria" id="nom_categoria" class="form-control"/>
                  </div>  
                </div>
                  
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Tipo de categoría</label>
                    <input type="text" name="tipo_categoria" id="tipo_categoria" class="form-control"/>                                      
                  </div>                      
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                      <label class="control-label"><span id="asociado_label">Asociado a</span></label>
                    <input type="text" name="asociado_a" id="asociado_a" class="form-control"/>                      
                  </div>  
                </div>               
              </div>         
            </div>            
           
            <div class="form-actions" style="text-align: center">
              <a class="btn btn-primary" href="#" id="guardar-form">Guardar</a>
              <a class="btn btn-danger" href="index.php?mod=sievas&controlador=observatorio&accion=categorias">Cancelar</a>
            </div>
          </form>



