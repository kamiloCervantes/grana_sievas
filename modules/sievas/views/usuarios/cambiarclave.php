<h4>
    <b>Cambiar clave de acceso</b>
</h4>
<br/>
<?php if($msg != ''){ ?>
    <div class="alert alert-danger" role="alert"><?php echo $msg ?></div>
<?php } ?>
<form action="index.php?mod=sievas&controlador=usuarios&accion=cambiar_clave&u=<?php echo $username ?>" method="post">
    <div class="form-group">
        <label>Nueva clave</label>
        <input type="password" name="clave" id="clave" class="form-control">
        <input type="hidden" name="username" id="username" value="<?php echo $username ?>">
    </div>
   <input type="submit" class="btn btn-primary" value="Enviar">
   <a href="#" class="btn btn-default">Cancelar</a>
</form>