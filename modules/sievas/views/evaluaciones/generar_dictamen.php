<h5>
    <b> <span style="font-size: 16px">Generar dictamen</span></b> 
</h5>
<hr/>
<br/>
<div class="document" style="border: 1px solid #ccc; background: #fff; padding: 60px;">
    
</div>
<form action="index.php?mod=sievas&controlador=evaluaciones&accion=previsualizar_dictamen" method="post" id="formDictamen">
    <div class="form-group col-sm-6">
        <label>Código dictamen</label>
        <input type="text" name="codigo_dictamen" id="codigo_dictamen" class="form-control" value="<?php echo $codigo_dictamen != '' ? $codigo_dictamen : ''; ?>">
    </div>
    <div class="form-group col-sm-6">
        <label>Clave</label>
        <input type="text" name="clave" id="clave" class="form-control" value="<?php echo $clave != '' ? $clave : ''; ?>">
    </div>
    <div class="form-group col-sm-12">
        <label>Revisión de información generada</label>
        <textarea name="titulo" id="titulo" class="form-control summernote" rows="5"><?php
            
            echo $titulo;
        ?>
        </textarea>
    </div>
    <div class="form-group col-sm-12">        
        <textarea name="introduccion" id="introduccion" class="form-control summernote" rows="10"><?php 
            //se captura del formulario
            echo $introduccion;
            ?>

        </textarea>
    </div>
    <div class="form-group col-sm-12">
        <textarea name="txt1" id="txt1" class="form-control summernote" rows="5"><?php
        
        echo $txt1;
            ?>
        </textarea>
    </div>
    <div class="form-group col-sm-12">
        <textarea name="txt2" id="txt2" class="form-control summernote" rows="5"><?php
        
        echo $txt2;
        ?></textarea>
    </div>
    <div class="form-group col-sm-12">
        <textarea name="txt3" id="txt3" class="form-control summernote" rows="5"><?php 
        
        echo $txt3;
        ?></textarea>
    </div>
    <div class="form-group col-sm-12">
        <textarea name="txt4" id="txt4" class="form-control summernote" rows="5"><?php 
        
        echo $txt4;
        ?></textarea>
    </div>
    <div class="form-group col-sm-12">
        <label>Contexto</label>
        <textarea name="contexto" id="contexto" class="form-control summernote" rows="15"><?php 
            echo $contexto != '' ? $contexto : ''; 
        ?></textarea>
    </div>
    <div class="form-group col-sm-12">
        <label>Percepción</label>
        <textarea name="percepcion" id="percepcion" class="form-control summernote" rows="15"><?php 
            echo $percepcion != '' ? $percepcion : ''; 
        ?></textarea>
    </div>
    <div class="form-actions" style="text-align: center">
        <input type="submit" class="btn btn-default" value="Previsualizar">
        <a href="#" class="btn btn-primary" id="guardar-dictamen">Guardar</a>
        <a href="#" class="btn btn-danger">Cancelar</a>
    </div>
</form>