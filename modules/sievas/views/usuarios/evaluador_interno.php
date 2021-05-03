
 
           <h4 class="sub-header">Crear evaluador interno</h4>
           <hr/>
          <br/>
          <form>
          <!-- informacion personal -->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Información personal</h3>
              </div>
              <div class="panel-body">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label class="control-label">País de origen</label>
                    <select name="pais" id="pais" class="form-control">
                      <option>Seleccione una opción...</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="control-label">Género</label>
                    <select name="genero" id="genero" class="form-control">
                      <option>Seleccione una opción...</option>
                      <option>Masculino</option>
                      <option>Femenino</option>
                    </select>
                  </div>
                   <div class="form-group">
                    <label class="control-label">Email GRANA-ISTEC</label>
                    <input type="text" name="email" id="email" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label class="control-label">Dirección</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" />
                  </div>                 
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="radio-inline">
                      <input type="radio" name="tipodocumento" id="tipo1" value="1"> C.C.
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="tipodocumento" id="tipo2" value="2"> C.E.
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="tipodocumento" id="tipo1" value="3"> P.P.
                    </label>
                    <input type="text" name="documento" id="documento" class="form-control" placeholder="Tipo y No. de documento"/>
                  </div>
            
                  <div class="form-group">
                    <label class="control-label">Fecha de nacimiento</label>
                    <div class="input-group">
                      <input type="text" class="form-control">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                  </div>
                   <div class="form-group">
                    <label class="control-label">Estado civil</label>
                    <input type="text" name="email" id="email" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label class="control-label">Email personal</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" />
                  </div>      
                  <div class="form-group">
                    <label class="control-label">Teléfono</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" />
                  </div>      
                </div>

                <div class="col-sm-4">
                  <img src="public/img/profile.jpg" style="border: 1px solid #ccc">
                </div>
              </div>
            </div>
            <!-- informacion academica -->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Información académica</h3>
              </div>
              <div class="panel-body">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label">Nivel de formación</label>
                    <select name="pais" id="pais" class="form-control">
                      <option>Seleccione una opción...</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="control-label">Año de egreso</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" />
                  </div>                 
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="control-label">Título obtenido</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label class="control-label">Publicaciones y artículos</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" />
                  </div>   
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="control-label">Institución que otorga el título</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label class="control-label">Escalafón</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" />
                  </div>   
                </div>
              </div>
            </div>
             <!-- experiencia laboral -->
            <div class="panel panel-primary">
              <div class="panel-heading">
              <a href="#" class="btn btn-xs btn-default pull-right">+ Añadir experiencia</a>       
                <h3 class="panel-title">Experiencia laboral</h3>

              </div>
              <div class="panel-body">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label">Institución</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" />
                  </div>                                
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="control-label">Status laboral</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" />
                  </div>                   
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="control-label">Período</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" />
                  </div>                            
                </div>
              </div>
            </div>
            <div class="form-actions" style="text-align: center">
              <a class="btn btn-primary" href="#">Guardar</a>
              <a class="btn btn-danger" href="#">Cancelar</a>
            </div>
          </form>