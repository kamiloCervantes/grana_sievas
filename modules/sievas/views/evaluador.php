

    <style>
        .ventanaAgregar .modal-dialog{
            width: 90%;
        }
    </style>   
    <h4>
        Perfil del Evaluador
    </h4>                
    <hr/>
    <div class="row">
        
        <!-- Arbol de lineamientos -->
        <div class="col-sm-7">
            <div style="overflow:auto">
            <div id="arbol"></div>
            </div>
        </div>
        
        <!-- Formulario -->
        <div class="col-sm-5">
            <form>
            	
                <div class="form-group">	
                    <div class="col-xs-6">
                        <input type="text" name="opciones" id="opciones" class="form-control" 
                            placeholder="Número documento">
                    </div>
                    <div class="radio-inline">
                      <label><input type="radio" name="tipo_documento" id="CC" value="CC" checked>
                        CC</label>
                    </div>
                    <div class="radio-inline">
                      <label><input type="radio" name="tipo_documento" id="CE" value="CE">
                        CE</label>
                    </div>
                    <div class="radio-inline">
                      <label><input type="radio" name="tipo_documento" id="PP" value="PP">
                        PP</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <input type="text" name="opciones" id="opciones" class="form-control" 
                    	placeholder="Nombre completo">
                </div>
                
                <div class="form-group">	
                    <div class="radio-inline">
                      <label><input type="radio" name="genero" id="F" value="F">
                        Femenino</label>
                    </div>
                    <div class="radio-inline">
                      <label><input type="radio" name="genero" id="M" value="M">
                        Masculino</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <input type="text" name="opciones" id="opciones" class="form-control" 
                    	placeholder="Dirección">
                </div>
                
                <div class="form-group">
                    <input type="text" name="opciones" id="opciones" class="form-control" 
                    	placeholder="Telefonos">
                </div>
                
                <div class="form-group">
                    <input type="text" name="opciones" id="opciones" class="form-control" 
                    	placeholder="Nombres">
                </div>
                
                <div class="form-group">
                    <input type="text" name="opciones" id="opciones" class="form-control" 
                    	placeholder="E - mail">
                </div>
                
                <div class="form-group">
                    <input type="text" name="opciones" id="opciones" class="form-control" 
                    	placeholder="Nacionalidad">
                </div>
            </form>
        </div>
        
    </div>
<?php
    View::add_js('public/summernote/summernote.min.js');
    View::add_css('modules/academico/resources/css/observador.css');
    View::add_css('public/summernote/summernote.css');
    View::add_css('//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css');