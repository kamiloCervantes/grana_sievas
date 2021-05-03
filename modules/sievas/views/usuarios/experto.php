         
           <?php if(Auth::info_usuario('rol') > 0){ ?>
           <h4 class="sub-header">Crear experto</h4>
           <?php } else{ ?>
           <h4 class="sub-header">Registro de experto</h4>
           <?php } ?>
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
                    <input type="text" name="nombre" id="nombre" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label class="control-label">País de origen</label>
                    <input type="hidden" name="pais" id="pais" class="form-control">                  
                  </div>
                  <div class="form-group">
                    <label class="control-label">Género</label>
                    <select name="genero" id="genero" class="form-control">
                      <option>Masculino</option>
                      <option>Femenino</option>
                    </select>
                  </div>
                   <div class="form-group">
                    <label class="control-label">Email GRANA-ISTEC</label>
                    <div class="input-group">
                      <input type="text" name="email" id="email" class="form-control" />
                      <span class="input-group-addon">@certification-grana.org</span>
                    </div>
                    
                  </div>
                  <div class="form-group">
                    <label class="control-label">Dirección</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" />
                  </div>        
                  <div class="form-group">
                    <label class="control-label">Celular</label>
                    <input type="text" name="celular" id="celular" class="form-control" />
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
                    <label class="radio-inline">
                      <input type="radio" name="tipodocumento" id="tipo1" value="4"> I.D.
                    </label>
                    <input type="text" name="documento" id="documento" class="form-control" placeholder="Tipo y No. de documento" />
                  </div>
            
                  <div class="form-group">
                    <label class="control-label">Fecha de nacimiento</label>
                    <div class="input-group">
                      <input type="text" class="form-control" data-date-format="yyyy-mm-dd" id="fecha_nacimiento" name="fecha_nacimiento">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                  </div>
                   <div class="form-group">
                    <label class="control-label">Estado civil</label>
                    <input type="hidden" name="estado_civil" id="estado_civil" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label class="control-label">Email personal</label>
                    <input type="text" name="email_personal" id="email_personal" class="form-control" />
                  </div>      
                  <div class="form-group">
                    <label class="control-label">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" class="form-control" />
                  </div>      
                  <div class="form-group">
                    <label class="control-label">Skype</label>
                    <input type="text" name="skype" id="skype" class="form-control" />
                  </div>      
                </div>

                <div class="col-sm-4">
                    <img src="public/img/profile.jpg" style="border: 1px solid #ccc; width: 245px; height: 245px" id="profile-pic">
                    <br/><br/>
                    <input type="file" name="foto" id="foto"></input>
                </div>
              </div>
            </div>
            <!-- tipo evaluador -->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Datos de usuario</h3>
              </div>
              <div class="panel-body">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label">Nombre de usuario</label>
                    <input type="text" name="nombre_usuario" id="nombre_usuario" class="form-control" />
                  </div>                         
                </div>

                <div class="col-sm-4">
<!--                    <div class="form-group">
                        <label class="control-label">Tipo de evaluador</label>
                        <select name="tipo_evaluador" id="tipo_evaluador" class="form-control">
                            <option value="interno">Evaluador interno</option>
                            <option value="externo">Evaluador externo</option>
                        </select>                     
                    </div>     -->
                </div>

                <div class="col-sm-4">
<!--                    <div class="form-group">
                        <label class="control-label">Cargo</label>
                        <input type="hidden" name="cargo_evaluador" id="cargo_evaluador" class="form-control">                      
                    </div>-->
                </div>
              </div>
            </div>
            <!-- informacion academica -->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Máximo nivel de estudios</h3>
              </div>
              <div class="panel-body">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label">Nivel de formación</label>
                    <input type="hidden" name="nivel_formacion" id="nivel_formacion" class="form-control">                     
                  </div>
                  <div class="form-group">
                    <label class="control-label">Año de egreso</label>
                    <input type="text" name="anio_egreso" id="anio_egreso" class="form-control" />
                  </div>                 
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="control-label">Título obtenido</label>
                    <input type="text" name="titulo_evaluador" id="titulo_evaluador" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label class="control-label">Síntesis CV</label>
                    <div id="publicaciones" class="summernote_modal" data-title="Editar descripción">
                          <input type="text" class="form-control">
                    </div>
                  </div>   
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="control-label">Institución que otorga el título</label>
                    <input type="text" name="institucion_titulo" id="institucion_titulo" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label class="control-label">Categoría Académica</label>
                    <input type="text" name="escalafon" id="escalafon" class="form-control" />
                  </div>   
                </div>
              </div>
            </div>
             <!-- experiencia laboral -->
            <div class="panel panel-primary">
              <div class="panel-heading">
<!--              <a href="#" class="btn btn-xs btn-default pull-right add-experiencia">+ Añadir experiencia</a>       -->
                <h3 class="panel-title">Institución donde labora</h3>
              </div>
              <div class="panel-body">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label">Institución</label>
                    <div id="instituciones">
                        <input type="text" name="exp_institucion_experiencia[]" class="form-control institucion_0" />
                    </div>                    
                  </div>                                
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="control-label">Status laboral (Cargo)</label>
                    <div id="status_laborales">
                        <input type="text" name="exp_status_laboral[]" class="form-control status_laboral_0" />
                    </div>                    
                  </div>                   
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="control-label">Período</label>
                    <div id="periodos">
                        <input type="text" name="exp_periodo[]" class="form-control periodo_0" />
                    </div>                    
                  </div>                            
                </div>
                  
<!--                <div class="col-sm-1">
                   <div class="form-group">
                    <label class="control-label">&nbsp;</label>
                    <div id="botones">
                        <span class="boton_0" data-index="0" style="margin-left: -15px;"><a href="#" class="btn btn-default add-row boton_0" data-index="0" title="Añadir nueva experiencia laboral">+</a><br/></span>
                    </div>                    
                  </div>                            
                </div>-->
              </div>
            </div>
             <!-- datos de acceso -->
<!--            <div class="panel panel-primary">
              <div class="panel-heading">    
                <h3 class="panel-title">Datos de acceso</h3>

              </div>
              <div class="panel-body">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label">Nombre de usuario</label>
                    <input type="text" name="nombre_usuario" id="nombre_usuario" class="form-control" />
                  </div>                                
                </div>

                <div class="col-sm-4">
                   &nbsp;                 
                </div>

                <div class="col-sm-4">
                   &nbsp;                            
                </div>
              </div>
            </div>-->
            <div class="form-actions" style="text-align: center">
              <a class="btn btn-primary" href="#" id="guardar-form">Guardar</a>
              <a class="btn btn-danger" href="index.php?mod=sievas&controlador=usuarios&accion=listar_evaluadores">Cancelar</a>
            </div>
          </form>