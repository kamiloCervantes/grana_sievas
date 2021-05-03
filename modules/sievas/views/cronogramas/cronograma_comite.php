
<style>
    .wide .modal-dialog{
        width: 800px;
    }
    
    h3.popover-title{
        color: #222;
    }
    
    .popover-content label{
        color: #222;
    }
    
    .table thead>tr>th,.table tbody>tr>th,.table tfoot>tr>th,.table thead>tr>td,.table tbody>tr>td,.table tfoot>tr>td{
        border: 1px solid #F5A9BC; 
    }
    
    tbody tr:nth-child(even) td{
        background: #dfa5a9;
    }
	
    .table ul.lista{		
        padding: 0;
        margin-left: 11px;
    }
    
    .table ul.lista li{
        list-style: square;
    }
     
	td{
        font-size: 12px;
		padding: 0;
    }
	   
    th{
        font-size: 12px;
		text-align:center;
		color: #fff;
    }
    
    thead{
        background: #800000;
    }    
</style>


<h4 class="sub-header"><!--<a href="#" id="imprimir">
	<i class="glyphicon glyphicon-print"></i></a>--> <?php echo $t->__('Cronograma de comité de evaluación', Auth::info_usuario('idioma')); ?></h4>
<hr/>
<?php if($privilegio == 1){ ?>
<a href="#" class="btn btn-primary add_actividad"><i class="glyphicon glyphicon-plus"></i> Nueva actividad</a>
<br/><br/>
<?php } ?>
<table class="table print" id="cronograma">
  <thead>
    <tr>
        <th width="16%" height="40" class="print"><?php echo $t->__('Actividad', Auth::info_usuario('idioma')); ?></th>     
        <th width="7%" class="print"><?php echo $t->__('Inicia', Auth::info_usuario('idioma')); ?></th>
        <th width="7%" class="print"><?php echo $t->__('Finaliza', Auth::info_usuario('idioma')); ?></th>           
        <th width="15%" class="print"><?php echo $t->__('Medio', Auth::info_usuario('idioma')); ?></th>
        <th width="15%" class="print"><?php echo $t->__('Notas', Auth::info_usuario('idioma')); ?></th>
        <th width="3%" class="print">&nbsp;</th>
<!--        <th width="15%" class="print"><?php echo $t->__('Responsable(s)', Auth::info_usuario('idioma')); ?></th>
        <th width="15%" class="print"><?php echo $t->__('Invitado(s)', Auth::info_usuario('idioma')); ?></th>
        <th width="5%"><?php echo $t->__('Actas', Auth::info_usuario('idioma')); ?></th>-->
    </tr>
  </thead>

<tbody><?php 

if($cronograma != null){ 
	foreach($cronograma as $key=>$c){?>
      <tr>
          <td class="print"><?php echo ($key+1).'. '.$c['actividad'] ?></td>
          <td class="print"><?php echo $c['fecha_inicia'] ?></td>
          <td class="print"><?php echo $c['fecha_fin'] ?></td>
          <td class="print"><?php echo $c['medio'] ?></td>
          <td class="print"><?php echo urldecode($c['anotaciones']) ?></td>
          <td class="print">
             <?php if($privilegio == 1){ ?>
              <a href="#" data-id="<?php echo $c['id'] ?>" class="btn btn-xs btn-default eliminar_actividad"><i class="glyphicon glyphicon-trash"></i></a>
              <?php } ?>
          </td>
<!--          <td class="print"><ul class="lista"><?php foreach($c['responsables'] as $r){ ?>
                  <li><?php echo "$r[titulo] $r[nombres] $r[primer_apellido] $r[segundo_apellido]"; } ?>    
              </ul></td>
          <td class="print"><ul class="lista"><?php foreach($c['invitados'] as $r){ ?>
                  <li><?php echo "$r[titulo] $r[nombres] $r[primer_apellido] $r[segundo_apellido]"; } ?>    
              </ul></td>
          <td><div class="dropdown">
            <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
              <i class="glyphicon glyphicon-list"></i> <?php echo $t->__('Acciones', Auth::info_usuario('idioma')); ?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
              <li role="presentation"><a href="#" class="cronograma-item" title="<?php echo $t->__('Subir acta', Auth::info_usuario('idioma')); ?>" 
              		data-id="<?php echo $c['id'] ?>"><?php echo $t->__('Subir acta', Auth::info_usuario('idioma')); ?></a></li>
              <li role="presentation"><a href="#" class="ver-actas" title="<?php echo $t->__('Ver actas', Auth::info_usuario('idioma')); ?>" 
              		data-id="<?php echo $c['id'] ?>"><?php echo $t->__('Ver actas', Auth::info_usuario('idioma')); ?></a></li>
            </ul>
          </div></td>          -->
      </tr>
      <?php if(count($c['itinerario']) > 0){ foreach($c['itinerario'] as $k => $i){?>
<!--      <tr>
          <td class="print"><?php echo ($key+1).'.'.($k+1).'. '.$i['actividad'] ?></td>
          <td class="print"><?php echo $i['fecha_inicia'] ?></td>
          <td class="print"><?php echo $i['fecha_fin'] ?></td>
          <td class="print"><?php echo $i['medio'] ?></td>
          <td class="print"><?php echo urldecode($i['anotaciones']) ?></td>
          <td class="print"></td>
          <td class="print"></td>
          <td></td>          
      </tr>-->
      <?php }} ?>
       <?php 
   }
} ?>   
</tbody>
</table>

