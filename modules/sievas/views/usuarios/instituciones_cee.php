<h4>Seleccione una entidad</h4>
<br/><br/>
<table class="table table-hover">
    <?php foreach($instituciones as $i){ ?>
    <tr>
        <td><a href="index.php?mod=sievas&controlador=usuarios&accion=programas_cee&i=<?php echo $i['id']?>"><?php echo $i['nom_institucion']?></a></td>
    </tr>
    <?php } ?>
</table>