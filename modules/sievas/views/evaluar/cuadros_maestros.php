<h5>
    <b> <span style="font-size: 16px">Cuadros maestros</span></b> 
</h5>
<hr/>

<p>Con  el  objeto  de  facilitar  el  conocimiento  y  manejo  de  la 
información relevante del programa, el CNA ha diseñado doce cuadros que se 
encuentran al final de la Guía de autoevaluación del CNA,  
los cuales sistematizan  toda  la  información que se requiere para el 
análisis de las características e indicadores: </p>
<ul>
    <li>Cuadro No. 1 - Programa: Identificación y Trayectoria </li>
    <li>Cuadro No. 2 - Estudiantes: Matriculados, graduados, deserción y movilidad </li>
    <li>Cuadro No. 3 - Número de profesores: dedicados principalmente al programa</li>
    <li>Cuadro No. 4 - Profesores: Forma de contratación</li>
    <li>Cuadro No. 5 - Profesores dedicados principalmente al programa: Nivel de formación</li>
    <li>Cuadro No. 6 - Profesores: Listado detallado </li>
    <li>Cuadro No. 7 - Investigación: Grupos de investigación relacionados con el programa</li>
    <li>Cuadro No. 8 - Publicaciones:  Referencias bibliográficas</li>
    <li>Cuadro No. 9 - Convenios y alianzas estratégicas del programa</li>
    <li>Cuadro No. 10 - Profesores visitantes al programa</li>
    <li>Cuadro No. 11 - Innovaciones generadas por el programa</li>
    <li>Cuadro No. 12 - Inmuebles y espacios disponibles</li>
</ul>

<p>Para ingresar los datos de los cuadros maestros se cuenta con una plantilla en excel la cual debe ser diligenciada
y luego se envia al sistema a través del formulario que se encuentra en la parte inferior de la página. 
Para descargar la plantilla haga click <a href="#">aquí</a>.</p><br/>

<?php if(count($cuadro_data) > 0){ ?>

<form class="form-cuadro" style='border: 1px solid #800;' method='post' data-id="<?php echo $cuadro_data['id']?>">
    <div style="width:100%; padding: 8px; background: #800; height: 40px">        
        <span style="color: #fff; font-size: 16px"><b>Archivo cuadros maestros:</b> &nbsp;&nbsp;&nbsp;&nbsp;</span> 
        <a href="<?php echo $cuadro_data['ruta']?>" style="color: #fff; font-size: 16px"> 
            <i class="glyphicon glyphicon-file"></i> <?php echo $cuadro_data['nombre']?>
        </a>
    </div>
    <div style="padding: 10px">
    <label>Reemplazar archivo cuadros maestros</label>
    <input type='file' name='cuadros_maestros'>
    </div>
</form>
<?php } else { ?>
<form class="form-cuadro" style='border: 1px solid #800; padding: 10px' method='post' data-id="0">
    <label>Subir archivo cuadros maestros <span><img src="public/files/img/ajax-loader.gif"></span></label>
    <input type='file' name='cuadros_maestros'>
</form>
<?php } ?>

<form style='border: 1px solid #800;' method='post' data-id="" class="form_tpl hide">
    <div style="width:100%; padding: 8px; background: #800; height: 40px">        
        <span style="color: #fff; font-size: 16px"><b>Archivo cuadros maestros:</b> &nbsp;&nbsp;&nbsp;&nbsp;</span> 
        <a href="" style="color: #fff; font-size: 16px"> 
            <i class="glyphicon glyphicon-file"></i> 
        </a>
    </div>
    <div style="padding: 10px">
    <label>Reemplazar archivo cuadros maestros <span><img src="public/files/img/ajax-loader.gif"></span></label>
    <input type='file' name='cuadros_maestros'>
    </div>
</form>