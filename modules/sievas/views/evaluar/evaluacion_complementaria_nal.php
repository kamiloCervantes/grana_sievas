    <h4>
        Evaluación complementaria 
    </h4>   
    <hr/>    

    <div class="panel panel-primary">              
              <div class="panel-heading">
                <h3 class="panel-title">Carga de documentos</h3>
              </div>
              <div class="panel-body">  
                  <div class="predictamen_link hide">
                      <a href="#" target="_blank"></a>
                  </div>
                  <form action="#" class="dropzone" id="predictamen-dropzone">
                      <div class="dz-message">
                          <h2>Subir documentos</h2>
                          <p>Arrastre y suelte el archivo</p>
                      </div>
                  </form>   
                  <br/>
                  <form>
                    <div class="form-group col-sm-4">
                        <label class="control-label">Nombre del documento</label>
                        <input type="text" name="nombre_documento" id="nombre_documento" class="form-control">                        
                     </div>
                    <div class="form-group col-sm-4">
                        <label class="control-label">Fecha del documento</label>
                        <input type="text" name="fecha_documento" id="fecha_documento" class="form-control fecha" data-date-format="yyyy-mm-dd">                        
                     </div>
                    <div class="form-group col-sm-4">
                        <label class="control-label">Tipo de documento</label>
                        <select class="form-control" id="tipo_documento" name="tipo_documento">
                            <option value="0">Seleccione una opción...</option>
                            <option value="1">Dictamen acreditación</option>
                            <option value="2">Datos estadísticos</option>
                            <option value="3">Documento autoevaluación</option>
                            <option value="4">Otro</option>
                        </select>                     
                     </div>
                     
                      <div class="form-actions" style="text-align: center">
                          <a href="#" id="guardar-documento" class="btn btn-primary">Guardar</a>
                      </div>
                </form>
              </div>
        
            </div>
    
    <h3>Documentos registrados</h3>
    <table class="table" id="documentos">
        <thead>
            <tr>
                <th>Nombre documento</th>
                <th>Fecha documento</th>
                <th>Tipo documento</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
           <?php foreach($documentos as $d){ ?>
             <tr>
                <td><?php echo $d['nombre_documento'] ?></td>
                <td><?php echo $d['fecha_documento'] ?></td>
                <td><?php echo $tipos[$d['tipo_documento']] ?></td>
                <td><a href='#' data-id='<?php echo $d['id'] ?>'><i class='glyphicon glyphicon-trash'></i></a></td>
            </tr>
           <?php } ?>
        </tbody>
    </table>
    