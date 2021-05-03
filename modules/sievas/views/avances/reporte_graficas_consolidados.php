
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
    Gráficos Consolidados de Evaluación 
</h4>
<hr/>

<div role="tabpanel">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Reporte comparativo General</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Reporte comparativo por rubros</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">
        <br/>
         <div class="panel panel-primary" >
              <div class="panel-heading">
                <h3 class="panel-title">Grafindicámetro comparativo general</h3>
              </div>
              <div class="panel-body">
                  <div class="row">
                      <div class="col-sm-6">
                        <h4 style="text-align: center">Evaluación interna</h4>
                        <canvas class="grafindicametro-general-interna interna" width="400" height="400" style="width: 100%" data-datos='<?php echo $calificaciones_generales[1] == null ? '0,0,0,0,0,0,0,0,0,0' : $calificaciones_generales[1]?>'>[No canvas support]</canvas>           
                      </div>
                      <div class="col-sm-6">
                        <h4 style="text-align: center">Evaluación externa</h4>
                        <canvas class="grafindicametro-general-externa externa" width="400" height="400" style="width: 100%" data-datos='<?php echo $calificaciones_generales[2] == null ? '0,0,0,0,0,0,0,0,0,0' : $calificaciones_generales[2]?>'>[No canvas support]</canvas>           
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-sm-12">
                        <!-- items -->
                        <p>
                        <?php foreach($rubros as $idx=>$r){  ?>
                        <?php echo $idx+1; ?>. <?php echo $r['nom_lineamiento']. ','; ?>
                        <?php } ?>
                        </p>
                    </div>
                  </div>
                

              </div>
            </div> 
    </div>
    <div role="tabpanel" class="tab-pane" id="profile">
        <br/>
        <?php 
        
        $i = 1; 
        foreach ($rubros as $r){  ?>
        <div class="panel panel-primary" >
              <div class="panel-heading">
                <h3 class="panel-title"> <?php echo "$i. ".$r['nom_lineamiento']; ?></h3>
              </div>
              <div class="panel-body">
                  <div class="row">
                      <div class="col-sm-6">
                        <h4 style="text-align: center">Evaluación interna</h4>
                        <canvas class="grafindicametro-rubros-interna interna" width="400" height="400" style="width: 100%" data-datos='<?php echo $r[1]['calificaciones'] == null ? '0,0,0,0,0,0,0,0,0,0' : implode(',',$r[1]['calificaciones'])?>'>[No canvas support]</canvas>                   
                      </div>
                      <div class="col-sm-6">
                        <h4 style="text-align: center">Evaluación externa</h4>
                        <canvas class="grafindicametro-rubros-externa externa" width="400" height="400" style="width: 100%"  data-datos='<?php echo $r[2]['calificaciones'] == null ? '0,0,0,0,0,0,0,0,0,0' : implode(',',$r[2]['calificaciones'])?>'>[No canvas support]</canvas>                   
                      </div>
                  </div>
                
                <div class="col-sm-12">
                    <!-- items -->
                    <p>
                    <?php foreach($r[1]['lineamientos'] as $idx=>$l){  ?>
                    <?php echo $idx+1; ?>. <?php echo $l['nom_lineamiento']. ', '; ?> 
                    <?php } ?>
                    </p>
                </div>

              </div>
            </div>
            <?php $i++; } ?>     
    </div>
    <div role="tabpanel" class="tab-pane" id="interactivo">
        <br/>
        <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Grafindicametros comparativos de rubros</h3>
            </div>
            <div class="panel-body">
                
              <div class="col-sm-5">
                  <canvas id="cvs" width="400" height="400">[No canvas support]</canvas>                      
              </div>
              <div class="col-sm-7">
                  <label class="control-label">Rubro</label>
                  <input type="hidden" name="rubro" id="rubro" class="form-control">  
                  <br/>
                  <div id="items"></div>
              </div>

            </div>
          </div>   
    </div>
  </div>

</div>
 
    

          
            



