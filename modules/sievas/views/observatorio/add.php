         

<h4 class="sub-header">Agregar observación</h4>
<hr/>

<form id="addObservacion">              
  <div class="form-group">
      <label for="tema">Tema</label>
      <input type="text" class="form-control" id="tema" name="tema">
  </div>
   <div class="row">
        <div class="form-group col-sm-8">
            <label class="control-label" style="display: block">Categoría</label>
            <div class="row">
                <div class="col-sm-6">
            <input type="hidden" id="filtro" class="form-control">
                 </div>
                <div class="col-sm-6">            
             <div class="input-group">
                 <input type="text" id="selector" class="form-control">
                 <span class="input-group-addon" id="add-categoria" style="cursor: pointer">+</span>
              </div>
                  </div>
            </div>
        </div>
        <div class="form-group col-sm-4">
            <label class="control-label">Lugar</label>
            <input type="text" class="form-control" id="lugar" name="lugar">
        </div>
    </div>
  <div class="form-group">
      <textarea class="form-control summernote" id="observacion" name="observacion"></textarea>
  </div>
    
    <div class="form-actions" style="text-align: center">
        <a href="#" class="btn btn-primary" id="publicar">Publicar</a>
        <a href="index.php?mod=sievas&controlador=observatorio" class="btn btn-danger">Cancelar</a>
    </div>
</form>
          
<div id="popover"></div>