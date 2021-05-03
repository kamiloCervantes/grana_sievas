
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
		vertical-align:middle;
		padding: 0;
    }
	   
    th{
        background: #FBEFF2;
		font-size: 12px;
		text-align:center;
		color: #800000;
    }
    
    thead{
		background: #800000;
		font-size: 14px; 
		vertical-align:middle; 
		font-weight:bold; 
		color:#FFFFFF; 
		text-align:center;
    }    
</style>


<h4 class="sub-header"> 
    Gráficos de Evaluación 
    <select id="tipo_momento" style="width:120px">
        <option value="1">Interna</option>
        <option value="2">Externa</option>
    </select>
</h4>
<hr/>

<div role="tabpanel">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
      <!--<li role="presentation" class="active"><a href="#interactivo" aria-controls="interactivo" role="tab" data-toggle="tab">Reporte interactivo</a></li>-->
      <li role="presentation" class="active"><a href="#profile"   aria-controls="profile" role="tab" data-toggle="tab">Reporte por rubros</a></li>
      <li role="presentation"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">Reporte general</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane" id="home">
        <br/>
         
    </div>
    <div role="tabpanel" class="tab-pane" id="general">
        <br/>
        <div class="panel panel-primary" >
              <div class="panel-heading">
                <h3 class="panel-title">Grafindicámetro general</h3>
              </div>
              <div class="panel-body">
                <div class="col-sm-5">
                  <canvas class="grafindicametro-general" width="400" height="400" style="width: 100%" data-datos='<?php echo $calificaciones_generales == null ? '0,0,0,0,0,0,0,0,0,0' : $calificaciones_generales?>'>[No canvas support]</canvas>           
                </div>
                <div class="col-sm-7">
                    <!-- items -->
                    <?php foreach($nom_rubros as $idx=>$r){  ?>
                    <p><?php echo $idx+1; ?>. <?php echo $r; ?></p>
                    <?php } ?>
                </div>

              </div>
            </div>  
    </div>
    <div role="tabpanel" class="tab-pane active" id="profile">
        <br/>
        <?php 
        
        $i = 1; 
        foreach ($rubros as $ri=>$r){  ?>
        <div class="panel panel-primary" >
              <div class="panel-heading">
                <h3 class="panel-title"> <?php echo "$i. ".$r['nom_lineamiento'] ; ?> <?php //var_dump(implode(',',$r['calificaciones']));?></h3>
              </div>
              <div class="panel-body">
                <div class="col-sm-5">
                  <canvas class="grafindicametro grafindicametro-rubros" style="width: 100%" width="400" height="400" data-datos='<?php  echo $r['calificaciones'] == null ? '0,0,0,0,0,0,0,0,0,0' : implode(',',$r['calificaciones'])?>'>[No canvas support]</canvas>  
                  
                </div>
                <div class="col-sm-7">
                    <!-- items -->
                    <?php foreach($r['lineamientos'] as $idx=>$l){  ?>
                    <p><?php echo $idx+1; ?>. <?php echo $l['nom_lineamiento']; ?></p>
                    <?php } ?>
                    <br/>
                    <p><b>Promedio del rubro : <span class="promedio-rubro"><?php echo round($calificaciones_generales[$ri],1); ?></span></b></p>
                </div>

              </div>
            </div>
            <?php $i++; } ?>     
    </div>
  </div>

</div>
 
    

          
            



