    <h4>
        Evaluación complementaria 
            <?php echo $_GET['momento'] == 1 ? 'interna' : '' ?>
            <?php echo $_GET['momento'] == 2 ? 'externa' : '' ?>
        :: <?php echo $evaluacion ?>
    </h4>   
    <hr/>
    <form method="post">
        <input type="hidden" id="evaluacion_complementaria_id" value="<?php echo $ev_comp['id'] ?>" />
        <textarea class="form-control summernote hide" rows="10" name="ev_complemento"><?php echo $ev_comp['evaluacion'] != null ? $ev_comp['evaluacion'] : ''; ?></textarea>
        <br/>
        <div id="anexos" style="border: 1px solid #E1B5AE; min-height:100px;">
            <div class="anexos-title"><label class="subtitulo"><?php echo $t->__('Anexos', Auth::info_usuario('idioma')); ?></label></div> 
            <div class="anexos-menu" style="background: #eee; padding: 0px 3px 3px 25px;">
                    <a href="#" class="agregar-anexo" style="color:#444; font-size:14px"><i class="glyphicon glyphicon-plus"></i> <?php echo $t->__('Agregar', Auth::info_usuario('idioma')); ?></a>
                    <a href="#" class="insertar-url" style="color:#444; font-size:14px"><i class="glyphicon glyphicon-link"></i> <?php echo $t->__('Insertar URL', Auth::info_usuario('idioma')); ?></a></div>            
            <div class="anexos-list" style="padding: 0; max-height: 140px; overflow:auto">
                <table style="width: 100%" id='anexos'>
                    <tbody>
                        <?php foreach($anexos as $key=>$a) { ?>
                        <tr>
                            <td style="padding: 4px"><?php echo ($key+1) ?></td>
                            <td style="padding: 4px"><a target="_blank" class="btn btn-xs btn-default" href="<?php echo $a['ruta'] ?>"><i class="glyphicon glyphicon-file"></i> <?php echo $a['nombre'] ?></a></td>
                            <td style="padding: 4px"><a data-id="<?php echo $a['id'] ?>" class="btn btn-xs btn-danger eliminar-anexo" href="#"><i class="glyphicon glyphicon-trash"></i></a></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <br/>
        <input type="submit" class="btn btn-primary" value="Guardar">
    </form>
<div class="insertar-url-form-tpl hide">
    <form>
        <div class="form-group">
            <label>URL <span style="color: #ccc; font-size: 10px">(Debe empezar con http:// o https://)</span></label>
            <input class="form-control url" type="text">
        </div>
        <div class="form-group">
            <label>Nombre</label>
            <input class="form-control nombre" type="text">
        </div>
    </form>
</div>
    
    