<h5>
    <b><span style="font-size: 16px">Autorizar usuarios</span></b> 
</h5>
<br/>
<table width="100%" border="0" cellspacing="0" cellpadding="1" id="data_tabla" class="table table-striped table-bordered" >
<thead>
<tr>
<td width="20%">Nombre</td>
<td width="20%">Rol</td>
<td width="10%">Usuario</td>
<td width="10%">&nbsp;</td>
</tr>
</thead>
<tbody>
    <?php foreach($usuarios as $u){ ?>
<tr>
<td><?php echo $u['nombres'] ?></td>
<td><?php echo $u['idrol'] ?></td>
<td><?php echo $u['username'] ?></td>
<td><a href="#" class="autorizar-usuarios" data-usuario="<?php echo $u['username'] ?>" title="Autorizar usuario"><i class="glyphicon glyphicon-ok-sign"></i></a></td>
</tr>
    <?php } ?>
</tbody>
<tfoot>
<tr style="background: #ccc">
<td width="20%">Nombre</td>
<td width="20%">Rol</td>
<td width="10%">Usuario</td>
<td width="10%">&nbsp;</td>
</tr>
</tfoot>
<tbody>
</tbody>
</table>
