<h4 class="sub-header"><?php echo $t->__('Responder tema', Auth::info_usuario('idioma')); ?></h4>
<hr/>

<form id="addTema">              
  <div class="form-group">
      <textarea class="form-control summernote" id="comentario" name="comentario"></textarea>
  </div>   
    <div class="form-actions" style="text-align: center">
        <a href="#" class="btn btn-primary" id="add-comentario"><?php echo $t->__('Enviar respuesta', Auth::info_usuario('idioma')); ?></a>
        <a href="index.php?mod=sievas&controlador=foro&accion=ver_tema&id=<?php echo $_GET['tema']?>" class="btn btn-danger"><?php echo $t->__('Cancelar', Auth::info_usuario('idioma')); ?></a>
    </div>
</form>
