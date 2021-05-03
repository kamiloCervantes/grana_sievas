<h4>Seleccione un programa</h4>
<br/><br/>
<a href="index.php?mod=sievas&controlador=usuarios&accion=instituciones_cee">Inicio</a> > <?php echo $programas[0]['nom_institucion']?>
<br/>
<br/>
<table class="table table-hover">
    <?php foreach($programas as $p){ ?>
    <tr>
        <td><a href="index.php?mod=sievas&controlador=usuarios&accion=evaluaciones_cee&p=<?php echo $p['id']?>"><?php echo $p['programa']?></a></td>
    </tr>
    <?php } ?>
</table>