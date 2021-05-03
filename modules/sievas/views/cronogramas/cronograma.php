
<style>
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
	<i class="glyphicon glyphicon-print"></i></a>--> <?php echo $t->__('Cronograma de evaluación', Auth::info_usuario('idioma')); ?> :: <?php echo $evaluacion ?></h4>
<hr/>

<table class="table print">
  <thead>
    <tr>
        <th width="16%" height="40" class="print"><?php echo $t->__('Actividad', Auth::info_usuario('idioma')); ?></th>     
        <th width="7%" class="print"><?php echo $t->__('Inicia', Auth::info_usuario('idioma')); ?></th>
        <th width="7%" class="print"><?php echo $t->__('Finaliza', Auth::info_usuario('idioma')); ?></th>           
        <th width="15%" class="print"><?php echo $t->__('Medio', Auth::info_usuario('idioma')); ?></th>
        <th width="15%" class="print"><?php echo $t->__('Notas', Auth::info_usuario('idioma')); ?></th>
        <th width="15%" class="print"><?php echo $t->__('Responsable(s)', Auth::info_usuario('idioma')); ?></th>
        <th width="15%" class="print"><?php echo $t->__('Invitado(s)', Auth::info_usuario('idioma')); ?></th>
        <th width="5%"><?php echo $t->__('Actas', Auth::info_usuario('idioma')); ?></th>
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
          <td class="print"><ul class="lista"><?php foreach($c['responsables'] as $r){ ?>
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
          </div></td>          
      </tr>
      <?php if(count($c['itinerario']) > 0){ foreach($c['itinerario'] as $k => $i){?>
      <tr>
          <td class="print"><?php echo ($key+1).'.'.($k+1).'. '.$i['actividad'] ?></td>
          <td class="print"><?php echo $i['fecha_inicia'] ?></td>
          <td class="print"><?php echo $i['fecha_fin'] ?></td>
          <td class="print"><?php echo $i['medio'] ?></td>
          <td class="print"><?php echo urldecode($i['anotaciones']) ?></td>
          <td class="print"></td>
          <td class="print"></td>
          <td></td>          
      </tr>
      <?php }} ?>
       <?php 
   }
} ?>   
</tbody>
</table>

          

          
            