<div class="actividad_tpl hide">
    
      <div class="row">
      <div class="form-group col-sm-4" >
          <label class="control-label">Actividad<span id="nro_actividad"></span></label>
        <input type="hidden" class="form-control actividad_gen" />
      </div>
      <div class="form-group col-sm-4">
        <label class="control-label">Etapa</label>
        <input type="hidden" class="form-control etapa" />
      </div>
      <div class="form-group col-sm-4">
        <label class="control-label">Medio</label>
        <input type="hidden" class="form-control medio" />
      </div>
      </div>
        
      <div class="row">
      <div class="form-group col-sm-3">
        <label class="control-label">Fecha inicia</label>
         <div class="input-group">
          <input type="text" class="form-control fecha fecha_inicio" data-date-format="yyyy-mm-dd">
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>                
      </div>  
      <div class="form-group col-sm-3">
        <label class="control-label">Fecha fin</label>
         <div class="input-group">
          <input type="text" class="form-control fecha fecha_fin" data-date-format="yyyy-mm-dd">
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>                 
      </div>
      <div class="form-group col-sm-6">
        <label class="control-label">Anotaciones</label>
        <div class="anotaciones summernote_modal" data-title="Anotaciones" style="overflow:auto; min-height: 60px">
          <input type="text" class="form-control">
        </div>            
      </div>
      </div>
       
        <div class="row hide">
              <div class="col-sm-6">
        <div class="responsable-form hide">
            <div style="padding:10px">
            <input type="text" class="form-control responsable_select" style="width:200px" />
            <div style="padding:10px 0 20px 0"><a href="#" class="btn btn-primary btn-sm pull-right seleccionar">Seleccionar</a></div> 
            </div>
        </div>
        <table class="table responsables-table">
            <thead>
                <tr>
                    <th>Responsables <i class="glyphicon glyphicon-plus-sign add-responsable" style="color:#3276b1"></i></th>
                    <th>&nbsp;</th>                                
                </tr> 
            </thead>
            <tbody>
            </tbody> 
        </table>
          
      </div>              
      <div class="col-sm-6">
         <div class="invitado-form hide">
            <div style="padding:10px">
            <label class="control-label">Nombre</label>
            <input type="text" class="form-control invitado_nombre" style="width:200px" />
            <label class="control-label">Email</label>
            <input type="text" class="form-control invitado_email" style="width:200px" />
            <div style="padding:10px 0 20px 0"><a href="#" class="btn btn-primary btn-sm pull-right agregar-invitado">Agregar</a></div> 
            </div>
        </div>
        <table class="table invitados-table">
            <thead>
                <tr>
                    <th>Invitados <i class="glyphicon glyphicon-plus-sign add-invitado" style="color: #3276b1"></i></th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr> 
            </thead>
            <tbody>
            </tbody> 
        </table>
      </div>   
        </div> 
        
        <div class="row hide">
            <div class="col-sm-12">
                <table class="table">
                  <thead>
                      <tr>
                          <th>Itinerario de la actividad <i class="glyphicon glyphicon-plus-sign add-itinerario" style="color:#3276b1"></i></th>
                          <th>&nbsp;</th>                                
                      </tr> 
                  </thead>
                  <tbody>
                  </tbody> 
                </table>
                <div class="itinerarios-list">

                </div>
            </div>
        </div> 
</div>
          
<table class="cronograma_row_tpl hide">
    <tr>
          <td class="print">1</td>
          <td class="print">2</td>
          <td class="print">3</td>
          <td class="print">4</td>
          <td class="print">5</td>
          <td class="print"><a href="#" class="btn btn-xs btn-default eliminar_actividad"><i class="glyphicon glyphicon-trash"></i></a></td>
      </tr>
</table>
          
            



