<style>
    .popover{
       width: 360px !important;
    }
</style>
            <h4 class="sub-header">Configurar evaluación</h4>
            <hr/>
          <br/>
          <form id="formEvaluacion" autocomplete="on">
              <div id="alert_config" class="hide">
                  <div class="alert alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <p></p>
                  </div>
              </div>
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Tablero de control</h3>
              </div>
              <div class="panel-body">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" id="tablero_control" <?php echo ($tablero != null ? ($tablero == 1 ? 'checked': ''): '') ?>> Habilitar tablero de control
                    </label>
                  </div>
              </div>
            </div>
           <div class="form-actions" style="text-align: center">
              <a class="btn btn-primary" href="#" id="guardar-form">Guardar</a>
              <a class="btn btn-danger" href="index.php?mod=sievas&controlador=evaluaciones&accion=listar_evaluaciones">Cancelar</a>
            </div>
            <input type="hidden" id="ev_id" value="<?php echo $config['ev_id'] ?>">
          </form>


