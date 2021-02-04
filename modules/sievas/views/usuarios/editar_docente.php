    
           <h4 class="sub-header">Editar docente</h4>
           <hr/>
          <br/>
          <form id="addProfesor" autocomplete="on">
          <!-- informacion personal -->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Información personal</h3>
              </div>
              <div class="panel-body">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo $data_docente['nombres'] ?>"/>
                  </div>
                  <div class="form-group">
                    <label class="control-label">País de origen</label>
                    <input type="hidden" name="pais" id="pais" class="form-control" value="<?php echo $data_docente['pais_institucion'] ?>">                  
                  </div>
                  <div class="form-group">
                    <label class="control-label">Género</label>
                    <select name="genero" id="genero" class="form-control">
                      <option value='M' <?php echo ($data_docente['genero'] == 'M' ? 'selected' : '') ?>>Masculino</option>
                      <option value='F' <?php echo ($data_docente['genero'] == 'F' ? 'selected' : '') ?>>Femenino</option>
                    </select>
                  </div>                   
                  <div class="form-group">
                    <label class="control-label">Dirección</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" value="<?php echo $data_docente['direccion'] ?>" />
                  </div>                 
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="radio-inline">
                      <input type="radio" name="tipodocumento" id="tipo1" value="1" <?php echo ($data_docente['cod_tipo_documento'] == '1' ? 'checked' : '') ?>> C.C.
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="tipodocumento" id="tipo2" value="2" <?php echo ($data_docente['cod_tipo_documento'] == '2' ? 'checked' : '') ?>> C.E.
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="tipodocumento" id="tipo1" value="3" <?php echo ($data_docente['cod_tipo_documento'] == '3' ? 'checked' : '') ?>> P.P.
                    </label>
                    <input type="text" name="documento" id="documento" class="form-control" placeholder="Tipo y No. de documento" value="<?php echo $data_docente['nro_documento'] ?>" />
                  </div>
            
                  <div class="form-group">
                    <label class="control-label">Fecha de nacimiento</label>
                    <div class="input-group">
                      <input type="text" class="form-control" data-date-format="yyyy-mm-dd" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $data_docente['fecha_nacimiento'] ?>" >
                      <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                  </div>                  
                  <div class="form-group">
                    <label class="control-label">Email personal</label>
                    <input type="text" name="email_personal" id="email_personal" class="form-control"  value="<?php echo $data_docente['email'] ?>" />
                  </div>      
                  <div class="form-group">
                    <label class="control-label">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" class="form-control"  value="<?php echo $data_docente['telefono'] ?>" />
                  </div>      
                </div>

                <div class="col-sm-4">
                    <img src="<?php echo $data_docente['ruta'] !== '' ? $data_docente['ruta'] : 'public/img/profile.jpg'?>" style="border: 1px solid #ccc; width: 245px; height: 245px" id="profile-pic">
                    <br/><br/>
                    <input type="file" name="foto" id="foto"></input>
                </div>
              </div>
            </div>
            <!-- datos usuario -->
           <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Datos de usuario</h3>
              </div>
              <div class="panel-body">              
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Pais</label>
                        <input type="hidden" name="pais_docente" id="pais_docente" class="form-control"  value="<?php echo $data_docente['pais_institucion'] ?>">                      
                    </div>     
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Institución</label>
                        <input type="hidden" name="institucion" id="institucion" class="form-control" value="<?php echo $data_docente['institucion_id'] ?>">                      
                    </div>     
                </div>

              </div>
            </div>
            <!-- informacion academica -->
            <div class="panel panel-primary" id="panel_formacion_academica">
              <div class="panel-heading">
                <h3 class="panel-title">Formación académica</h3>
              </div>
              <div class="panel-body" id="formacion_academica">
                  <?php if(count($data_formacion_academica) === 0){ ?>
                   <div class="formacion_academica_item">
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label class="control-label">Nivel de formación</label>
                          <input type="hidden" name="nivel_formacion" class="form-control nivel_formacion" value="<?php echo $fa['cod_nivel_formacion'] ?>">                     
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="control-label">Año de egreso</label>
                          <input type="text" name="anio_egreso" class="form-control anio_egreso" value="<?php echo $fa['anio'] ?>"/>
                        </div>  
                      </div>


                      <div class="col-sm-3">
                         <div class="form-group">
                          <label class="control-label">Título obtenido</label>
                          <input type="text" name="titulo_profesor" class="form-control titulo_profesor" value="<?php echo $fa['titulo'] ?>" />
                        </div>
                      </div>

                      <div class="col-sm-3">
                         <div class="form-group">
                          <label class="control-label">Institución que otorga el título</label>
                          <input type="text" name="institucion_titulo" class="form-control institucion_titulo"  value="<?php echo $fa['institucion'] ?>"/>
                        </div>                  
                      </div>
                      <div class="col-sm-1">
                         <div class="form-group">
                          <label class="control-label">&nbsp;</label>
                          <div>
                              <span class="formacion_academica_0" data-index="0" style="margin-left: -15px;"><a href="#" class="btn btn-default add-row-fa formacion_academica_0" data-index="0">+</a><br/></span>
                          </div>                    
                        </div>                            
                      </div>                        
                  </div>
                  <?php } else { ?>
                  <?php  foreach($data_formacion_academica as $idx=>$fa){?>
                  <div class="formacion_academica_item">
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label class="control-label">Nivel de formación</label>
                          <input type="hidden" name="nivel_formacion" class="form-control nivel_formacion" value="<?php echo $fa['cod_nivel_formacion'] ?>">                     
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class="control-label">Año de egreso</label>
                          <input type="text" name="anio_egreso" class="form-control anio_egreso" value="<?php echo $fa['anio'] ?>"/>
                        </div>  
                      </div>


                      <div class="col-sm-3">
                         <div class="form-group">
                          <label class="control-label">Título obtenido</label>
                          <input type="text" name="titulo_profesor" class="form-control titulo_profesor" value="<?php echo $fa['titulo'] ?>" />
                        </div>
                      </div>

                      <div class="col-sm-3">
                         <div class="form-group">
                          <label class="control-label">Institución que otorga el título</label>
                          <input type="text" name="institucion_titulo" class="form-control institucion_titulo"  value="<?php echo $fa['institucion'] ?>"/>
                        </div>                  
                      </div>
                      <?php if($idx == (count($data_formacion_academica)-1)){ ?>
                      <div class="col-sm-1">
                         <div class="form-group">
                          <label class="control-label">&nbsp;</label>
                          <div>
                              <span class="formacion_academica_0" data-index="0" style="margin-left: -15px;"><a href="#" class="btn btn-default add-row-fa formacion_academica_0" data-index="0">+</a><br/></span>
                          </div>                    
                        </div>                            
                      </div>  
                      <?php } else{ ?>
                      <div class="col-sm-1">
                         <div class="form-group">
                          <label class="control-label">&nbsp;</label>
                          <div>
                              <span class="formacion_academica_0" data-index="0" style="margin-left: -15px;"><a href="#" class="btn btn-default del-row-fa formacion_academica_0" data-index="0">-</a><br/></span>
                          </div>                    
                        </div>                            
                      </div>  
                      <?php } ?>
                  </div>
                  <?php } ?>              
                  <?php } ?>              
              </div>
            </div>

