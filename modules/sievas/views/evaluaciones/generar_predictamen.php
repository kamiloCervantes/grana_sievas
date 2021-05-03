<h5>
    <b> <span style="font-size: 16px">Generar predictamen</span></b> 
</h5>
<hr/>
<br/>
<form action="index.php?mod=sievas&controlador=evaluaciones&accion=previsualizar_predictamen" method="post" id="formPredictamen">
    <div class="form-group col-sm-12">
        <label>Percepción sobre el interés de la comunidad académica por la mejora en la calidad del PA</label>
        <input type="hidden" class="calificacion pull-right" id="calificacion_interes" style="width:250px">
        <br/><br/>
        <textarea class="form-control" id="comentario_interes"></textarea>
    </div>   
    <div class="form-group col-sm-12">
        <label>Percepción sobre la disponibilidad de profesores, estudiantes, directivos y empleadores en las entrevistas realizadas</label>
        <input type="hidden" class="calificacion pull-right" id="calificacion_disponibilidad"  style="width:250px">
        <br/><br/>
        <textarea class="form-control" id="comentario_disponibilidad"></textarea>
    </div>   
    <div class="form-group col-sm-12">
        <label>Percepción sobre la documentación disponible para la verificación de datos</label>
        <input type="hidden" class="calificacion pull-right" id="calificacion_documentacion" style="width:250px">
        <br/><br/>
        <textarea class="form-control" id="comentario_documentacion"></textarea>
    </div>   
    <div class="form-group col-sm-12">
        <label>Recomendaciones en lo general sobre la mejora en la calidad</label>
        <input type="hidden" class="calificacion pull-right" id="calificacion_mejora" style="width:250px">
        <br/><br/>
        <textarea class="form-control" id="comentario_mejora"></textarea>
    </div>   
    <div class="form-actions" style="text-align: center">
        <input type="submit" class="btn btn-default" value="Previsualizar">
        <a href="#" class="btn btn-primary" id="guardar-dictamen">Guardar</a>
        <a href="#" class="btn btn-danger">Cancelar</a>
    </div>
</form>