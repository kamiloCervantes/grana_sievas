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
        Definir rubros
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
        
        <!-- Formulario -->
        <div class="col-sm-6">            
            <br><br><br><br>
            <form class="form-horizontal" autocomplete="on" id='formLineamiento'>
                <div class="form-group">                   
                    <label>Lineamiento</label>
                    <textarea name="lineamiento" class="form-control" id="lineamiento" rows="5"></textarea>                                          
                </div>                
                
                <br/>
                <div class="form-actions pull-right">
                    <a href="#" class="btn btn-primary" id="guardar-item">Guardar</a>
                </div>                
            </form>
        </div>
        
    </div>
