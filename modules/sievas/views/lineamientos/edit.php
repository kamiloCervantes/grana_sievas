    <style>
        .ventanaAgregar .modal-dialog{
            width: 90%;
        }
        
        
        
    </style> 

    <h4>
        <?php echo $conjunto != null ? 'Editar conjunto de lineamientos' : 'Agregar conjunto de lineamientos'?>        
    </h4>   
    <hr/>
    <?php if($conjunto > 0){ ?>
    <div class="form-group">
        <label>Nombre de conjunto de lineamientos</label>
        <input type="text" name="nom_conjunto" id="nom_conjunto" class="form-control" value="<?php echo $nom_conjunto ?>">
    </div>
    <br/>
    <div class="row">        
        <!-- Arbol de lineamientos -->
        <div class="col-sm-6">
            <div style="overflow:auto">                        
                <div id="arbol" class="arbol" style="border: 1px solid #eee;min-height: 400px" data-conjunto="0">

                </div>            
            </div>
        </div>
        
        <!-- Formulario -->
        <div class="col-sm-6">            
            <form class="form-horizontal" autocomplete="on" id='formLineamiento'>                               
                <div class="form-group">                   
                    <label><a href="#" class="jstree-draggable"><i class="glyphicon glyphicon-arrow-left" style="border: 1px solid #888; padding: 1px; padding-bottom: 3px; color: #888;"></i></a> Lineamiento</label>
                    <textarea name="lineamiento" class="form-control" id="lineamiento" rows="5"></textarea>                                          
                </div>  
                <input id="conjunto" type="hidden" value="<?php echo $conjunto != null ? $conjunto : '0'?>">
            </form>
        </div>
        
    </div>
    <br/>
    <div style="text-align:center">
        <a href="#" id="salir" class="btn btn-primary">Guardar y salir</a>
    </div>
    <?php } else { ?>
    <p>El conjunto de lineamientos seleccionado se encuentra en uso y no se puede editar.</p>
    <a href="index.php?mod=sievas&controlador=lineamientos&accion=conjuntos_lineamientos" class="btn btn-default">Volver</a>
    <?php } ?>