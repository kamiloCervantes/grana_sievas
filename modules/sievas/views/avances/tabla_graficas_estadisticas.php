
<h4>
 Gráficas de tablas estadísticas
</h4>
<hr/>
<a href="index.php?mod=sievas&controlador=avances&accion=select_graficas_estadisticas" class="btn btn-default">Volver</a>
<input type="hidden" value="<?php echo $_GET['i']; ?>" id="i">
<?php foreach($indicadores as $ind){ ?>
<br/><br/>
<div class="row">
    <div class="col-sm-12">   
        <div id="cvs<?php echo $ind['indicador'] ?>" style="width: 100%"></div>       
    </div>
</div>
<?php } ?>