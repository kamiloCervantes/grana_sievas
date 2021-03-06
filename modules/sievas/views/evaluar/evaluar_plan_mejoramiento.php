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
        Plan de Mejoramiento Permanente :: <?php echo $evaluacion ?>
    </h4>   
    <hr/>
    <div class="row">
        
        <!-- Arbol de lineamientos -->
        <div class="col-sm-6">
            <div style="overflow:auto">            
            
               
            <br/><br/>
            <p id="nombre-rubro" style="color: #990000"></p>
            
            <div class="buscador-tipos-lineamientos">            
            
<!--               <a id="cerrar-nodos" class="btn btn-default" href="#">Cerrar nodos</a>
               <a id="abrir-nodos" class="btn btn-default" href="#">Abrir nodos</a>-->
<!--               <br/> <br/>-->
               <div class="form-actions">
               <div class="input-group">                
                    <input type="text" id="q" placeholder="Buscar" class="form-control">
                    <span id="buscar-tipo-lineamiento" class="input-group-addon">
                        <i class="glyphicon glyphicon-search"></i>
                    </span>
                </div>
            </div>
           </div>
            <div id="arbol" class="arbol"></div>
            
            
            </div>
        </div>
        
        <div id="accion-form" style="display:none">
            <form>
                <div class="form-group">                   
                    <label>Descripci??n de la acci??n</label>
                    <textarea name="nombre_accion" class="form-control" id="nombre_accion"></textarea>                                          
                </div>
                <div class="form-group">                   
                    <label>Metas de la acci??n</label>
                    <textarea type="text" name="metas_accion" class="form-control" id="metas_accion"></textarea>                                          
                </div>
                <div class="form-group">                   
                    <label>Responsables de la acci??n</label>
                    <textarea type="text" name="responsables_accion" class="form-control" id="responsables_accion"></textarea>                                          
                </div>
            </form>
        </div>
        
        <div id="help" style="display:none">
            <div id="titulo_help">
                <b>T??tulo del plan de mejoramiento.</b> Se puede definir un t??tulo que
                resuma de forma general los prop??sitos del plan de mejoramiento.
            </div>
            <div id="subtitulo_help">
                <b>Subt??tulo del plan de mejoramiento.</b> El subt??tulo complementa la
                informaci??n presentada en el t??tulo otorgando un nivel de
                especificidad al plan de mejoramiento permanente.
            </div>
            <div id="presupuesto_help">
                <b>Presupuesto del plan de mejoramiento.</b> El presupuesto 
                es el c??mputo anticipado del costo de 
                una obra o de los gastos que implicar?? un determinado proyecto.
            </div>
            <div id="fecha_cumplimiento_help">
                <b>Fecha de cumplimiento del plan de mejoramiento.</b> Consiste en el
                rango de fechas en las cuales se planea ejecutar el plan de mejoramiento.
            </div>
            <div id="objetivos_help">
                <b>Objetivos del plan de mejoramiento.</b> Un objetivo es el 
                planteo de una meta o un prop??sito a alcanzar, y que, de 
                acuerdo al ??mbito donde sea utilizado, o m??s bien formulado, 
                tiene cierto nivel de complejidad. El objetivo es una de las 
                instancias fundamentales en un proceso de planificaci??n 
                (que puede estar, como dijimos, a diferentes ??mbitos) y que se 
                plantean de manera abstracta en ese principio pero luego, 
                pueden (o no) concretarse en la realidad, seg??n si el proceso 
                de realizaci??n ha sido, o no, exitoso.
            </div>
            <div id="metas_help">
                <b>Metas del plan de mejoramiento.</b> Una meta es un 
                peque??o objetivo que lleva a conseguir el objetivo como tal. 
                La meta se puede entender como la expresi??n de un objetivo en 
                t??rminos cuantitativos y cualitativos.
                Las metas son como los procesos que se deben seguir y terminar 
                para poder llegar al objetivo. Todo objetivo est?? compuesto por 
                una serie de metas, que unidas y alcanzadas conforman el objetivo.
            </div>
            <div id="estrategias_help">
                <b>Estrategias del plan de mejoramiento.</b> Una estrategia es un 
                plan que especifica una serie de pasos o de conceptos nucleares 
                que tienen como fin la consecuci??n de un determinado objetivo.
            </div>
            <div id="acciones_help">
                <b>Acciones del plan de mejoramiento.</b> Una acci??n es un medio
                por el cual se logran alcanzar metas planteadas en pro de objetivos
                establecidos.
            </div>
        </div>
        <!-- Formulario -->
        <div class="col-sm-6">
            <div id="opciones">                
                <div class="controls">                    
                                    
                </div>                
            </div>
            <br/>
            <div id="datos-item"></div>
            <br/>
            <div id="calificaciones"></div>
            
            <form class="form-horizontal" autocomplete="on" data-cargo='<?php echo $cod_cargo?>' data-momento='<?php echo $cod_momento?>' data-bandera-ev='<?php echo $bandera?>' id='formEvaluacion'>
                <div class="form-group">                   
                    <label class="subtitulo">T??tulo  <a href="#" data-help="#titulo_help" class="help"><i class="glyphicon glyphicon-question-sign" style="color:#ffffcc"></i></a></label>
                    <input type="text" name="titulo" class="form-control" id="titulo_p">                                          
                </div>
                <div class="form-group">                   
                    <label class="subtitulo">Subt??tulo <a href="#" data-help="#subtitulo_help" class="help"><i class="glyphicon glyphicon-question-sign" style="color:#ffffcc"></i></a></label>
                    <input type="text" name="subtitulo" class="form-control" id="subtitulo">                                     
                </div>
                <div class="form-group" style="padding: 0">
                    <label class="subtitulo">Presupuesto <a href="#" data-help="#presupuesto_help" class="help"><i class="glyphicon glyphicon-question-sign" style="color:#ffffcc"></i></a></label>
                    <div id="presupuesto" class="summernote_modal" data-title="Presupuesto" data-success="guardar_plan" style="overflow:auto">
                      <input type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group">                   
                    <label class="subtitulo">Fecha de inicio <a href="#" data-help="#fecha_cumplimiento" class="help"><i class="glyphicon glyphicon-question-sign" style="color:#ffffcc"></i></a></label>
                    <input type="text" name="fecha_inicio" class="form-control" id="fecha_inicio">                                     
                </div>
                <div class="form-group">                   
                    <label class="subtitulo">Fecha de fin <a href="#" data-help="#fecha_cumplimiento" class="help"><i class="glyphicon glyphicon-question-sign" style="color:#ffffcc"></i></a></label>
                    <input type="text" name="fecha_fin" class="form-control" id="fecha_fin">                                     
                </div>
                <div class="form-group" style="padding: 0">
                    <label class="subtitulo">Objetivos <a href="#" data-help="#objetivos_help" class="help"><i class="glyphicon glyphicon-question-sign" style="color:#ffffcc"></i></a></label>
                    <div id="objetivos" class="summernote_modal " data-title="Objetivos" data-success="guardar_plan" style="overflow:auto">
                          <input type="text" class="form-control">
                      </div>
                </div>
                <div class="form-group" style="padding: 0">
                    <label class="subtitulo">Estrategias <a href="#" data-help="#estrategias_help" class="help"><i class="glyphicon glyphicon-question-sign" style="color:#ffffcc"></i></a></label>
                    <div id="estrategias" class="summernote_modal " data-title="Estrategias" data-success="guardar_plan" style="overflow:auto">
                          <input type="text" class="form-control">
                      </div>
                </div>
                <div class="form-group">                   
                    <label class="subtitulo">Acciones <a href="#" data-help="#acciones_help" class="help"><i class="glyphicon glyphicon-question-sign" style="color:#ffffcc"></i></a><a href="#" class="pull-right" id="add-accion"><i class="glyphicon glyphicon-plus-sign" style="color:#ffffcc"></i></a></label>
                    <table class="table table-bordered table-striped" id="lista_acciones">
                        
                    </table>
                    <!--<input type="text" name="acciones" class="form-control etiquetas" id="acciones" style="border: 1px solid #ccc; border-radius: 3px;height: 35px;font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size:14px">-->                                      
                </div>
                
                
                <br/>
                <div class="form-actions pull-right">
                    <a href="#" class="btn btn-primary" id="guardar-item">Guardar</a>
                </div>                
            </form>
        </div>
        
    </div>
