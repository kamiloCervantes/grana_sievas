         

<h4 class="sub-header">Crear cronograma</h4>
<hr/>

<form id="addCronograma" autocomplete="on">              
  <div class="panel panel-default actividad_tpl hide">
    <div class="panel-body">  
        <div class="col-sm-12">
            <a href="#" class="btn btn-danger btn-xs pull-right eliminar-actividad"><i class="glyphicon glyphicon-trash"></i></a>
        </div>
      <div class="form-group col-sm-4" >
          <label class="control-label">Actividad N° <span id="nro_actividad"></span></label>
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
      
      <div class="form-group col-sm-2">
        <label class="control-label">Fecha inicia</label>
         <div class="input-group">
          <input type="text" class="form-control fecha fecha_inicio" data-date-format="yyyy-mm-dd">
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>                
      </div>  
      <div class="form-group col-sm-2">
        <label class="control-label">Fecha fin</label>
         <div class="input-group">
          <input type="text" class="form-control fecha fecha_fin" data-date-format="yyyy-mm-dd">
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>                 
      </div>
        
      <div class="form-group col-sm-8">
        <label class="control-label">Anotaciones</label>
        <div class="anotaciones summernote_modal" data-title="Anotaciones" style="overflow:auto; min-height: 60px">
          <input type="text" class="form-control">
        </div>            
      </div>
      
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

<div class="add-more" style="width:100%; height:45px; border:2px dashed #aaa; color:#aaa; padding:10px; 
		text-align:center; cursor:pointer"><i class="glyphicon glyphicon-plus-sign"></i> Nueva actividad</div>

<div class="col-sm-12">
  <div class="form-actions" style="text-align: center"><br/>
    <a class="btn btn-primary" href="#" id="guardar-form">Guardar</a>
    <a class="btn btn-danger" href="#">Cancelar</a>
  </div>
</div>

</form>
          