    <style>
        .ventanaAgregar .modal-dialog{
            width: 90%;
        }
        
        
        
    </style> 
<!--    <div class="btn-group pull-right">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          Opciones <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
          <li><a href="#">Significado</a></li>
          <li><a href="#">Contexto</a></li>
          <li><a href="#">Referencia</a></li>
          <li><a href="#">Manual</a></li>
          <li><a href="#">Glosario</a></li>
        </ul>
      </div>-->
    <h4>
        Datos complementarios del conjunto de lineamientos        
    </h4>   
    <hr/>
    
    <div class="row">        
        <!-- Arbol de lineamientos -->
        <div class="col-sm-6">
            <div style="overflow:auto">                        
                <div id="arbol" class="arbol" style="border: 1px solid #eee;min-height: 400px" data-conjunto="0">

                </div>            
            </div>
            <input id="conjunto" type="hidden" value="<?php echo $conjunto != null ? $conjunto : '0'?>">
        </div>
        
        <!-- Formulario -->
        <div class="col-sm-6">            
            <form class="form-horizontal" autocomplete="on" id='form_rubros' style="display:none">                               
                <div class="form-group">                   
                    <label>Significado del rubro</label>
                    <textarea name="significado" class="form-control summernote" id="significado" rows="5"></textarea>                                          
                </div>      
                <div class="form-group">                   
                    <label>Contexto del rubro</label>
                    <textarea name="contexto" class="form-control summernote" id="contexto" rows="5"></textarea>                                          
                </div>      
                <div class="form-group">                   
                    <label>Referencias del rubro</label>
                    <textarea name="referencias" class="form-control summernote" id="referencias" rows="5"></textarea>                                          
                </div>      
                <div class="form-group">                   
                    <label>Glosario del rubro</label>
                    <textarea name="glosario" class="form-control summernote" id="glosario" rows="5"></textarea>                                          
                </div>  
                <div class="form-group">                   
                    <label>Documento contexto del rubro</label>
                    <input name="documento_contexto" type="file" id="documento_contexto">                                        
                </div> 
                <div class="form-group">                   
                    <label>Documento glosario del rubro</label>
                    <input name="documento_glosario" type="file" id="documento_glosario">                                        
                </div>      
                <div class="form-group">                   
                    <label>Documento referencias</label>
                    <input name="documento_referencias" type="file" id="documento_referencias">                                           
                </div>      
                <div class="form-group">                   
                    <label>Tabla estadística</label>
                    <input name="tabla_estadistica" type="file" id="tabla_estadistica">                                           
                </div>  
                <div class="form-actions pull-right">
                    <a href="#" class="btn btn-primary" id="guardar-datos-rubro">Guardar</a>
                    <a href="index.php?mod=sievas&controlador=lineamientos&accion=conjuntos_lineamientos" class="btn btn-danger">Cancelar</a>                        
                </div>
            </form>
            <form class="form-horizontal" autocomplete="on" id='form_items' style="display:none">                               
                <div class="form-group">                   
                    <label>Indicadores de Ítem</label>
                    <textarea name="indicadores_item" class="form-control summernote" id="indicadores_item" rows="5"></textarea>                                          
                </div>    
                <div class="form-group">                   
                    <label>Documentos de Ítem</label>
                    <textarea name="documentos_item" class="form-control summernote" id="documentos_item" rows="5"></textarea>                                          
                </div>    
                <div class="form-actions pull-right">
                    <a href="#" class="btn btn-primary" id="guardar-datos-item">Guardar</a>  
                    <a href="index.php?mod=sievas&controlador=lineamientos&accion=conjuntos_lineamientos" class="btn btn-danger">Cancelar</a>                                                           
                </div>
            </form>
        </div>
<!--        <p style="color:#900">
            <strong>SIGNIFICADO
                <br/>R01. Impacto social de la formación.</strong>
        </p>
        <p style="text-align:justify">Formación de profesionistas en las distintas áreas del conocimiento con un conjunto de conocimientos, valores y habilidades que le permitan al egresado interactuar de manera crítica y propositiva con su entorno.</p>-->
    </div>
