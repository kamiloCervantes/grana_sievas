<style>
    td{
        padding: 5px;
    }
    
    .etiqueta{
        display:block;
        font-size: 12px;
        text-transform: uppercase;
        padding-bottom: 3px;
    }
    
    p.data{
        font-weight: bold;
    }
</style>
<?php //var_dump($evaluado['comite']) ?>
            <h4 class="sub-header">Información de evaluación</h4>
            <hr/>
          <br/>
          <?php switch($evaluado['tipo_evaluado']) { case '2': ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                      <h3 class="panel-title">Programa evaluado</h3>
                    </div>
                    <div class="panel-body">              
                        <table style="width:100%">
                            <tr>
                                <td>
                                    <span class="etiqueta">Nivel académico</span>
                                    <p class="data"><?php echo $evaluado['programa']['nivel_academico'] ?></p>
                                </td>
                                <td>
                                    <span class="etiqueta">Programa</span>
                                    <p class="data"><?php echo $evaluado['programa']['programa'] ?></p>
                                </td> 
                                <td>
                                    <span class="etiqueta">Adscrito a</span>
                                    <p class="data"><?php echo $evaluado['programa']['adscrito'] ?></p>
                                </td>  
                                <td>
                                    <span class="etiqueta"><?php echo $evaluado['programa']['cargo'] == null ? 'Responsable' : $evaluado['programa']['cargo']; ?></span>
                                    <p class="data"><?php echo $evaluado['programa']['nombre_director'] ?></p>
                                </td>                                
                            </tr>                   
                        </table>
                    </div>         
                  </div>
          <?php break; case '1':  ?>
          <!-- informacion basica de la institucion-->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Institución <?php echo ($evaluado['programa'] == null ? "evaluada": '');?></h3>
              </div>
              <div class="panel-body">
                  <table style="width:100%">
                      <tr>
                          <td>
                              <span class="etiqueta">Ubicación</span>
                              <p class="data"><?php echo $evaluado['institucion']['municipio'] ?>, <?php echo $evaluado['institucion']['departamento'] ?>, <?php echo $evaluado['institucion']['nom_pais'] ?></p>
                          </td>
                          <td>
                              <span class="etiqueta">Institución</span>
                              <p class="data"><?php echo $evaluado['institucion']['nom_institucion'] ?></p>
                          </td>
                          <td>
                              <span class="etiqueta">Nivel académico</span>
                              <p class="data"><?php echo $evaluado['institucion']['nivel_academico'] ?></p>
                          </td>
                          <td>
                              <span class="etiqueta">NIT</span>
                              <p class="data"><?php echo $evaluado['institucion']['nit'] ?>-<?php echo $evaluado['institucion']['dv'] ?></p>
                          </td>                      
                      </tr>                      
                      <tr>
                          <td>
                              <span class="etiqueta">Dirección</span>
                              <p class="data"><?php echo $evaluado['institucion']['direccion'] ?></p>
                          </td>    
                          <td>
                              <span class="etiqueta">Celular</span>
                              <p class="data"><?php echo $evaluado['institucion']['celular'] ?></p>
                          </td>    
                          <td>
                              <span class="etiqueta">Teléfono</span>
                              <p class="data"><?php echo $evaluado['institucion']['telefono'] ?></p>
                          </td>    
                          <td>
                              <span class="etiqueta">Fax</span>
                              <p class="data"><?php echo $evaluado['institucion']['fax'] ?></p>
                          </td>                      
                      </tr>
                      <tr>
                          <td>
                              <span class="etiqueta">Nombre corto</span>
                              <p class="data"><?php echo $evaluado['institucion']['nombre_corto'] ?></p>
                          </td>
                          <td>
                              <span class="etiqueta">Apartado aéreo</span>
                              <p class="data"><?php echo $evaluado['institucion']['apartado_aereo'] ?></p>
                          </td>
                          <td>
                              <span class="etiqueta">Web</span>
                              <p class="data"><?php echo $evaluado['institucion']['web'] ?></p>
                          </td>
                          <td>
                              <span class="etiqueta">Email</span>
                              <p class="data"><?php echo $evaluado['institucion']['email'] ?></p>
                          </td>                                    
                      </tr>
                      <tr>
                        <td>                              
                            <span class="etiqueta">Rector</span>
                             <p class="data"><?php echo $evaluado['institucion']['nombres'] ?></p>
                         </td>                     
                      </tr>
                  </table>
              </div>
            </div>
            <?php break; case '3':  ?>
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Información del profesor</h3>
              </div>
              <div class="panel-body">
                <div class="col-sm-4">
                  <div class="form-group">
                    <span class="etiqueta">Nombre</span>
                    <p class="data"><?php echo $evaluado['data_docente']['nombres']?></p>
                  </div>
                  <div class="form-group">
                    <span class="etiqueta">País de origen</span>
                    <p class="data"><?php echo $evaluado['data_docente']['nom_pais']?></p>                                     
                  </div>
                  <div class="form-group">
                    <span class="etiqueta">Género</span>
                    <p class="data"><?php echo $evaluado['data_docente']['genero']?></p>  
                  </div>                   
                  <div class="form-group">
                    <span class="etiqueta">Dirección</span>
                    <p class="data"><?php echo $evaluado['data_docente']['direccion']?></p> 
                   </div>                 
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                       <span class="etiqueta">Documento</span>
                       <p class="data"><?php echo $evaluado['data_docente']['tipo_documento'].' '.$evaluado['data_docente']['nro_documento']?></p> 
                  </div>
            
                  <div class="form-group">
                    <span class="etiqueta">Fecha de nacimiento</span>
                    <p class="data"><?php echo $evaluado['data_docente']['fecha_nacimiento']?></p> 
                  </div>                  
                  <div class="form-group">
                    <span class="etiqueta">Email personal</span>
                    <p class="data"><?php echo $evaluado['data_docente']['email']?></p> 
                  </div>      
                  <div class="form-group">
                    <span class="etiqueta">Teléfono</span>
                    <p class="data"><?php echo $evaluado['data_docente']['telefono']?></p>                     
                  </div>      
                </div>

                <div class="col-sm-4">
                    <img src="<?php echo $evaluado['data_docente']['ruta'] !== '' ? $evaluado['data_docente']['ruta'] : 'public/img/profile.jpg'?>" style="border: 1px solid #ccc; width: 245px; height: 245px" id="profile-pic">
                </div>
              </div>
            </div>
          
           <div class="panel panel-primary" id="panel_formacion_academica">
              <div class="panel-heading">
                <h3 class="panel-title">Formación académica</h3>
              </div>
              <div class="panel-body" id="formacion_academica">
                  <?php if(count($evaluado['data_formacion_academica']) === 0){  ?>
                  <p>No hay formación académica registrada.</p>
                  <?php } else { ?>
                  <?php  foreach($evaluado['data_formacion_academica'] as $idx=>$fa){?>
                  <div class="formacion_academica_item">
                      <div class="col-sm-3">
                        <div class="form-group">
                          <span class="etiqueta">Nivel de formación</span>
                          <p class="data"><?php echo $fa['nivel_formacion'] ?>  </p>                   
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="form-group">
                          <span class="etiqueta">Año de egreso</span>
                          <p class="data"><?php echo $fa['anio'] ?>  </p>  
                        </div>  
                      </div>

                      <div class="col-sm-3">
                         <div class="form-group">
                          <span class="etiqueta">Título obtenido</span>
                          <p class="data"><?php echo $fa['titulo'] ?>  </p>                          
                        </div>
                      </div>

                      <div class="col-sm-3">
                         <div class="form-group">
                            <span class="etiqueta">Institución que otorga el título</span>
                            <p class="data"><?php echo $fa['institucion'] ?>  </p>                            
                        </div>                  
                      </div>                    
                  </div>
                  <?php } ?>              
                  <?php } ?>              
              </div>
            </div>
          
          <div class="panel panel-primary">
              <div class="panel-heading">
<!--              <a href="#" class="btn btn-xs btn-default pull-right add-experiencia">+ Añadir experiencia</a>       -->
                <h3 class="panel-title">Experiencia laboral</h3>
              </div>
              <div class="panel-body" id="experiencia_laboral">
                  <?php if(count($evaluado['data_experiencia']) === 0){  ?>
                  <p>No hay formación académica registrada.</p>
                  <?php } else { ?>
                  <?php  foreach($evaluado['data_experiencia'] as $idx=>$exp){?>
                  <div class="experiencia_laboral_item">                  
                    <div class="col-sm-4">
                      <div class="form-group">
                        <span class="etiqueta">Institución</span>
                        <p class="data"><?php echo $exp['institucion'] ?>  </p>                                             
                      </div>                                
                    </div>

                    <div class="col-sm-4">
                       <div class="form-group">
                        <span class="etiqueta">Status laboral</span>
                        <p class="data"><?php echo $exp['status_laboral'] ?>  </p>                                            
                      </div>                   
                    </div>

                    <div class="col-sm-4">
                       <div class="form-group">
                        <span class="etiqueta">Período</span>
                        <p class="data"><?php echo $exp['periodo'] ?>  </p>                      
                      </div>                            
                    </div>                      
                </div>  
                  <?php } ?> 
                  <?php } ?> 
              </div>
            </div>
          <?php break; }  ?>
             <!-- informacion basica de la institucion-->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Evaluación interna</h3>
              </div>
              <div class="panel-body">
                  <table style="width:100%">
                      <tr>
                          <td>
                              <span class="etiqueta">Fecha de inicio</span>
                              <p class="data"><?php echo $evaluado['comite']['1']['1'][0]['fecha_inicia'] ?></p>
                          </td>
                          <td>
                              <span class="etiqueta">Fecha de finalización</span>
                              <p class="data"><?php echo $evaluado['comite']['1']['1'][0]['fecha_termina'] ?></p>
                          </td>
                          <td>
                              <span class="etiqueta">Coordinador</span>
                              <p class="data"><?php echo $evaluado['comite']['1']['1'][0]['nombres'].' '.$evaluado['comite']['1']['1'][0]['primer_apellido'].' '.$evaluado['comite']['1']['1'][0]['segundo_apellido'] ?></p>
                          </td>
                          <td>
                              <span class="etiqueta">Directivo</span>
                              <p class="data"><?php echo $evaluado['comite']['1']['2'][0]['nombres'].' '.$evaluado['comite']['1']['2'][0]['primer_apellido'].' '.$evaluado['comite']['1']['2'][0]['segundo_apellido'] ?></p>
                          </td>                      
                      </tr>                      
                      <tr>
                          <td>
                              <span class="etiqueta">Estudiante</span>
                              <p class="data"><?php echo $evaluado['comite']['1']['3'][0]['nombres'].' '.$evaluado['comite']['1']['3'][0]['primer_apellido'].' '.$evaluado['comite']['1']['3'][0]['segundo_apellido'] ?></p>
                          </td>    
                          <td>
                              <span class="etiqueta">Evaluador 1</span>
                              <p class="data"><?php echo $evaluado['comite']['1']['4'][0]['nombres'].' '.$evaluado['comite']['1']['4'][0]['primer_apellido'].' '.$evaluado['comite']['1']['4'][0]['segundo_apellido'] ?></p>
                          </td>    
                          <td>
                              <span class="etiqueta">Evaluador 2</span>
                              <p class="data"><?php echo $evaluado['comite']['1']['4'][1]['nombres'].' '.$evaluado['comite']['1']['4'][1]['primer_apellido'].' '.$evaluado['comite']['1']['4'][1]['segundo_apellido'] ?></p>
                          </td>    
                          <td>
                              <span class="etiqueta">Evaluador 3</span>
                              <p class="data"><?php echo $evaluado['comite']['1']['4'][2]['nombres'].' '.$evaluado['comite']['1']['4'][2]['primer_apellido'].' '.$evaluado['comite']['1']['4'][2]['segundo_apellido'] ?></p>
                          </td>                      
                      </tr>
                    
                  </table>
              </div>
            </div>
          <!-- informacion basica de la institucion-->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Evaluación externa</h3>
              </div>
              <div class="panel-body">
                  <table style="width:100%">
                      <tr>
                          <td>
                              <span class="etiqueta">Fecha de inicio</span>
                              <p class="data"><?php echo $evaluado['comite']['2']['1'][0]['fecha_inicia'] ?></p>
                          </td>
                          <td>
                              <span class="etiqueta">Fecha de finalización</span>
                              <p class="data"><?php echo $evaluado['comite']['2']['1'][0]['fecha_termina'] ?></p>
                          </td>
                          <td>
                              <span class="etiqueta">Coordinador</span>
                              <p class="data"><?php echo $evaluado['comite']['2']['1'][0]['nombres'].' '.$evaluado['comite']['2']['1'][0]['primer_apellido'].' '.$evaluado['comite']['2']['1'][0]['segundo_apellido'] ?></p>
                          </td>
                          <td>
                              <span class="etiqueta">Directivo</span>
                              <p class="data"><?php echo $evaluado['comite']['2']['2'][0]['nombres'].' '.$evaluado['comite']['2']['2'][0]['primer_apellido'].' '.$evaluado['comite']['2']['2'][0]['segundo_apellido'] ?></p>
                          </td>                      
                      </tr>                      
                      <tr>
                          <td>
                              <span class="etiqueta">Estudiante</span>
                              <p class="data"><?php echo $evaluado['comite']['2']['3'][0]['nombres'].' '.$evaluado['comite']['2']['3'][0]['primer_apellido'].' '.$evaluado['comite']['2']['3'][0]['segundo_apellido'] ?></p>
                          </td>    
                          <td>
                              <span class="etiqueta">Evaluador 1</span>
                              <p class="data"><?php echo $evaluado['comite']['2']['4'][0]['nombres'].' '.$evaluado['comite']['2']['4'][0]['primer_apellido'].' '.$evaluado['comite']['2']['4'][0]['segundo_apellido'] ?></p>
                          </td>    
                          <td>
                              <span class="etiqueta">Evaluador 2</span>
                              <p class="data"><?php echo $evaluado['comite']['2']['4'][1]['nombres'].' '.$evaluado['comite']['2']['4'][1]['primer_apellido'].' '.$evaluado['comite']['2']['4'][1]['segundo_apellido'] ?></p>
                          </td>    
                          <td>
                              <span class="etiqueta">Evaluador 3</span>
                              <p class="data"><?php echo $evaluado['comite']['2']['4'][2]['nombres'].' '.$evaluado['comite']['2']['4'][2]['primer_apellido'].' '.$evaluado['comite']['2']['4'][2]['segundo_apellido'] ?></p>
                          </td>                      
                      </tr>               
                  </table>
              </div>
            </div>



