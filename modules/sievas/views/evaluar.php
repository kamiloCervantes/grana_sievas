    <style>
        .ventanaAgregar .modal-dialog{
            width: 90%;
        }
    </style>   
    <h4>
        Evaluación Interna
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
                    <label>Calificacion</label>
                    <select name="calificacion" class="form-control">
                        <option>Seleccione una calificacion...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fortalezas</label>
                    <textarea name="fortalezas" class="form-control lineamiento"></textarea>
                </div>
                <div class="form-group">
                    <label>Debilidades</label>
                    <textarea name="debilidades" class="form-control lineamiento"></textarea>
                </div>
                <div class="form-group">
                    <label>Plan de mejoramiento</label>
                    <textarea name="plan_mejoramiento" class="form-control lineamiento"></textarea>
                </div>
            </form>
        </div>
        
    </div>
<?php
    View::add_js('public/summernote/summernote.min.js');
    View::add_css('public/summernote/summernote.css');
    View::add_css('//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css');