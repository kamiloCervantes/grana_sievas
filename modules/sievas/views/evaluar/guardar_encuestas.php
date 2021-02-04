<h5>
    <b> <span style="font-size: 16px">Guardar encuestas</span></b> 
</h5>
<hr/>

 <div class="panel panel-primary">              
  <div class="panel-heading">
    <h3 class="panel-title">
        Encuesta a docentes
        <?php if(!is_null( $encuestas[0]['url_encuesta']))?>
        <a href="<?php echo $encuestas[0]['url_encuesta'] ?>" class="pull-right" style="color: #fff"><i class="glyphicon glyphicon-link"></i>Link de encuesta</a>
    </h3>
    
  </div>
  <div class="panel-body">  
      <?php if($predictamen == null){ ?>
      <div class="predictamen_link hide">
          <a href="#" target="_blank"></a>
      </div>
      <form action="index.php?mod=sievas&controlador=evaluar&accion=subirarchivo&type=encuesta&id=<?php echo $e_id ?>" class="dropzone" id="predictamen-dropzone">
          <div class="dz-message">
              <h2>Subir encuesta a docentes</h2>
              <p>Arrastre y suelte el archivo</p>
          </div>
      </form>   
      <?php } else { ?>
      <div class="predictamen_link">
          <a href="<?php echo $predictamen['ruta'] ?>" target="_blank"><?php echo $predictamen['nombre'] ?></a><a href="#" class="modificar_predictamen"> [Modificar]</a>
      </div>
      <form action="index.php?mod=sievas&controlador=evaluar&accion=subirarchivo&type=encuesta&id=<?php echo $e_id ?>" class="dropzone hide" id="predictamen-dropzone">
          <div class="dz-message">
              <h2>Subir encuesta a docentes</h2>
              <p>Arrastre y suelte el archivo</p>
          </div>
      </form>  
      <?php } ?>
  </div>         
</div>  
 <div class="panel panel-primary">              
  <div class="panel-heading">
    <h3 class="panel-title">Encuesta a directivos
      <?php if(!is_null( $encuestas[1]['url_encuesta']))?>
        <a href="<?php echo $encuestas[1]['url_encuesta'] ?>" class="pull-right" style="color: #fff"><i class="glyphicon glyphicon-link"></i>Link de encuesta</a>
    </h3>
  </div>
  <div class="panel-body">  
      <?php if($predictamen == null){ ?>
      <div class="predictamen_link hide">
          <a href="#" target="_blank"></a>
      </div>
      <form action="index.php?mod=sievas&controlador=evaluar&accion=subirarchivo&type=encuesta&id=<?php echo $e_id ?>" class="dropzone" id="predictamen-dropzone">
          <div class="dz-message">
              <h2>Subir encuesta a directivos</h2>
              <p>Arrastre y suelte el archivo</p>
          </div>
      </form>   
      <?php } else { ?>
      <div class="predictamen_link">
          <a href="<?php echo $predictamen['ruta'] ?>" target="_blank"><?php echo $predictamen['nombre'] ?></a><a href="#" class="modificar_predictamen"> [Modificar]</a>
      </div>
      <form action="index.php?mod=sievas&controlador=evaluar&accion=subirarchivo&type=encuesta&id=<?php echo $e_id ?>" class="dropzone hide" id="predictamen-dropzone">
          <div class="dz-message">
              <h2>Subir encuesta a directivos</h2>
              <p>Arrastre y suelte el archivo</p>
          </div>
      </form>  
      <?php } ?>
  </div>         
</div>  
 <div class="panel panel-primary">              
  <div class="panel-heading">
    <h3 class="panel-title">Encuesta a estudiantes
      <?php if(!is_null( $encuestas[2]['url_encuesta']))?>
        <a href="<?php echo $encuestas[2]['url_encuesta'] ?>" class="pull-right" style="color: #fff"><i class="glyphicon glyphicon-link"></i>Link de encuesta</a></h3>
  </div>
  <div class="panel-body">  
      <?php if($predictamen == null){ ?>
      <div class="predictamen_link hide">
          <a href="#" target="_blank"></a>
      </div>
      <form action="index.php?mod=sievas&controlador=evaluar&accion=subirarchivo&type=encuesta&id=<?php echo $e_id ?>" class="dropzone" id="predictamen-dropzone">
          <div class="dz-message">
              <h2>Subir encuesta a estudiantes</h2>
              <p>Arrastre y suelte el archivo</p>
          </div>
      </form>   
      <?php } else { ?>
      <div class="predictamen_link">
          <a href="<?php echo $predictamen['ruta'] ?>" target="_blank"><?php echo $predictamen['nombre'] ?></a><a href="#" class="modificar_predictamen"> [Modificar]</a>
      </div>
      <form action="index.php?mod=sievas&controlador=evaluar&accion=subirarchivo&type=encuesta&id=<?php echo $e_id ?>" class="dropzone hide" id="predictamen-dropzone">
          <div class="dz-message">
              <h2>Subir encuesta a estudiantes</h2>
              <p>Arrastre y suelte el archivo</p>
          </div>
      </form>  
      <?php } ?>
  </div>         
</div>  
 <div class="panel panel-primary">              
  <div class="panel-heading">
    <h3 class="panel-title">Encuesta a egresados
      <?php if(!is_null( $encuestas[3]['url_encuesta']))?>
        <a href="<?php echo $encuestas[3]['url_encuesta'] ?>" class="pull-right" style="color: #fff"><i class="glyphicon glyphicon-link"></i>Link de encuesta</a></h3>
  </div>
  <div class="panel-body">  
      <?php if($predictamen == null){ ?>
      <div class="predictamen_link hide">
          <a href="#" target="_blank"></a>
      </div>
      <form action="index.php?mod=sievas&controlador=evaluar&accion=subirarchivo&type=predictamen&id=<?php echo $e_id ?>" class="dropzone" id="predictamen-dropzone">
          <div class="dz-message">
              <h2>Subir encuesta a egresados</h2>
              <p>Arrastre y suelte el archivo</p>
          </div>
      </form>   
      <?php } else { ?>
      <div class="predictamen_link">
          <a href="<?php echo $predictamen['ruta'] ?>" target="_blank"><?php echo $predictamen['nombre'] ?></a><a href="#" class="modificar_predictamen"> [Modificar]</a>
      </div>
      <form action="index.php?mod=sievas&controlador=evaluar&accion=subirarchivo&type=predictamen&id=<?php echo $e_id ?>" class="dropzone hide" id="predictamen-dropzone">
          <div class="dz-message">
              <h2>Subir encuesta a egresados</h2>
              <p>Arrastre y suelte el archivo</p>
          </div>
      </form>  
      <?php } ?>
  </div>         
</div>  