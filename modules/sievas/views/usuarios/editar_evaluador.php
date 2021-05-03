           <h4 class="sub-header">Editar evaluador</h4>
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
                    <input type="text" name="nombre" id="nombre" class="form-control" value='<?php echo $evaluador['info_personal']['nombres']?>'/>
                  </div>
                  <div class="form-group">
                    <label class="control-label">País de origen</label>
                    <input type="hidden" name="pais" id="pais" class="form-control" value='<?php echo $evaluador['info_personal']['lugar_nacimiento']?>'>                  
                  </div>
                  <div class="form-group">
                    <label class="control-label">Género</label>
                    <select name="genero" id="genero" class="form-control">
                      <option value='M' <?php echo ($evaluador['info_personal']['genero'] == 'M' ? 'selected' : '') ?>>Masculino</option>
                      <option value='F' <?php echo ($evaluador['info_personal']['genero'] == 'F' ? 'selected' : '') ?>>Femenino</option>
                    </select>
                  </div>
                   <div class="form-group">
                    <label class="control-label">Email GRANA-ISTEC</label>
                    <div class="input-group">
                        <input type="text" name="email" id="email" class="form-control" value='<?php echo substr($evaluador['info_personal']['email_grana'],0, stripos($evaluador['info_personal']['email_grana'],'@'))?>' />
                      <span class="input-group-addon">@certification-grana.org</span>
                    </div>
                    
                  </div>
                  <div class="form-group">
                    <label class="control-label">Dirección</label>
                    <input type="text" name="direccion" id="direccion" class="form-control"  value='<?php echo $evaluador['info_personal']['direccion']?>' />
                  </div>  
                  <div class="form-group">
                    <label class="control-label">Celular</label>
                    <input type="text" name="celular" id="celular" class="form-control" value='<?php echo $evaluador['info_personal']['celular']?>'/>
                  </div>
                 
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="radio-inline">
                      <input type="radio" name="tipodocumento" id="tipo1" value="1" <?php echo ($evaluador['info_personal']['cod_tipo_documento'] == '1' ? 'checked' : '') ?>> C.C.
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="tipodocumento" id="tipo2" value="2" <?php echo ($evaluador['info_personal']['cod_tipo_documento'] == '2' ? 'checked' : '') ?>> C.E.
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="tipodocumento" id="tipo1" value="3" <?php echo ($evaluador['info_personal']['cod_tipo_documento'] == '3' ? 'checked' : '') ?>> P.P.
                    </label>
                    <input type="text" name="documento" id="documento" class="form-control" placeholder="Tipo y No. de documento"  value='<?php echo $evaluador['info_personal']['nro_documento']?>'/>
                  </div>
            
                  <div class="form-group">
                    <label class="control-label">Fecha de nacimiento</label>
                    <div class="input-group">
                      <input type="text" class="form-control" data-date-format="yyyy-mm-dd" id="fecha_nacimiento" name="fecha_nacimiento" value='<?php echo $evaluador['info_personal']['fecha_nacimiento']?>' >
                      <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label">Email personal</label>
                    <input type="text" name="email_personal" id="email_personal" class="form-control"  value='<?php echo $evaluador['info_personal']['email']?>'/>
                  </div>      
                  <div class="form-group">
                    <label class="control-label">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" class="form-control"  value='<?php echo $evaluador['info_personal']['telefono']?>'/>
                  </div>    
                  <div class="form-group">
                    <label class="control-label">Skype</label>
                    <input type="text" name="skype" id="skype" class="form-control" value='<?php echo $evaluador['info_personal']['skype']?>'/>
                  </div> 
                  <div class="form-group">
                    <label class="control-label">CV</label><br/>
                    <a href="<?php echo $evaluador['info_personal']['cv_ruta']?>" target="_blank">Curriculum Vitae</a>
                  </div>
                </div>

                <div class="col-sm-4">
                    <img src="<?php echo $evaluador['info_personal']['ruta'] == null ? 'public/img/profile.jpg' : $evaluador['info_personal']['ruta'] ?>" style="border: 1px solid #ccc; width: 245px; height: 245px" id="profile-pic"  data-foto='<?php echo $evaluador['info_personal']['foto'] ?>'>
                    <br/><br/>
                    <input type="file" name="foto" id="foto"></input>
                </div>
              </div>
            </div>
            
            <!-- informacion academica -->
            <div class="panel panel-primary" id='academica'>
              <div class="panel-heading">
                <h3 class="panel-title">Información académica</h3>
              </div>
              <div class="panel-body">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label">Nivel de formación</label>
                    <input type="hidden" name="nivel_formacion" id="nivel_formacion" class="form-control" value="<?php echo $evaluador['info_academica'][0]['nivel_formacion']?>">                     
                  </div>
                  <div class="form-group">
                    <label class="control-label">Año de egreso</label>
                    <input type="text" name="anio_egreso" id="anio_egreso" class="form-control" value="<?php echo $evaluador['info_academica'][0]['anio']?>"/>
                  </div>                 
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="control-label">Título obtenido</label>
                    <input type="text" name="titulo_evaluador" id="titulo_evaluador" class="form-control" value="<?php echo $evaluador['info_academica'][0]['titulo']?>"/>
                  </div>

                  <div class="form-group">
                    <label class="control-label">Síntesis CV</label>
                    <div id="publicaciones" class="summernote_modal" data-title="Editar descripción" data-content='<?php echo $evaluador['info_academica'][0]['publicaciones']?>'>
                          <input type="text" class="form-control">
                    </div>
                  </div>   
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="control-label">Institución que otorga el título</label>
                    <input type="text" name="institucion_titulo" id="institucion_titulo" class="form-control" value="<?php echo $evaluador['info_academica'][0]['institucion']?>" />
                  </div>
                  <div class="form-group">
                    <label class="control-label">Categoría Académica</label>
                    <input type="text" name="escalafon" id="escalafon" class="form-control" value="<?php echo $evaluador['info_academica'][0]['categoria_academica']?>" />
                  </div>   
                </div>
              </div>
            </div>
             <!-- experiencia laboral -->
            <div class="panel panel-primary">
              <div class="panel-heading">
