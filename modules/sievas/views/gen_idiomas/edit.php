
          <h4 class="sub-header">Crear idioma</h4>
          <hr/>
          <br/>
          <form id="formPais"  autocomplete="on">
              <div class="row">            
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">Idioma</label>
                    <input type="text" name="idioma" id="idioma" class="form-control" value="<?php echo $idioma['nombre']?>"/>
                  </div>                          
                </div>
          </div>
            <br/>
            <div class="form-actions" style="text-align: center">
              <a class="btn btn-primary" href="#" id="guardar-idioma">Guardar</a>
              <a class="btn btn-danger" href="#">Cancelar</a>
            </div>
            <input type="hidden" name="idioma_id" id="idioma_id" value="<?php echo $_GET['id'] ?>" />
          </form>

<?php 
