<h4>Seleccione una evaluación</h4>
<br/>
<br/>
<a href="index.php?mod=sievas&controlador=usuarios&accion=instituciones_cee">Inicio</a> > <a href="index.php?mod=sievas&controlador=usuarios&accion=programas_cee&i=<?php echo $evaluaciones[0]['institucion_id']?>"><?php echo $evaluaciones[0]['nom_institucion']?></a> > <?php echo $evaluaciones[0]['programa']?>
<br/>
<br/>
<table class="table table-hover">
    <?php foreach($evaluaciones as $e){ ?>
    <tr>
        <td><a href="index.php?mod=sievas&controlador=usuarios&accion=detalle_cee&e=<?php echo $e['id']?>"><?php echo $e['etiqueta']?></a></td>
    </tr>
    <?php } ?>
</table>