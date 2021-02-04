<style>
    .observacion{        
        -webkit-box-shadow: 10px 3px 11px 0px rgba(46, 50, 50, 0.58);
        -moz-box-shadow:    10px 3px 11px 0px rgba(46, 50, 50, 0.58);
        box-shadow:         10px 3px 11px 0px rgba(46, 50, 50, 0.58);
        background: #fff;
        border: 1px solid #eee;
        margin-top: 20px;
    }
    
    .fresh{
        padding: 10px 20px 4px 20px;
    }
    .observacion-actions{
        height: 50px;
        width: 100%;
        background: #eee;
    }
    
    .observacion-actions table{
        width: 100%;
    }
    
    .observacion-actions td{
        padding: 10px;
    }
    
    #observaciones{
        margin-top: 20px;
    }
</style>
            <h4 class="sub-header">Observatorio</h4>
            <hr/>
          <br/>
          <?php if($observaciones > 0){ ?>
          <div class="well">
              <h3>Gracias por compartir sus observaciones!</h3>
          </div>
          <?php } else { ?>
          <form id="observatorio">
            <?php foreach($rubros as $r){ ?>
            <div class="rubro form-group">
                <b>Observaciones sobre el rubro #<?php echo $r['num_orden'] ?></b>
                <br/>
                <br/>
                <div class="alert alert-info">
                    Rubro #<?php echo $r['num_orden'] ?>: <?php echo $r['nom_lineamiento'] ?>
                </div>
                <textarea class="summernote form-control observacion" data-rubro="<?php echo $r['id'] ?>"></textarea>
                <input type="hidden" value="<?php echo $r['id'] ?>">
            </div>
            <?php } ?>
              <div style="text-align:center">
                  <input type="submit" class="btn btn-primary" value="Guardar">
                  <a href="index.php?mod=sievas&controlador=index_sieva&accion=index" class="btn btn-danger">Cancelar</a>
              </div>              
          </form>
          <?php } ?>
          
          <!--<div id="observaciones"></div>-->