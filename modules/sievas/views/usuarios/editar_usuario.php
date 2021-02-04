
          <h4 class="sub-header">Editar usuario</h4>
          <hr/>
          <br/>
          <form id="addEvaluador" autocomplete="on">
          <!-- informacion personal -->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Información personal</h3>
              </div>
              <div class="panel-body">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo $usuario['nombres'] ?>"/>
                  </div>
                  <div class="form-group">
                    <label class="control-label">País de origen</label>
                    <input type="hidden" name="pais" id="pais" class="form-control"  value="<?php echo $usuario['lugar_nacimiento'] ?>">                  
                  </div>
                  <div class="form-group">
                    <label class="control-label">Género</label>
                    <select name="genero" id="genero" class="form-control">
                      <option <?php echo ($usuario['genero'] === 'M' ? "selected" : "") ?>>Masculino</option>
                      <option <?php echo ($usuario['genero'] === 'F' ? "selected" : "") ?>>Femenino</option>
                    </select>
                  </div>
                   
                  <div class="form-group">
                    <label class="control-label">Dirección</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" value="<?php echo $usuario['direccion'] ?>"/>
                  </div>        
                  <div class="form-group">
                    <label class="control-label">Celular</label>
                    <input type="text" name="celular" id="celular" class="form-control"  value="<?php echo $usuario['celular'] ?>"/>
                  </div>
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="radio-inline">
                      <input type="radio" name="tipodocumento" id="tipo1" value="1" <?php echo ($usuario['cod_tipo_documento'] === '1' ? "checked" : "") ?>> C.C.
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="tipodocumento" id="tipo2" value="2" <?php echo ($usuario['cod_tipo_documento'] === '2' ? "checked" : "") ?>> C.E.
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="tipodocumento" id="tipo1" value="3" <?php echo ($usuario['cod_tipo_documento'] === '3' ? "checked" : "") ?>> P.P.
                    </label>
                    <input type="text" name="documento" id="documento" class="form-control" placeholder="Tipo y No. de documento"  value="<?php echo $usuario['direccion'] ?>"/>
                  </div>
            
                  <div class="form-group">
                    <label class="control-label">Fecha de nacimiento</label>
                    <div class="input-group">
                      <input type="text" class="form-control" data-date-format="yyyy-mm-dd" id="fecha_nacimiento" name="fecha_nacimiento"  value="<?php echo $usuario['fecha_nacimiento'] ?>">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label">Email personal</label>
                    <input type="text" name="email_personal" id="email_personal" class="form-control"  value="<?php echo $usuario['email'] ?>"/>
                  </div>      
                  <div class="form-group">
                    <label class="control-label">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" class="form-control"  value="<?php echo $usuario['telefono'] ?>"/>
                  </div>      
                  <div class="form-group">
                    <label class="control-label">Skype</label>
                    <input type="text" name="skype" id="skype" class="form-control"  value="<?php echo $usuario['skype'] ?>"/>
                  </div>      
                </div>

                <div class="col-sm-4">
                    <img src="<?php echo $usuario['ruta'] ?>" style="border: 1px solid #ccc; width: 245px; height: 245px" id="profile-pic">
                    <br/><br/>
                    <input type="file" name="foto" id="foto"></input>
                </div>
              </div>
            </div>
           
            
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Asignación de roles</h3>
              </div>
              <div class="panel-body">
                <?php foreach($roles as $r){ ?>
                  <div class="rol_check" style="display:block">
                      <input type="checkbox" name="rol" value="<?php echo $r['id'] ?>" class="rol"> <?php echo $r['idrol'] ?>
                  </div>
                  
                <?php } ?>
              </div>
            </div>

            <div class="form-actions" style="text-align: center">
              <a class="btn btn-primary" href="#" id="guardar-form">Guardar</a>
              <a class="btn btn-danger" href="index.php?mod=sievas&controlador=usuarios&accion=listar_evaluadores">Cancelar</a>
            </div>
          </form>