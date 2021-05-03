<style>
    #temas{
        padding: 10px;
        background: #800000;
        border-radius: 5px;
    }
    
    #temas table td{
        padding: 10px;
        border-bottom: 1px solid #ccc;
        background: #fff;
    }
    
    #temas table tr:hover td{
        background: #ffff99;
        cursor: pointer;
    }
</style>
<h5>
    <span style="font-size: 16px"><?php echo $t->__('Foro de comité evaluador', Auth::info_usuario('idioma')); ?> :: <?php echo $evaluacion ?></span></b> 
</h5>
<br/>
<div id="controles">    
    <div class="row">
        <div id="breadcrumb_foro" class="col-sm-6">
            <a href="#"><?php echo $t->__('Foro', Auth::info_usuario('idioma')); ?></a><span style="font-weight: bold;font-size:14px"> / </span>
        </div>
        <div class="col-sm-6">
            <a href="index.php?mod=sievas&controlador=foro&accion=agregar<?php echo $_GET['momento'] > 0 ? '&momento='.$_GET['momento'] : ''?>" class="btn btn-primary pull-right"><?php echo $t->__('Agregar tema', Auth::info_usuario('idioma')); ?></a>
        </div>
    </div>
</div>
<br/>
<br/>
<div id="temas">
    <table style="width:100%">
        <?php foreach($temas as $tem){ ?>
        <tr class="tema" data-id="<?php echo $tem['id'] ?>">
            <td><span style="font-size: 14px; font-weight:bold;display:block"><?php echo $tem['tema'] ?></span> <span style="font-size: 11px"><?php echo $t->__('Comentarios', Auth::info_usuario('idioma')); ?> : <?php echo $tem['comentarios'] ?></span></td>
            <td><?php echo $tem['nombres'] ?></td>
        </tr>
        <?php } ?>               
    </table>
</div>