<!--              <a href="#" class="btn btn-xs btn-default pull-right add-experiencia">+ Añadir experiencia</a>       -->
                <h3 class="panel-title">Experiencia laboral</h3>
              </div>
              <div class="panel-body">
               
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label">Institución</label>
                    <div id="instituciones">
                         <?php $i = 0; foreach($evaluador['info_experiencia'] as $a){ ?>
                        <input type="text" name="exp_institucion_experiencia[]" class="form-control institucion_<?php echo $i ?>" value='<?php echo $a['institucion']?>'/>
                        <?php $i++; } $i++; ?>
                        <input type="text" name="exp_institucion_experiencia[]" class="form-control institucion_<?php echo $i ?>" />
                    </div>                    
                  </div>                                
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="control-label">Status laboral</label>
                    <div id="status_laborales">
                        <?php $i = 0; foreach($evaluador['info_experiencia'] as $a){ ?>
                        <input type="text" name="exp_status_laboral[]" class="form-control status_laboral_<?php echo $i ?>"  value='<?php echo $a['status_laboral']?>' />
                        <?php $i++; } $i++; ?>
                        <input type="text" name="exp_status_laboral[]" class="form-control status_laboral_<?php echo $i ?>" />
                    </div>                    
                  </div>                   
                </div>

                <div class="col-sm-3">
                   <div class="form-group">
                    <label class="control-label">Período</label>
                    <div id="periodos">
                        <?php $i = 0; foreach($evaluador['info_experiencia'] as $a){ ?>
                        <input type="text" name="exp_periodo[]" class="form-control periodo_<?php echo $i ?>"  value='<?php echo $a['periodo']?>' />
                        <?php $i++; } $i++; ?>
                        <input type="text" name="exp_periodo[]" class="form-control periodo_<?php echo $i ?>" />
                    </div>                    
                  </div>                            
                </div>
                  
                <div class="col-sm-1">
                   <div class="form-group">
                    <label class="control-label">&nbsp;</label>
                    <div id="botones">
                         <?php $i = 0; foreach($evaluador['info_experiencia'] as $a){ ?>
                        <span class="boton_<?php echo $i ?>" data-index="<?php echo $i ?>" style="margin-left: -15px;"><a href="#" class="btn btn-default del-row boton_<?php echo $i ?>" data-index="<?php echo $i ?>">-</a><br/></span>
                        <?php $i++; } $i++; ?>
                        <span class="boton_<?php echo $i ?>" data-index="<?php echo $i ?>" style="margin-left: -15px;"><a href="#" class="btn btn-default add-row boton_<?php echo $i ?>" data-index="<?php echo $i ?>">+</a><br/></span>
                    </div>                    
                  </div>                            
                </div>
                
                
              </div>
            </div>
             <!-- datos de acceso -->
            <div class="form-actions" style="text-align: center">
              <a class="btn btn-primary" href="#" id="guardar-form" data-evaluador='<?php echo $evaluador['info_personal']['evaluador_id'] ?>'>Guardar</a>
              <a class="btn btn-danger" href="index.php?mod=sievas&controlador=usuarios&accion=listar_evaluadores">Cancelar</a>
            </div>
          </form>
