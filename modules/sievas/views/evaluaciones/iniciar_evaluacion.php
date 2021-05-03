
            <h4 class="sub-header">Iniciar evaluación</h4>
            <hr/>
          <br/>
          <form id="formEvaluacion">
          <!-- informacion basica de la institucion-->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Asignar evaluación a evaluadores</h3>
              </div>
              <div class="panel-body">
                  <div class="row">
                    <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Evaluación</label>
                        <input type="text" name="fecha_inicia" id="fecha_inicia" class="form-control fecha">                        
                      </div>                                           
                    </div>

                    <div class="col-sm-6">       
                      <div class="form-group">
                        <label class="control-label">Momento de evaluación</label>
                        <input type="text" name="fecha_certificacion" id="fecha_certificacion" class="form-control fecha">                        
                      </div>                 
                    </div>
                  </div>
                  <div class="row">
                      <div class="col-sm-11">
                          <div class="form-group" id="evaluadores">
                            <label class="control-label">Evaluadores</label>
                            <select name="tipo_evaluado" id="tipo_evaluado" class="form-control">
                                    <option value="1">Instituciones</option>
                                    <option value="2">Programas</option>
                            </select>
                          </div>                       
                      </div>
                      <div class="col-sm-1">
                          <div class="form-group">
                            <label class="control-label">&nbsp;</label>
                            <div id="botones">
                                <span class="boton_0" data-index="0" style="margin-left: -15px;"><a href="#" class="btn btn-default add-row boton_0" data-index="0" style="padding: 3px 15px 4px 15px">+</a><br/></span>
                            </div> 
                          </div> 
                      </div>

              </div>
            </div>
            </div>
           <div class="form-actions" style="text-align: center">
              <a class="btn btn-primary" href="#" id="guardar-form">Guardar</a>
              <a class="btn btn-danger" href="#">Cancelar</a>
            </div>
           
          </form>


