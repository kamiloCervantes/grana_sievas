
            <h4 class="sub-header">Editar institución educativa</h4>
            <hr/>
          <br/>
          <form id="formInstitucion"  autocomplete="on">
          <!-- informacion basica de la institucion-->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Información básica de la institución</h3>
              </div>
              <div class="panel-body">
                <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label">Pais</label>
                    <input type="hidden" name="pais" id="pais" class="form-control" value='<?php echo $institucion['cod_pais'] ?>'>
                  </div>                 
                  <div class="form-group">
                    <label class="control-label">Estado/Departamento</label>
                    <input type="hidden" name="departamento" id="departamento" class="form-control"  value='<?php echo $institucion['cod_departamento'] ?>'>                    
                  </div> 
                  <div class="form-group">
                    <label class="control-label">Teléfono</label>
                    
                    <div class="row">
                        <div class="col-sm-3"><input type="text" name="indicativo" id="indicativo" class="form-control"   value='<?php echo $institucion['indicativo'] ?>' /></div>
                        <div class="col-sm-3"><input type="text" name="indicativo_comp" id="indicativo_comp" class="form-control"   value='<?php echo $institucion['indicativo_comp'] ?>' /></div>
                        <div class="col-sm-6"><input type="text" name="telefono" id="telefono" class="form-control"   value='<?php echo $institucion['telefonos'] ?>'/></div>
                    </div>
                    
                  </div>
                  <div class="form-group">
                    <label class="control-label">Web</label>
                    <input type="text" name="web" id="web" class="form-control" value='<?php echo $institucion['web'] ?>' />
                  </div>                                
                </div>

                <div class="col-sm-4">               
                  <div class="form-group">
                    <label class="control-label">Institución</label>
                    <input type="text" name="nom_institucion" id="nom_institucion" class="form-control" value='<?php echo $institucion['nom_institucion'] ?>' />
                  </div>
                   <div class="form-group">
                    <label class="control-label">Nombre corto</label>
                    <input type="text" name="nombre_corto" id="nombre_corto" class="form-control"  value='<?php echo $institucion['nombre_corto'] ?>'/>
                  </div>
                  <div class="form-group">
                    <label class="control-label">Ciudad</label>
                    <input type="hidden" name="municipio" id="municipio" class="form-control"  value='<?php echo $institucion['cod_municipio'] ?>'>                      
                  </div>     
                  <div class="form-group">
                    <label class="control-label">Fax</label>
                    <input type="text" name="fax" id="fax" class="form-control" value='<?php echo $institucion['fax'] ?>'/>
                  </div>
                   <div class="form-group">
                    <label class="control-label">E-mail</label>
                    <input type="text" name="email" id="email" class="form-control" value='<?php echo $institucion['institucion_email'] ?>'/>
                  </div>     
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="control-label">Nivel académico</label>
                    <input type="hidden" name="nivel_academico" id="nivel_academico" class="form-control" value='<?php echo $institucion['cod_nivel_academico'] ?>'>                      
                  </div>
                  <div class="form-group">
                    <label class="control-label">Dirección</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" value='<?php echo $institucion['direccion'] ?>' />
                  </div>
                  <div class="form-group">
                    <label class="control-label">Celular</label>
                    <input type="text" name="celular" id="celular" class="form-control"  value='<?php echo $institucion['celular'] ?>'/>
                  </div>
                   <div class="form-group">
                    <label class="control-label">Apartado aéreo</label>
                    <input type="text" name="aa" id="aa" class="form-control"  value='<?php echo $institucion['apartado_aereo'] ?>'/>
                  </div> 
                </div>
              </div>
            </div>
            <!-- rector o maxima autoridad de la institucion -->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Rector o máxima autoridad de la institución</h3>
              </div>
              <div class="panel-body">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" name="nombre_rector" id="nombre_rector" class="form-control"   value='<?php echo $institucion['nombre_rector']?>'/>
                  </div>
                  <div class="form-group">
                    <label class="control-label">Nivel de formación</label>
                    <input type="hidden" name="nivel_formacion" id="nivel_formacion" class="form-control" value='<?php echo $institucion['cod_nivel_formacion'] ?>' />
                  </div>  
                  <div class="form-group">
                    <label class="control-label">Teléfono</label>
                    <input type="text" name="telefono_rector" id="telefono_rector" class="form-control"  value='<?php echo $institucion['telefono_rector'] ?>'/>
                  </div>                
                </div>

                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="radio-inline">
                      <input type="radio" name="tipodocumento" id="tipo1" value="1" <?php echo ($institucion['cod_tipo_documento'] == '1' ? 'checked' : '') ?>> C.C.
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="tipodocumento" id="tipo2" value="2" <?php echo ($institucion['cod_tipo_documento'] == '2' ? 'checked' : '') ?>> C.E.
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="tipodocumento" id="tipo3" value="3" <?php echo ($institucion['cod_tipo_documento'] == '3' ? 'checked' : '') ?>> P.P.
                    </label>
                    <input type="text" name="documento" id="documento" class="form-control" placeholder="Tipo y No. de documento" value='<?php echo $institucion['nro_documento'] ?>'/>
                  </div>
                   <div class="form-group">
                    <label class="control-label">Título obtenido</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" value='<?php echo $institucion['titulo'] ?>'/>
                  </div>
                  <div class="form-group">
                    <label class="control-label">Email</label>
                    <input type="text" name="email_rector" id="email_rector" class="form-control" value='<?php echo $institucion['email'] ?>'/>
                  </div>   
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="control-label">País de origen</label>
                    <input type="hidden" name="pais_origen" id="pais_origen" class="form-control" value='<?php echo $institucion['lugar_nacimiento'] ?>'>
                  </div>
                  <div class="form-group">
                    <label class="control-label">Institución que otorga el título</label>
                    <input type="text" name="institucion" id="institucion" class="form-control" value='<?php echo $institucion['institucion'] ?>' />
                  </div>
                  <div class="form-group">
                    <label class="control-label">Período</label>
                    <input type="text" name="periodo" id="periodo" class="form-control"  value='<?php echo $institucion['periodo'] ?>'/>
                  </div>   
                </div>
              </div>         
            </div>
            <div class="form-actions" style="text-align: center">
              <a class="btn btn-primary" href="#" id="guardar-form" data-id='<?php echo $institucion['id'] ?>'>Guardar</a>
              <a class="btn btn-danger" href="index.php?mod=sievas&controlador=instituciones">Cancelar</a>
            </div>
          </form>


