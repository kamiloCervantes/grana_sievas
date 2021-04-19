<h2>Comité de evaluación externa</h2>
<br/>
<a href="index.php?mod=sievas&controlador=usuarios&accion=instituciones_cee">Inicio</a> > <a href="index.php?mod=sievas&controlador=usuarios&accion=programas_cee&i=<?php echo $evaluacion[0]['institucion_id']?>"><?php echo $evaluacion[0]['nom_institucion']?></a> > <a href="index.php?mod=sievas&controlador=usuarios&accion=evaluaciones_cee&p=<?php echo $evaluacion[0]['programa_id']?>"><?php echo $evaluacion[0]['programa']?></a> > <a href="#"><?php echo $evaluacion[0]['etiqueta']?></a> > CEE
<br/>
<br/>
<table class="table table-hover">
    <?php foreach($evaluacion as $comite){ ?>
    <tr>
        <td><img src="<?php echo $comite['foto']?>" width="160"></td>
        <td>
            <h3><?php echo $comite['nombres'].' '.$comite['primer_apellido'].' '.$comite['segundo_apellido']?></h3> 
            <a href="<?php echo $comite['cv']?>" target="_blank">Visualizar CV</a>
        </td>
    </tr>
    <?php } ?>
</table>