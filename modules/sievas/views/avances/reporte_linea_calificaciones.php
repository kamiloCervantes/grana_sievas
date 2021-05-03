

<h4 class="sub-header">
    Datos comparativos de ítems de evaluación
</h4>
<hr/>

<div class="row"
    <div class="col-sm-12">
        <div style="text-align: center">
            <span class="label label-default" style="background: #ff8e00">Evaluación interna actual</span>
            <span class="label label-default" style="background: #41db00">Evaluación externa actual</span>
            <span class="label label-default" style="background: rgba(200,50,100,0.4)">Evaluación interna anterior</span>
            <span class="label label-default" style="background: #ff0000">Evaluación externa anterior</span>
        </div>
        <canvas id="cvs" width="1200" height="500" style="width: 100%" data-tooltips="<?php echo $tooltips?>" 
                data-labels="<?php echo $labels?>" data-eiac="<?php echo $data['einternaact'];?>"
                data-eeac="<?php echo $data['eexternaact'];?>" data-eian="<?php echo $data['einternaant'];?>"
                data-eean="<?php echo $data['eexternaant'];?>" data-max="<?php echo $max;?>"
                >[No canvas support]</canvas>
    </div>
</div>