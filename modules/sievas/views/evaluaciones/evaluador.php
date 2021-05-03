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
            <h4 class="sub-header">Información del evaluador</h4>
            <hr/>
          <br/>
          <!-- informacion basica de la institucion-->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Información personal</h3>
              </div>
              <div class="panel-body">
                  <table>
                      <tr>                          
                          <td width="75%">
                              <table width="100%">
                                <tr>
                                    <td>
                                        <span class="etiqueta">Nombre</span>
                                        <p class="data"><?php echo $evaluador['info_personal']['nombres'].' '.$evaluador['info_personal']['primer_apellido'].' '.$evaluador['info_personal']['segundo_apellido'] != '' ? $evaluador['info_personal']['nombres'].' '.$evaluador['info_personal']['primer_apellido'].' '.$evaluador['info_personal']['segundo_apellido'] : 'N/A' ?></p>
                                    </td>
                                    <td>
                                        <span class="etiqueta">Documento</span>
                                        <p class="data"><?php echo $evaluador['info_personal']['abreviatura'].' '.$evaluador['info_personal']['nro_documento'] != '' ? $evaluador['info_personal']['abreviatura'].' '.$evaluador['info_personal']['nro_documento'] : 'N/A' ?></p>
                                    </td>
                                    <td>
                                        <span class="etiqueta">País de origen</span>
                                        <p class="data"><?php echo $evaluador['info_personal']['nom_pais'] != '' ? $evaluador['info_personal']['nom_pais'] : 'N/A' ?></p>
                                    </td>                                        
                                </tr>                      
                                <tr>
                                    <td>
                                        <span class="etiqueta">Fecha de nacimiento</span>
                                        <p class="data"><?php echo $evaluador['info_personal']['fecha_nacimiento'] ? $evaluador['info_personal']['fecha_nacimiento'] : 'N/A' ?></p>
                                    </td>
                                    <td>
                                        <span class="etiqueta">Género</span>
                                        <p class="data"><?php echo ($evaluador['info_personal']['genero'] == 'F' ? 'Femenino':'Masculino') ?></p>
                                    </td>                                                                       
                                </tr>                      
                                <tr>
                                    <td>
                                        <span class="etiqueta">Email GRANA</span>
                                        <p class="data"><?php echo $evaluador['info_personal']['email'] != '' ? $evaluador['info_personal']['email'] : 'N/A' ?></p>
                                    </td>
                                    <td>
                                        <span class="etiqueta">Email Personal</span>
                                        <p class="data"><?php echo $evaluador['info_personal']['email_personal'] != '' ? $evaluador['info_personal']['email_personal'] : 'N/A' ?></p>
                                    </td>
                                   <td>
                                        <span class="etiqueta">Dirección</span>
                                        <p class="data"><?php echo $evaluador['info_personal']['direccion'] != '' ? $evaluador['info_personal']['direccion'] : 'N/A' ?></p>
                                    </td>                                     
                                </tr>                      
                                <tr>
                                    <td>
                                        <span class="etiqueta">Teléfono</span>
                                        <p class="data"><?php echo $evaluador['info_personal']['telefono'] != '' ? $evaluador['info_personal']['telefono'] : 'N/A' ?></p>
                                    </td>
                                    <td>
                                        &nbsp;
                                    </td>                                        
                                </tr>                      
                            </table>
                          </td>
                          <td width="25%">
                              <div id="foto">
                                <img src="<?php echo $evaluador['info_personal']['ruta'] != '' ? $evaluador['info_personal']['ruta'] : 'public/img/profile.jpg' ?>" style="width: 100%">
                            </div>
                          </td>
                      </tr>
                  </table>
                  
                  
              </div>
            </div>
          <!-- informacion basica de la institucion-->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Información académica</h3>
              </div>
              <div class="panel-body">
                  <div style="height: 150px; overflow-y:auto">
                    <table style="width:100%">
                        <?php foreach($evaluador['info_academica'] as $e) { ?>
                        <tr>
                            <td>
                                <span class="etiqueta">Titulo</span>
                                <p class="data"><?php echo $e['titulo'] != '' ? $e['titulo'] : 'N/A'?></p>
                            </td>
                            <td>
                                <span class="etiqueta">Nivel de formación</span>
                                <p class="data"><?php echo $e['nivel_formacion'] != '' ? $e['nivel_formacion'] : 'N/A'?></p>
                            </td>
                            <td>
                                <span class="etiqueta">Institucion</span>
                                <p class="data"><?php echo $e['institucion'] != '' ?  $e['institucion']  : 'N/A'?></p>
                            </td>
                            <td>
                                <span class="etiqueta">Año</span>
                                <p class="data"><p class="data"><?php echo $e['anio'] != '' ? $e['anio'] : 'N/A' ?></p></p>
                            </td>                      
                        </tr>    
                        <?php } ?>
                    </table>
                  </div>
              </div>
            </div>
          <!-- informacion basica de la institucion-->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Experiencia laboral</h3>
              </div>
              <div class="panel-body">
                  <div style="height: 150px; overflow-y:auto">
                    <table style="width:100%">
                        <?php foreach($evaluador['info_experiencia'] as $e) { ?>
                        <tr>
                            <td>
                                <span class="etiqueta">Institución</span>
                                <p class="data"><?php echo ($e['institucion'] != '' ?  $e['institucion'] : 'N/A')?></p>
                            </td>
                            <td>
                                <span class="etiqueta">Status laboral</span>
                                <p class="data"><?php echo ($e['status_laboral'] != '' ?  $e['status_laboral'] : 'N/A')?></p>
                            </td>
                            <td>
                                <span class="etiqueta">Periodo</span>
                                <p class="data"><?php echo ($e['periodo'] != '' ?  $e['periodo'] : 'N/A')?></p>
                            </td>                    
                        </tr>    
                        <?php } ?>
                    </table>
                  </div>
              </div>
            </div>
            
            



