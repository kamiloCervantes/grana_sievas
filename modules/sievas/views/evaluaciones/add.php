<style>
    .popover{
       width: 360px !important;
    }
</style>
            <h4 class="sub-header">Iniciar evaluación</h4>
            <hr/>
          <br/>
          <form id="formEvaluacion" autocomplete="on">
          <!-- informacion basica de la institucion-->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Datos de la evaluación</h3>
              </div>
              <div class="panel-body">
                <div class="form-group col-sm-3">
                    <label class="control-label">Nombre</label>
                    <input type="text" name="etiqueta" id="etiqueta" class="form-control">                        
                  </div>
                <div class="form-group col-sm-3">
                    <label class="control-label">Tipo de evaluado</label>
                    <input type="hidden" name="tipo_evaluado" id="tipo_evaluado" class="form-control">                        
                  </div>
                  <div class="form-group col-sm-3">
                    <label class="control-label">Evaluado</label>
                    <input type="text" name="evaluado" id="evaluado" class="form-control" />
                  </div> 
                  <div class="form-group col-sm-3">
                    <label class="control-label">Lineamientos</label>
                    <input type="hidden" name="conjunto_lineamientos" id="conjunto_lineamientos" class="form-control" />
                  </div>
              </div>
            </div>
            <!-- rector o maxima autoridad de la institucion -->
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Evaluación interna</h3>
              </div>
              <div class="panel-body">
                <div class="row">
                <div class="col-sm-4">
                  <div class="form-group" id="tipos_momentos">
                    <label class="control-label">Momento</label>
                    <input type="hidden" name="tipo_momento_interna" class="form-control momento tipo_momento_0" value="1"/>
                  </div>  
                </div>
                  
                <div class="col-sm-4">
                  <div class="form-group" id="fechas_inicio">
                    <label class="control-label">Fecha de inicio</label>
                    <div class="input-group">
                      <input type="text" name="fecha_inicio_interna" class="form-control fecha fecha_inicio_0" data-date-format="yyyy-mm-dd"/>
                      <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>                    
                  </div>                      
                </div>

                <div class="col-sm-4">
                  <div class="form-group" id="fechas_fin">
                    <label class="control-label">Fecha de finalización</label>
                    <div class="input-group">
                      <input type="text" name="fecha_fin_interna" class="form-control fecha fecha_fin_0" data-date-format="yyyy-mm-dd" />
                      <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div> 
                    
                  </div>  
                </div>
                </div>
                  
                 <div class="row">                 
                  <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label">Coordinador</label>
                    <div class="input-group">                        
                        <input type="hidden" name="coordinador_interna" id="coordinador_interna" class="form-control sel-usuarios" />
                        <span class="input-group-addon"><a href="index.php?mod=sievas&controlador=usuarios&accion=evaluador" target="_blank"><i class="glyphicon glyphicon-plus-sign"></i></a></span>
                     </div>
                  </div>                                 
                </div>

                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="control-label">Directivo</label>
                    <div class="input-group">
                        <input type="hidden" name="directivo_interna" id="directivo_interna" class="form-control sel-usuarios" />
                        <!--<span class="input-group-addon" id="popup2" data-cargo="2"><i class="glyphicon glyphicon-plus-sign"></i></span>-->
                        <span class="input-group-addon"><a href="index.php?mod=sievas&controlador=usuarios&accion=evaluador" target="_blank"><i class="glyphicon glyphicon-plus-sign"></i></a></span>
                    </div>
                    
                  </div>                    
                </div>
                     
                <div class="col-sm-4">
                   <div class="form-group">
                    <label class="control-label">Estudiante</label>
                    <div class="input-group">
                        <input type="hidden" name="estudiante_interna" id="estudiante_interna" class="form-control sel-usuarios" />
                        <!--<span class="input-group-addon" id="popup4" data-cargo="3"><i class="glyphicon glyphicon-plus-sign"></i></span>-->
                        <span class="input-group-addon"><a href="index.php?mod=sievas&controlador=usuarios&accion=evaluador" target="_blank"><i class="glyphicon glyphicon-plus-sign"></i></a></span>
                    </div>
                  </div>                    
                </div>                
               </div> 
                
                <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                      <label class="control-label">Evaluador 1</label>
                      <div class="input-group">
                          <input type="hidden" name="evaluador_1_interna" id="evaluador_1_interna" class="form-control sel-usuarios" />
                          <!--<span class="input-group-addon" id="popup1" data-cargo="4"><i class="glyphicon glyphicon-plus-sign"></i></span>-->
                          <span class="input-group-addon"><a href="index.php?mod=sievas&controlador=usuarios&accion=evaluador" target="_blank"><i class="glyphicon glyphicon-plus-sign"></i></a></span>
                      </div>
                  </div>  
                </div>    
                
                <div class="col-sm-4">  
                <div class="form-group">
                      <label class="control-label">Evaluador 2</label>
                      <div class="input-group">
                          <input type="hidden" name="evaluador_2_interna" id="evaluador_2_interna" class="form-control sel-usuarios" />
                          <!--<span class="input-group-addon" id="popup3" data-cargo="4"><i class="glyphicon glyphicon-plus-sign"></i></span>-->
                          <span class="input-group-addon"><a href="index.php?mod=sievas&controlador=usuarios&accion=evaluador" target="_blank"><i class="glyphicon glyphicon-plus-sign"></i></a></span>
                      </div>  
                  </div> 
                </div>
                
                <div class="col-sm-4">
                <div class="form-group">
                      <label class="control-label">Evaluador 3</label>
                      <div class="input-group">
                          <input type="hidden" name="evaluador_3_interna" id="evaluador_3_interna" class="form-control sel-usuarios" />
                          <!--<span class="input-group-addon popup-persona" id="popup5" data-cargo="4"><i class="glyphicon glyphicon-plus-sign"></i></span>-->
                          <span class="input-group-addon"><a href="index.php?mod=sievas&controlador=usuarios&accion=evaluador" target="_blank"><i class="glyphicon glyphicon-plus-sign"></i></a></span>
                      </div>
                  </div> 
                  </div>
               </div>    
              </div>         
            </div>
            
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Evaluación externa</h3>
                </div>
                <div class="panel-body">  
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group" id="tipos_momentos">
                                <label class="control-label">Momento</label>
                                <input type="hidden" name="tipo_momento_externa" class="form-control momento tipo_momento_0" value="2"/>
                            </div>  
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group" id="fechas_inicio">
                                <label class="control-label">Fecha de inicio</label>
                                <div class="input-group">
                                    <input type="text" name="fecha_inicio_externa" class="form-control fecha fecha_inicio_0" data-date-format="yyyy-mm-dd"/>
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div>                    
                            </div>                      
                        </div>


                        <div class="col-sm-4">
                            <div class="form-group" id="fechas_fin">
                                <label class="control-label">Fecha de finalización</label>
                                <div class="input-group">
                                    <input type="text" name="fecha_fin_externa" class="form-control fecha fecha_fin_0" data-date-format="yyyy-mm-dd" />
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div> 

                            </div>  
                        </div>
                    </div>

                    <div class="row">  
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label">Coordinador</label>
                                <div class="input-group">
                                    <input type="hidden" name="coordinador_externa" id="coordinador_externa" class="form-control sel-usuarios" />
                                    <span class="input-group-addon"><a href="index.php?mod=sievas&controlador=usuarios&accion=evaluador" target="_blank"><i class="glyphicon glyphicon-plus-sign"></i></a></span>
                                </div>
                            </div>                                   
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label">Directivo</label>
                                <div class="input-group">
                                    <input type="hidden" name="directivo_externa" id="directivo_externa" class="form-control sel-usuarios" />
                                    <!--<span class="input-group-addon popup-persona" id="popup7" data-cargo="2"><i class="glyphicon glyphicon-plus-sign"></i></span>-->
                                    <span class="input-group-addon"><a href="index.php?mod=sievas&controlador=usuarios&accion=evaluador" target="_blank"><i class="glyphicon glyphicon-plus-sign"></i></a></span>
                                </div>
                            </div>                  
                        </div>


                        <div class="col-sm-4">                   
                            <div class="form-group">
                                <label class="control-label">Evaluador 1</label>
                                <div class="input-group">
                                    <input type="hidden" name="evaluador_1_externa" id="evaluador_1_externa" class="form-control sel-usuarios" />
                                    <!--<span class="input-group-addon popup-persona" id="popup6" data-cargo="4"><i class="glyphicon glyphicon-plus-sign"></i></span>-->
                                    <span class="input-group-addon"><a href="index.php?mod=sievas&controlador=usuarios&accion=evaluador" target="_blank"><i class="glyphicon glyphicon-plus-sign"></i></a></span>
                                </div>
                            </div>                  
                        </div>    

                    </div> 

                    <div class="row">

                        <div class="col-sm-4">                  
                            <div class="form-group">
                                <label class="control-label">Evaluador 2</label>
                                <div class="input-group">
                                    <input type="hidden" name="evaluador_2_externa" id="evaluador_2_externa" class="form-control sel-usuarios" />
                                    <!--<span class="input-group-addon popup-persona" id="popup8" data-cargo="4"><i class="glyphicon glyphicon-plus-sign"></i></span>-->
                                    <span class="input-group-addon"><a href="index.php?mod=sievas&controlador=usuarios&accion=evaluador" target="_blank"><i class="glyphicon glyphicon-plus-sign"></i></a></span>
                                </div>
                            </div>                  
                        </div>    

                        <div class="col-sm-4">               
                            <div class="form-group">
                                <label class="control-label">Evaluador 3</label>
                                <div class="input-group">
                                    <input type="hidden" name="evaluador_3_externa" id="evaluador_3_externa" class="form-control sel-usuarios" />
                                    <!--<span class="input-group-addon popup-persona" id="popup10" data-cargo="4"><i class="glyphicon glyphicon-plus-sign"></i></span>-->
                                    <span class="input-group-addon"><a href="index.php?mod=sievas&controlador=usuarios&accion=evaluador" target="_blank"><i class="glyphicon glyphicon-plus-sign"></i></a></span>
                                </div>
                            </div>   
                        </div>


                        <div class="col-sm-4"> 
                            <div class="form-group">
                                <label class="control-label">Evaluador 4</label>
                                <div class="input-group">
                                    <input type="hidden" name="evaluador_4_externa" id="evaluador_4_externa" class="form-control sel-usuarios" />
                                    <!--<span class="input-group-addon popup-persona" id="popup9" data-cargo="3"><i class="glyphicon glyphicon-plus-sign"></i></span>-->
                                    <span class="input-group-addon"><a href="index.php?mod=sievas&controlador=usuarios&accion=evaluador" target="_blank"><i class="glyphicon glyphicon-plus-sign"></i></a></span>
                                </div>
                            </div>
                        </div> 
                    </div>

                </div>         
            </div>
            
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Opciones de evaluacion</h3>
                </div>
                <div class="panel-body">  
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <br/>
                                <input type="checkbox" id="ev_red"/>
                                <label class="control-label">Evaluación en red</label>
                                
                            </div>  
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Evaluación padre</label>
                                <input type="hidden" id="ev_padre" class="form-control" />                        
                            </div>                      
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <br/>
                                <input type="checkbox" id="traduccion"/>
                                <label class="control-label">Activar traducciones</label>
                                
                            </div>  
                        </div>
                         <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Establecer escala de evaluación</label>
                                <input type="hidden" id="escala" class="form-control" />                        
                            </div>                      
                        </div>

                    </div>
                </div>         
            </div>
            
           
            <div class="form-actions" style="text-align: center">
              <a class="btn btn-primary" href="#" id="guardar-form">Guardar</a>
              <a class="btn btn-danger" href="index.php?mod=sievas&controlador=evaluaciones&accion=listar_evaluaciones">Cancelar</a>
            </div>
          </form>
          <div id="popover-wrapper-1"></div>
          <div id="popover-wrapper-2"></div>
          <div id="popover-wrapper-3"></div>
          <div id="popover-wrapper-4"></div>
          <div id="popover-wrapper-5"></div>
          <div id="popover-wrapper-6"></div>
          <div id="popover-wrapper-7"></div>
          <div id="popover-wrapper-8"></div>
          <div id="popover-wrapper-9"></div>
          <div id="popover-wrapper-10"></div>


