<style>
    .comentario-contenido{
        padding: 10px;
        background:#fff;
        border-top-right-radius: 2px;
        border-top-left-radius: 2px;
        border: 1px solid #800000;
    }
    .comentario-acciones{
        background: #800000;
        height: 30px;
        padding: 5px;
    }
    
    .comentario-acciones a{
        color: #fff;
    }
    
    
</style>
<h4 class="sub-header"><?php if(($comentarios[0]['cod_autor'] == $persona) || Auth::info_usuario('rol') == '1'){ ?> <a href="#" data-id="<?php echo $comentarios[0]['tema_id']?>" class="eliminar-tema btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> <?php echo $t->__('Eliminar tema', Auth::info_usuario('idioma')); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?><?php echo $comentarios[0]['tema']?></h4>
<hr/>
<div id="controles">    
    <div class="row">
        <div id="breadcrumb_foro" class="col-sm-12">
            <a href="index.php?mod=sievas&controlador=foro&accion=index<?php echo $momento > 0 ? '&momento='.$momento : ''?>"><?php echo $t->__('Foro', Auth::info_usuario('idioma')); ?></a><span style="font-weight: bold;font-size:14px"> / </span>
            <a href="#"><?php echo $comentarios[0]['tema']?></a><span style="font-weight: bold;font-size:14px"> / </span>
        </div>        
    </div>
</div>
<br/>
<br/>
<div id="comentarios">
    <?php $i=1; foreach($comentarios as $c){ ?>
    <div class="comentario">
        <div class="col-sm-2" style="text-align:center">
            <img src="<?php echo $c['ruta'] == null ? 'public/img/user.png' : $c['ruta'] ?>" style="width: 150px; height: 128px">
            <span style="font-size: 11px;"><?php echo $c['nombres']?></span>
        </div>
        <div class="col-sm-10">
            
            <div class="comentario-contenido" style="min-height: 128px">
                <p><span><b>#<?php echo $i?></b></span>
                    <span class="pull-right"><b><?php echo $c['fecha_comentario']?></b></span></p>
            <?php echo urldecode($c['comentario'])?>
            </div>
            <div class="comentario-acciones">
                <table width="200">
                    <tr>
                        <td><a href="index.php?mod=sievas&controlador=foro&accion=responder&tema=<?php echo $comentarios[0]['tema_id']?><?php echo $momento > 0 ? '&momento='.$momento : ''?>"><?php echo $t->__('Responder', Auth::info_usuario('idioma')); ?></a></td>
                        <?php if(($c['comentario_autor'] == $persona) || Auth::info_usuario('rol') == '1'){ ?>                        
                        <td><a href="index.php?mod=sievas&controlador=foro&accion=editar&id=<?php echo $c['comentario_id']?>"><?php echo $t->__('Editar', Auth::info_usuario('idioma')); ?></a></td>
                        <td><a href="#" class="eliminar-comentario" data-id="<?php echo $c['comentario_id']?>"><?php echo $t->__('Eliminar', Auth::info_usuario('idioma')); ?></a></td>
                        <?php } ?>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php $i++; } ?>  
    <a name="end"></a>
</div>
