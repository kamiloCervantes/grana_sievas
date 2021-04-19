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
    
    .observacion-content{
        min-height: 300px;
    }
    
    #observaciones{
        margin-top: 20px;
    }
</style>
          <h4 class="sub-header"><?php echo $observacion['tema'] ?></h4>
          <hr/>
          <br/>
          <div class="row">
          <div id="observaciones" class=" col-sm-9">
              
                  <div class="observacion">
                  <div class="observacion-content fresh">
                      <p><?php echo urldecode($observacion['comentario']) ?></p>
                  </div>
              </div>
                 
              
          </div>
               <div class="col-sm-3">
                   <div id="autor" style="text-align: center">
                       <img src="<?php echo $observacion['ruta'] == null ? 'public/img/user.png' : $observacion['ruta']?>" style="width: 128px;height:128px">  
                       <p style="font-size: 11px"><?php echo $observacion['nombres']?></p>
                   </div>
                   <div id="datos-observacion">
                       <table class="table">
                           <tr>
                               <td><?php echo $observacion['tipo_categoria'] == 1 ? 'Rubro:' : 'Área'?></td>
                               <td><?php echo $observacion['categoria']?></td>
                           </tr>
                           <tr>
                               <td>Fecha:</td>
                               <td><?php echo $observacion['fecha']?></td>
                           </tr>
                       </table>
                   </div>
                   <div id="cita-bibliografica">
                       <p>Cita bibliográfica:</p>
                       <textarea class="form-control" style="min-height: 250px; height: auto"><?php echo $observacion['nombres'].', '.$observacion['tema'].', '.$observacion['lugar'].', '.substr($observacion['fecha'],0,4).', Editorial Grana, Link: http://certification-grana.org/sievas/index.php?mod=sievas&controlador=observatorio&accion=ver_observacion&id='.$observacion['id']?></textarea>
                   </div>
                </div>
              </div>
              