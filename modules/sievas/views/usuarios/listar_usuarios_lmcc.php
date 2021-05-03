<h5>
    <a href="index.php?mod=sievas&controlador=usuarios&accion=agregar_usuario_lmcc" class="btn btn-default btn-xs" id="add-generic"><i class="glyphicon glyphicon-plus"></i></a><b> <span style="font-size: 16px">Usuarios LMCC</span></b> 
</h5>
<br/>
<table width="100%" border="0" cellspacing="0" cellpadding="1" id="data_tabla" class="table table-striped table-bordered" >
<thead>
<tr>
<td width="10%">Usuario</td>
<td width="20%">Nombre</td>
<td width="10%">&nbsp;</td>
</tr>
</thead>
<tbody>
    <?php foreach($users as $u){ ?>
    <tr>
        <td><?php echo $u['username']?></td>
        <td><?php echo $u['nombre']?></td>
        <td>
            <a href="index.php?mod=sievas&controlador=usuarios&accion=editar_usuario_lmcc&id=<?php echo $u['id'] ?>">Editar</a>
        </td>
    </tr>
    <?php } ?>
</tbody>
<tfoot>
<tr style="background: #ccc">
<td width="10%">Usuario</td>
<td width="20%">Nombre</td>
<td width="10%">

</td>
</tr>
</tfoot>
<tbody>
</tbody>
</table>