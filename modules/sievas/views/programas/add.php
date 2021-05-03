<style>
    .popover{
       width: 360px !important;
    }
</style>
            <h4 class="sub-header"><?php echo $titulo == null ? 'Crear programa educativo': $titulo?></h4>
            <hr/>
          <br/>
          <form id="formPrograma" autocomplete="on">
          <!-- Ficha básica del programa -->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Ficha básica del programa</h3>
              </div>
              <div class="panel-body">
                  <div class="row">
                        <div class="form-group col-sm-4">
                         <label class="control-label">Programa</label>
                         <input type="text" name="programa" id="programa" class="form-control" value="<?php echo $programa['programa']?>">
                       </div>

                       <div class="form-group col-sm-4">
                         <label class="control-label">Adscrito a</label>
                         <input type="text" name="adscrito" id="adscrito" class="form-control" value="<?php echo $programa['adscrito']?>" >
                       </div>

                       <div class="form-group col-sm-4">
                         <label class="control-label">Nivel académico</label>
                         <input type="hidden" name="nivel_academico" id="nivel_academico" class="form-control" value="<?php echo $programa['cod_nivel_academico']?>">
                       </div>
                   </div> 
                   <div class="row">
                
                    <div class="form-group col-sm-4">
                    <label class="control-label">Área de conocimiento</label>
                    <input type="hidden" name="area_conocimiento" id="area_conocimiento" class="form-control" value="<?php echo $programa['cod_area']?>">
                  </div>
                   
                  <div class="form-group col-sm-4">
                    <label class="control-label">Núcleo</label>
                    <input type="hidden" name="area_nucleo" id="area_nucleo" class="form-control" value="<?php echo $programa['cod_nucleo']?>">
                  </div>                  
                  
                  <div class="form-group col-sm-4">
                    <label class="control-label">Responsable</label>
                    <input type="text" name="director" id="director" class="form-control" value="<?php echo $programa['nombre_director']?>" >
                  </div>
                   </div>
                  <div class="row">
                <div class="form-group col-sm-4">
                    <label class="control-label">Título del responsable</label>
                    <div class="input-group">                        
                        <input type="hidden" name="cargo_responsable" id="cargo_responsable" class="form-control" data-tipo_cargo="1"  value="<?php echo $programa['cod_cargo_director']?>" />
                        <span class="input-group-addon" id="agregar-cargo"><i class="glyphicon glyphicon-plus-sign"></i></span>
                     </div>
                  </div>
                  
                  <div class="form-group col-sm-4">
                    <label class="control-label">Pais</label>
                    <input type="hidden" name="pais" id="pais" class="form-control" value="<?php echo $programa['cod_pais']?>">
                  </div>
                  
                   <div class="form-group col-sm-4">
                    <label class="control-label">Institución</label>
                    <div class="input-group">                        
                        <input type="hidden" name="institucion" id="institucion" class="form-control" value="<?php echo $programa['cod_institucion']?>">
                        <span class="input-group-addon"><a href="index.php?mod=sievas&controlador=instituciones&accion=agregar" target="_blank"><i class="glyphicon glyphicon-plus-sign"></i></a></span>
                     </div>
                  </div>
                  </div>
                    
                  
              </div>
            </div>
            
            <div class="form-actions" style="text-align: center">
              <a class="btn btn-primary" href="#" id="guardar-form" data-id='<?php echo $programa['id']?>'>Guardar</a>
              <a class="btn btn-danger" href="index.php?mod=sievas&controlador=programas&accion=index">Cancelar</a>
            </div>
          </form>