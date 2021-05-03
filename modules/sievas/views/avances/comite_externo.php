<h5>
 <span style="font-size: 16px">Listado de evaluadores externos</span></b> 
</h5>
<br/>
<table class="table table-striped table-bordered" >
<thead>
<tr>
<td>Evaluador</td>
<td>Usuario</td>
<td>Rol</td>
<td>Evaluación</td>
</tr>
</thead>
<tbody>
<?php foreach($comite as $c){ ?>
    <tr>
        <td><?php echo $c['nombres'] ?></td>
        <td><?php echo $c['username'] ?></td>
        <td><?php echo $c['cargo'] ?></td>
        <td><?php echo $c['etiqueta'] ?></td>
    </tr>
<?php } ?>    
</tbody>
<tfoot>
<tr style="background: #ccc">
<td>Programa</td>
<td>Username</td>
<td>Evaluador</td>
<td>Rol</td>
</tr>
</tfoot>
<tbody>
</tbody>
</table>