<!--            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Seminarios, talleres y cursos</h3>
              </div>
              <div class="panel-body" id="otra_formacion">
                  <div class="otra_formacion_item">
                      <div class="col-sm-11">
                        <div class="form-group">
                          <label class="control-label">&nbsp;</label>
                          <textarea name="otra_formacion" class="form-control otra_formacion"></textarea>                     
                        </div>
                      </div>             

                      <div class="col-sm-1">
                         <div class="form-group">
                          <label class="control-label">&nbsp;</label>
                          <div>
                              <span class="otra_formacion_0" data-index="0" style="margin-left: -15px;"><a href="#" class="btn btn-default add-row-of otra_formacion_0" data-index="0">+</a><br/></span>
                          </div>                    
                        </div>                            
                      </div>
                  </div>
                
                  
              </div>
            </div>-->
             <!-- experiencia laboral -->
            <div class="panel panel-primary">
              <div class="panel-heading">
<!--              <a href="#" class="btn btn-xs btn-default pull-right add-experiencia">+ Añadir experiencia</a>       -->
                <h3 class="panel-title">Experiencia laboral</h3>
              </div>
              <div class="panel-body" id="experiencia_laboral">
                  <?php if(count($data_experiencia) === 0){ ?>
                  <div class="experiencia_laboral_item">                  
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="control-label">Institución</label>
                        <div id="instituciones">
                            <input type="text" name="institucion_exp_laboral" class="form-control institucion_exp_laboral" value="<?php echo $exp['institucion'] ?>" />
                        </div>                    
                      </div>                                
                    </div>

                    <div class="col-sm-4">
                       <div class="form-group">
                        <label class="control-label">Status laboral</label>
                        <div id="status_laborales">
                            <input type="text" name="status_laboral" class="form-control status_laboral" value="<?php echo $exp['status_laboral'] ?>"  />
                        </div>                    
                      </div>                   
                    </div>

                    <div class="col-sm-3">
                       <div class="form-group">
                        <label class="control-label">Período</label>
                        <div id="periodos">
                            <input type="text" name="periodo" class="form-control periodo" value="<?php echo $exp['periodo'] ?>"  />
                        </div>                    
                      </div>                            
                    </div>
                    <div class="col-sm-1">
                       <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <div id="botones">
                            <span class="boton_0" data-index="0" style="margin-left: -15px;"><a href="#" class="btn btn-default add-row boton_0" data-index="0">+</a><br/></span>
                        </div>                    
                      </div>                            
                    </div>
                  </div>  
                  <?php } else { ?>
                  <?php  foreach($data_experiencia as $idx=>$exp){?>
                  <div class="experiencia_laboral_item">                  
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="control-label">Institución</label>
                        <div id="instituciones">
                            <input type="text" name="institucion_exp_laboral" class="form-control institucion_exp_laboral" value="<?php echo $exp['institucion'] ?>" />
                        </div>                    
                      </div>                                
                    </div>

                    <div class="col-sm-4">
                       <div class="form-group">
                        <label class="control-label">Status laboral</label>
                        <div id="status_laborales">
                            <input type="text" name="status_laboral" class="form-control status_laboral" value="<?php echo $exp['status_laboral'] ?>"  />
                        </div>                    
                      </div>                   
                    </div>

                    <div class="col-sm-3">
                       <div class="form-group">
                        <label class="control-label">Período</label>
                        <div id="periodos">
                            <input type="text" name="periodo" class="form-control periodo" value="<?php echo $exp['periodo'] ?>"  />
                        </div>                    
                      </div>                            
                    </div>
                     <?php if($idx == (count($data_experiencia)-1)){ ?>
                    <div class="col-sm-1">
                       <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <div id="botones">
                            <span class="boton_0" data-index="0" style="margin-left: -15px;"><a href="#" class="btn btn-default add-row boton_0" data-index="0">+</a><br/></span>
                        </div>                    
                      </div>                            
                    </div>
                    <?php } else {?> 
                    <div class="col-sm-1">
                       <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <div id="botones">
                            <span class="boton_0" data-index="0" style="margin-left: -15px;"><a href="#" class="btn btn-default del-row boton_0" data-index="0">-</a><br/></span>
                        </div>                    
                      </div>                            
                    </div>  
                    <?php } ?>  
                </div>  
                  <?php } ?> 
                  <?php } ?> 
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
              <a class="btn btn-primary" href="#" id="guardar-form" data-docente="<?php echo $data_docente['id'] ?>">Guardar</a>
              <a class="btn btn-danger" href="#">Cancelar</a>
            </div>
          </form>