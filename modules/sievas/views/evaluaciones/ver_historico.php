<h5>
    <b> <span style="font-size: 16px">Histórico de evaluaciones</span></b> 
</h5>
<br/>
<table class="table">
    <thead>
        <tr>
            <th>Nombre evaluacion</th>
            <th>Predictamen</th>
            <th>Dictamen</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($historico_actual as $ha){ ?>
        <tr>
            <td>Evaluacion actual</td>
            <td><?php echo $ha['predictamen_ruta'] != null ? '<a href="'.$ha['predictamen_ruta'].'" target="_blank">Ver predictamen</a>' :  'NA' ?> </td>
            <td><?php echo $ha['dictamen_ruta'] != null ? '<a href="'.$ha['dictamen_ruta'].'" target="_blank">Ver dictamen</a>' :  'NA' ?> </td>
        </tr>
        <?php } ?>
        <?php foreach($historico_previo as $hp){ ?>
        <tr>
            <td><?php echo $hp['etiqueta'] ?></td>
            <td><?php echo $hp['predictamen_ruta'] != null ? '<a href="'.$hp['predictamen_ruta'].'" target="_blank">Ver predictamen</a>' :  'NA' ?> </td>
            <td><?php echo $hp['dictamen_ruta'] != null ? '<a href="'.$hp['dictamen_ruta'].'" target="_blank">Ver dictamen</a>' :  'NA' ?> </td>
        </tr>
        <?php } ?>
    </tbody>
</table>