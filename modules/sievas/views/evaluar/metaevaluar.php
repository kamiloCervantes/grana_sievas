    <style>
        .ventanaAgregar .modal-dialog{
            width: 90%;
        }
        
        
        
    </style> 

    <h4>
        Metaevaluación
    </h4>   
    <hr/>
   <?php if($estado > 0){ ?>
    <div class='well'>
        <h2>Gracias por enviar sus opiniones!</h2>
    </div>
   <?php } else {  ?>
    <form id="formMetaevaluacion">
        <div class="form-group">
            <label>Opinión general del modelo de evaluación</label>
            <div class="pull-right">
                <b>Calificación</b> 
                <select id="cal_1" name="cal_1">
                    <option value="0">Seleccione una opción...</option>
                    <option value="1">Malo</option>
                    <option value="2">Regular</option>
                    <option value="3">Aceptable</option>
                    <option value="4">Bueno</option>
                    <option value="5">Excelente</option>
                </select>
            </div>
            
            <textarea class="form-control" id="op_1" rows="5"></textarea>
        </div>
        <div class="form-group">
            <label>Opinión sobre el contenido de los ítem</label>
            <div class="pull-right">
                <b>Calificación</b> 
                <select id="cal_2" name="cal_2">
                    <option value="0">Seleccione una opción...</option>
                    <option value="1">Malo</option>
                    <option value="2">Regular</option>
                    <option value="3">Aceptable</option>
                    <option value="4">Bueno</option>
                    <option value="5">Excelente</option>
                </select>
            </div>
            <textarea class="form-control" id="op_2" rows="5"></textarea>
        </div>
        <div class="form-group">
            <label>Opinión de la estructura del sistema</label>
            <div class="pull-right">
                <b>Calificación</b> 
                <select id="cal_3" name="cal_3">
                    <option value="0">Seleccione una opción...</option>
                    <option value="1">Malo</option>
                    <option value="2">Regular</option>
                    <option value="3">Aceptable</option>
                    <option value="4">Bueno</option>
                    <option value="5">Excelente</option>
                </select>
            </div>
            <textarea class="form-control" id="op_3" rows="5"></textarea>
        </div>
        <div class="form-group">
            <label>Opinión de las tablas estadísticas</label>
            <div class="pull-right">
                <b>Calificación</b> 
                <select id="cal_4" name="cal_4">
                    <option value="0">Seleccione una opción...</option>
                    <option value="1">Malo</option>
                    <option value="2">Regular</option>
                    <option value="3">Aceptable</option>
                    <option value="4">Bueno</option>
                    <option value="5">Excelente</option>
                </select>
            </div>
            <textarea class="form-control" id="op_4" rows="5"></textarea>
        </div>
        <div class="form-group">
            <label>Opinión del uso informático del sistema SIEVAS</label>
            <div class="pull-right">
                <b>Calificación</b> 
                <select id="cal_5" name="cal_5">
                    <option value="0">Seleccione una opción...</option>
                    <option value="1">Malo</option>
                    <option value="2">Regular</option>
                    <option value="3">Aceptable</option>
                    <option value="4">Bueno</option>
                    <option value="5">Excelente</option>
                </select>
            </div>
            <textarea class="form-control" id="op_5" rows="5"></textarea>
        </div>
        <div class="form-group">
            <label>Opinión del significado, referencias, bibliografía</label>
            <div class="pull-right">
                <b>Calificación</b> 
                <select id="cal_6" name="cal_6">
                    <option value="0">Seleccione una opción...</option>
                    <option value="1">Malo</option>
                    <option value="2">Regular</option>
                    <option value="3">Aceptable</option>
                    <option value="4">Bueno</option>
                    <option value="5">Excelente</option>
                </select>
            </div>
            <textarea class="form-control" id="op_6" rows="5"></textarea>
        </div>
        <div class="form-group">
            <label>Opinión de las gráficas</label>
            <div class="pull-right">
                <b>Calificación</b> 
                <select id="cal_7" name="cal_7">
                    <option value="0">Seleccione una opción...</option>
                    <option value="1">Malo</option>
                    <option value="2">Regular</option>
                    <option value="3">Aceptable</option>
                    <option value="4">Bueno</option>
                    <option value="5">Excelente</option>
                </select>
            </div>
            <textarea class="form-control" id="op_7" rows="5"></textarea>
        </div>
        <div class="form-group">
            <label>Opinión de la capacitación para la evaluacion interna</label>
            <div class="pull-right">
                <b>Calificación</b> 
                <select id="cal_8" name="cal_8">
                    <option value="0">Seleccione una opción...</option>
                    <option value="1">Malo</option>
                    <option value="2">Regular</option>
                    <option value="3">Aceptable</option>
                    <option value="4">Bueno</option>
                    <option value="5">Excelente</option>
                </select>
            </div>
            <textarea class="form-control" id="op_8" rows="5"></textarea>
        </div>
        <div class="form-group">
            <label>Opinión de la evaluación externa</label>
            <div class="pull-right">
                <b>Calificación</b> 
                <select id="cal_9" name="cal_9">
                    <option value="0">Seleccione una opción...</option>
                    <option value="1">Malo</option>
                    <option value="2">Regular</option>
                    <option value="3">Aceptable</option>
                    <option value="4">Bueno</option>
                    <option value="5">Excelente</option>
                </select>
            </div>
            <textarea class="form-control" id="op_9" rows="5"></textarea>
        </div>
        <div class="form-group">
            <label>Opinión de los procesos y procedimientos</label>
            <div class="pull-right">
                <b>Calificación</b> 
                <select id="cal_10" name="cal_10">
                    <option value="0">Seleccione una opción...</option>
                    <option value="1">Malo</option>
                    <option value="2">Regular</option>
                    <option value="3">Aceptable</option>
                    <option value="4">Bueno</option>
                    <option value="5">Excelente</option>
                </select>
            </div>
            <textarea class="form-control" id="op_10" rows="5"></textarea>
        </div>
        <div class="form-actions" style="text-align:center">
            <input type="submit" value="Guardar" class="btn btn-primary"/>
            <a href="#" class="btn btn-danger">Cancelar</a>
        </div>
    </form>
<?php } ?>