var sieva_evaluar = {};

sieva_evaluar.items = [];
sieva_evaluar.plan_mejoramiento = [];

sieva_evaluar.init = function(){
    
    //replicacion de datos
    $('#replicar-item').on('click', function(e){
        e.preventDefault();
       var momento_evaluacion = $('#guardar-item').data('momento_evaluacion');
       var momento_resultado = $('#guardar-item').data('momento_resultado');
       var momento = $('#guardar-item').data('momento');
       if(momento_resultado > 0){
            var html = $('.replicar-select-form-tpl').clone();
            html.removeClass('hide');
            html.removeClass('replicar-select-form-tpl');
            html.addClass('replicar-select-form');
            bootbox.dialog({
            message: html,
            title: "Seleccionar evaluaciones",
            buttons: {
              success: {
                label: "Aceptar",
                className: "btn-success",
                callback: function() {                    
                    var evaluaciones = $.map($('.evaluaciones_centro'), function(val,idx){
                        if($(val).prop('checked') == true){
                            return $(val).val();
                        }
                    });
                    if(evaluaciones.length > 0){
                        var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=replicar_indicador', {
                           evaluaciones : evaluaciones,
                           momento : momento,
                           momento_resultado : momento_resultado,
                           momento_evaluacion : momento_evaluacion
                        });
                        
                        jqxhr.done(function(){
                           bootbox.alert('El item fue replicado correctamente'); 
                        });
                        
                        jqxhr.fail(function(err){
                            bootbox.alert(err.responseText);
                        })
                    }
                    else{
                        return false;
                    }
                    
                }
              },
              cancel: {
                label: "Cancelar",
                className: "btn-danger",
                callback: function() {


                }
              }

            }
          });
       }
       else{
           bootbox.alert("Debe guardar el ítem para poder replicarlo");
       }
    });
    
    //tablas estadisticas en linea
    $('.ingresar-tabla-estadistica').on('click', function(){
        var html = $('.ingresar-tabla-estadistica-tpl').clone();
        html.removeClass('hide');
        html.removeClass('ingresar-tabla-estadistica-tpl');
        html.addClass('ingresar-tabla-estadistica');
        bootbox.dialog({
        message: html,
        className: 'modal70',
        title: "Insertar URL",
        buttons: {
          success: {
            label: "Aceptar",
            className: "btn-success",
            callback: function() {
              
              
            }
          }
          
        }
      });
    });
    
    
    //url de anexos
    $('.insertar-url').on('click', function(e){
        e.preventDefault();
        var html = $('.insertar-url-form-tpl').clone();
        html.removeClass('hide');
        html.removeClass('insertar-url-form-tpl');
        html.addClass('insertar-url-form');
        html.find('.url').prop('name', 'url');
        html.find('.nombre').prop('name', 'nombre');
        bootbox.dialog({
        message: html,
        title: "Insertar URL",
        buttons: {
          success: {
            label: "Aceptar",
            className: "btn-success",
            callback: function() {
              html.find('form').validate({
                  rules: {
                      url: {
                          required: true
                      },
                      nombre: {
                          required: true
                      }
                  }
              });
              
              if(html.find('form').valid()){
                  var url = html.find('.url').val();
                  var nombre = html.find('.nombre').val();
                  var lineamiento_id = $('#arbol').jstree('get_selected')[0];
                  var momento = $('#guardar-item').data('momento');
                  var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=guardar_url_anexo', {
                     url : url,
                     nombre : nombre,
                     lineamiento_id : lineamiento_id,
                     momento : momento
                  });
                  
                  jqxhr.done(function(){
                     $('.close').trigger('click');
                     $(document).trigger('guardar_evaluacion'); 
                  });
              }
              else{
                  return false;
              }
              
            }
          }
          
        }
      });

    });
    
    
    //tablas estadisticas
    $('.ingresar-tabla-estadistica').on('click', function(e){
        var rubro = $('#arbol').jstree('get_selected')[0];
        var momento = $('#guardar-item').data('momento');        
    });
    //antecedentes red
    $('.resultado_red').on('click', function(e){
        e.preventDefault();
        var evaluacion = $(this).data('evaluacion');
        var lineamiento = $('#arbol').jstree('get_selected')[0];
        var momento = $('#guardar-item').data('momento_evaluacion');
        console.log(momento);
        var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=get_resultado_red_indicador', {
            evaluacion : evaluacion,
            lineamiento : lineamiento,
            momento : momento
        });
        
        jqxhr.done(function(data){
            var html = $('.tpl_resultado_red').clone();
            html.removeClass('hide');
            html.removeClass('tpl_resultado_red');
            html.addClass('resultado_red');
            html.find('.analisis_indicador').html(data.resultados.desc_escala != null ? data.resultados.desc_escala : 'N/A');
            html.find('.programa_academico').html(data.resultados.programa+', '+data.resultados.nom_institucion);
            bootbox.dialog({
                message: html,
                title: "Resultados de evaluación en red",
                buttons: {
                  success: {
                    label: "Aceptar",
                    className: "btn-primary",
                    callback: function() {

                    }
                  }              
                }
              });
        });
        

    });
    $('#arbol').jstree({
            "core" : {
              "animation" : 0,
              "multiple": false,
              "check_callback" : true,
              "themes" : { "stripes" : true },
              'data' : {
                    'url' : 'index.php?mod=sievas&controlador=lineamientos&accion=cargar_arbol_evaluacion',
                    'data' : function (node) {
	                     return { 
                                 'id' : node.id,
                                 conjunto : 59   
                            };
                    }
              }
            },
            
			"types" : {
                "#" : {
                  "max_children" : 1, 
                  "max_depth" : 4, 
                  "valid_children" : ["root"]
                },
                "root" : {
                  "icon" : "public/img/icon-nodes.png",
                  "valid_children" : ["default"]
                },
                "default" : {
                   "icon" : "public/img/icon-nodes.png",
                   "valid_children" : ["default","file"]
                },
                "file" : {
                  "icon" : "glyphicon glyphicon-file",
                  "valid_children" : []
                }
              },

            "plugins" : [
              "dnd", "search",
              "state", "types", "wholerow"
            ]
     });
	      

     $('#cal').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=evaluar&accion=get_escala_completa',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10
                }
            },
            results: function(data, page){
                return {
                    results: data
                }
            }
        },

        initSelection: function(element, callback) {
        // the input tag has a value attribute preloaded that points to a preselected movie's id
        // this function resolves that id attribute to an object that select2 can render
        // using its formatResult renderer - that way the movie name is shown preselected
        var id=$(element).val();   
            if (id!=="") {
                var url = "index.php?mod=sievas&controlador=evaluar&accion=get_escala&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data[0]);
                });
            }
        },

        formatResult: function(d){
            return d.desc_escala;
        },

        formatSelection: function(d){
            return d.desc_escala;
        },

        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }

    });

    $('#referencia').on('click', sieva_evaluar.mostrar_referencia);
    $('#guardar-item').on('click', sieva_evaluar.guardaritem);
    sieva_evaluar.cargardatositem();
    sieva_evaluar.cargar_referencias();
//    $('#plan_mejoramiento').on('click', sieva_evaluar.cargarFormPlanMejoramiento);
    $('#ver-tabla-estadistica').on('click', sieva_evaluar.verTablaEstadistica);
    $(document).on('click', '.referencia-item', sieva_evaluar.cargar_referencia);
      $('.agregar-anexo').on('click', function(e){
            e.preventDefault();
            sieva_evaluar.cargar_popup_listado_archivos();
        });
      $('.agregar-tabla-estadistica').on('click', function(e){
            e.preventDefault();
            sieva_evaluar.cargar_popup_tabla_estadistica();
        });
    $(document).on('click', '.eliminar-anexo', sieva_evaluar.eliminar_anexo);
    $(document).on('click', '.eliminar-tabla', sieva_evaluar.eliminar_tabla);
    $('.dato-rubro').on('click', sieva_evaluar.cargarDatoRubro);

        

    $('.anexos-list').on('recargar.archivos', function(){
        $('.anexos-list table').remove();
        var self = this;       
        var momento_actual = $('#guardar-item').data('momento');
        var lineamiento_id = $('#arbol').jstree('get_selected')[0];
        var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=getAnexos', {
            lineamiento : lineamiento_id,
            momento : 1
        });

        

        jqxhr.done(function(data){
            var html = '<table style="width: 100%">';
			indice = 1
            $.each(data, function(index,value){               
                html += '<tr>';            
                html += '<td style="padding: 4px">'+indice;      
                html += '</td>'; 
                html += '<td style="padding: 4px">';      
                html += '<a href="'+value.ruta+'" class="btn btn-xs btn-default" target="_blank">';
				html += '<i class="glyphicon glyphicon-file"></i> '+value.nombre+'</a>';      
                html += '</td>';     
                html += '<td style="padding: 4px">';     
                if(momento_actual == 1)
                html += '<a href="#" class="btn btn-xs btn-danger eliminar-anexo" data-id="'+value.id+'"><i class="glyphicon glyphicon-trash"></i></a>';      
                html += '</td>';      
                html += '<tr>'; 
				
				indice++;   
            });
            html += '</table>';                
            $(self).append(html);
        });
    });    
    $('.tablas-list').on('recargar.archivos', function(){
        $('.tablas-list table').remove();
        var momento_actual = $('#guardar-item').data('momento');
        var self = this;        
        var lineamiento_id = $('#arbol').jstree('get_selected')[0];
        var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=getAnexos', {
            lineamiento : lineamiento_id,
            momento : 1
        });

        

        jqxhr.done(function(data){
            var html = '<table style="width: 100%">';
			indice = 1
            $.each(data, function(index,value){               
                html += '<tr>';            
                html += '<td style="padding: 4px">'+indice;      
                html += '</td>'; 
                html += '<td style="padding: 4px">';      
                html += '<a href="'+value.ruta+'" class="btn btn-xs btn-default" target="_blank">';
				html += '<i class="glyphicon glyphicon-file"></i> '+value.nombre+'</a>';      
                html += '</td>';     
                html += '<td style="padding: 4px">';     
                if(momento_actual == 1)
                html += '<a href="#" class="btn btn-xs btn-danger eliminar-tabla" data-id="'+value.id+'"><i class="glyphicon glyphicon-trash"></i></a>';      
                html += '</td>';      
                html += '<tr>'; 
				
				indice++;   
            });
            html += '</table>';                
            $(self).append(html);
        });
    });    
    
    $('#indicadores_item').on('click', function(){
        var lineamiento = $('#arbol').jstree('get_selected','full')[0];
        if(lineamiento.parent > 0){
            var jqxhr = $.get('index.php?mod=sievas&controlador=lineamientos&accion=get_datos_complementarios', {
                lineamiento : lineamiento.id,
                tipo_lineamiento : 'item'
            });    

            jqxhr.done(function(data){
                bootbox.alert(decodeURIComponent(data.indicadores));
            }); 
        }        
    });

    
    //buscar
    var to = false;
    $('#buscar-tipo-lineamiento').on('click', function(){
        if(to) { clearTimeout(to); }
        to = setTimeout(function () {
          var v = $('#q').val();
          $('#arbol').jstree(true).search(v);
        }, 250);
    });
    
    $('#cerrar-nodos').on('click', function(){
        $('#arbol').jstree(true).close_all();
    });    

    $('#abrir-nodos').on('click', function(){
        $('#arbol').jstree(true).open_all();
    });    

    $(document).on('guardar_evaluacion', sieva_evaluar.guardaritem);
    $(document).on('guardar_analisis', sieva_evaluar.guardaranalisis);
    $('#cal').on('change', function(e, data){
        if(typeof data == 'undefined'){
            $(document).trigger('guardar_evaluacion');
        }
    });
}


sieva_evaluar.cargarnombrerubro = function(){
    var lineamiento_id = $('#arbol').jstree('get_selected','full')[0];
    var rubro = 0;
    if(lineamiento_id.parent > 0){
        //es un item
        var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=getNombreLineamiento', {
            lineamiento : lineamiento_id.parent
        });        

        jqxhr.done(function(data){
            $('#nombre-rubro').html(data.num_orden+'. '+data.nom_lineamiento);
        });
    }
    else{
        //es un rubro o la raiz
        if(lineamiento_id.parent !== '#'){
            $('#nombre-rubro').html(lineamiento_id.text);
        }
    }
}


sieva_evaluar.verTablaEstadistica = function(){
    var html = '';
    var $html = null;
    var lineamiento_id = $('#arbol').jstree('get_selected','full')[0];
    var rubro = 0;
    var cargo = $('#formEvaluacion').data('cargo');
    var momento = $('#formEvaluacion').data('momento');
    var bandera = $('#formEvaluacion').data('bandera-ev');
    if(lineamiento_id.parent > 0){
        //es un item
        rubro = lineamiento_id.parent;
    }
    else{
        //es un rubro o la raiz
        if(lineamiento_id.parent !== '#'){
            rubro = lineamiento_id.id;
        }
    }    

    var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=getTablaLineamiento', {
        rubro : rubro,
        momento : momento
    });
    
    jqxhr.done(function(data){
       if(data.length > 0){
            html += '<form>';
            html += '<a href="'+data[0].ruta+'" target="_blank"><div class="alert alert-info" role="alert"><i class="glyphicon glyphicon-file"></i> Haga click aquí para ver la tabla de estadísticas asociada al rubro</div></a>';
            if(cargo == 1 && momento == 1 && bandera == 0){                
                html += '<div class="form-group">';
                html += '<div id="msg-uploader"></div>';
                html += '<label>Actualizar archivo</label>';
                html += '<input type="file" name="file" id="archivo">';       
                html += '</div>';
            }           
            html += '</form>';
            $html = $.parseHTML(html);

            $('#archivo', $html).on('change', function(){
                $("#msg-uploader").append('<div class="alert alert-success" role="alert"><img src="public/img/ajax.gif">Cargando archivo...</div>');
                var file = $('#archivo')[0].files[0];
                var formData = new FormData();
                formData.append('archivo', file);
                formData.append('rubro', rubro);
                formData.append('momento', momento);
                formData.append('documento_id', data[0].archivo_id)

                var jqxhr = $.ajax({
                    url: 'index.php?mod=sievas&controlador=evaluar&accion=upload_tabla_estadistica',
                    data: formData,           
                    processData: false,
                    contentType: false,
                    type: 'POST'            
                });       

                jqxhr.done(function(data){
                    $('.close').trigger('click');
                    $(document).trigger('guardar_evaluacion');
                });

                jqxhr.fail(function(err){ 
                    var error = JSON.parse(err.responseText);
                    bootbox.alert(error.error);         
                });
            });

             bootbox.dialog({
                title:"Tabla estadística",
                message:$html,

                buttons: {
                    cancel: {
                        label: "Cancelar",
                        className: "btn-danger"
                    },
                }
           });
       } 
       else{
           if(cargo == 1 && momento == 1 && bandera == 0){
                html += '<form>'; 
                html += '<div class="form-group">';
                html += '<div id="msg-uploader"></div>';
                html += '<label>Subir archivo</label>';
                html += '<input type="file" name="file" id="archivo">';       
                html += '</div>';
                html += '</form>';
                $html = $.parseHTML(html);

                $('#archivo', $html).on('change', function(){
                    $("#msg-uploader").append('<div class="alert alert-success" role="alert"><img src="public/img/ajax.gif">Cargando archivo...</div>');
                    var file = $('#archivo')[0].files[0];
                    var formData = new FormData();
                    formData.append('archivo', file);
                    formData.append('momento', momento);
                    formData.append('rubro', rubro);

                    var jqxhr = $.ajax({
                        url: 'index.php?mod=sievas&controlador=evaluar&accion=upload_tabla_estadistica',
                        data: formData,           
                        processData: false,
                        contentType: false,
                        type: 'POST'            
                    });       

                    jqxhr.done(function(data){
                        $('.close').trigger('click');
                        $(document).trigger('guardar_evaluacion');
                    });

                    jqxhr.fail(function(err){ 
                        var error = JSON.parse(err.responseText);
                        bootbox.alert(error.error);         
                    });
                });

                 bootbox.dialog({
                    title:"Tabla estadística",
                    message:$html,
                    buttons: {         
                        cancel: {
                            label: "Cancelar",
                            className: "btn-danger"
                        },
                    }
               });
           }

           else{
               bootbox.alert("No hay tablas estadisticas asociadas al rubro seleccionado");
           }            
       }
    });   
}


sieva_evaluar.eliminar_anexo = function(e){
    e.preventDefault();
    var documento_id = $(this).data('id');
    var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=eliminarAnexo', {
        documento_id : documento_id
    });    

    jqxhr.done(function(){
         $('.anexos-list').trigger('recargar.archivos');
//         $('.tablas-list').trigger('recargar.archivos');
         $(document).trigger('guardar_evaluacion');
    });
}

sieva_evaluar.eliminar_tabla = function(e){
    e.preventDefault();
    var documento_id = $(this).data('id');
    var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=eliminarAnexo', {
        documento_id : documento_id
    });    

    jqxhr.done(function(){
//         $('.anexos-list').trigger('recargar.archivos');
         $('.tablas-list').trigger('recargar.archivos');
//         var selected = $('#arbol').jstree('get_selected')[0]; 
//         var node = $('#arbol').jstree('get_node', selected); 
//         $('#arbol').trigger('select_node.jstree', { node : node, selected : selected });
    });
}

/*
sieva_evaluar.cargarFormPlanMejoramiento = function(e){

    e.preventDefault();

    var html = '';

    html += '<form id="formPlanMejoramiento">';

    html += '<div class="form-group">';

    html += '<label>Titulo</label>';

    html += '<input type="text" class="form-control" id="titulo" name="titulo">';

    html += '</div>';

    html += '<div class="form-group">';

    html += '<label>Subtitulo</label>';

    html += '<input type="text" class="form-control" id="subtitulo" name="subtitulo">';

    html += '</div>';

    html += '<div class="form-group">';

    html += '<label>Presupuesto</label>';

    html += '<input type="text" class="form-control" id="presupuesto" name="presupuesto">';

    html += '</div>';

    html += '<div class="form-group">';

    html += '<label>Fecha cumplimiento</label>';

    html += '<div class="input-group">';    

    html += '<input type="text" class="form-control" id="fecha_cumplimiento" name="fecha_cumplimiento" data-date-format="yyyy-mm-dd">';

    html += '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';

    html += '</div>';

    html += '</div>';

    html += '<div class="form-group">';

    html += '<label>Objetivos</label>';

    html += '<textarea class="form-control" id="objetivos" name="objetivos"></textarea>';

    html += '</div>';

    html += '<div class="form-group">';

    html += '<label>Estrategias</label>';

    html += '<textarea class="form-control" id="estrategias" name="estrategias"></textarea>';       

    html += '</div>';

    html += '<div class="form-group">';

    html += '<label>Metas</label>';

    html += '<textarea class="form-control etiquetas" id="metas" name="metas"></textarea>';       

    html += '</div>';

    html += '<br/>';    

    html += '<div class="form-group">';

    html += '<label>Acciones</label>';

    html += '<textarea class="form-control etiquetas" id="acciones" name="acciones"></textarea>';       

    html += '</div>';

    html += '</div>';

    html += '</form>';

    

    $html = $.parseHTML(html); 

    

    $('.etiquetas', $html).textext({ plugins: 'tags' });    

    $('#fecha_cumplimiento', $html).datepicker();

    

    $.each(sieva_evaluar.plan_mejoramiento, function(index,value){

        switch(value.name){

            case 'titulo':

                 $('#titulo', $html).val(value.value);

                break;

            case 'subtitulo':

                $('#subtitulo', $html).val(value.value);

                break;

            case 'presupuesto':

                $('#presupuesto', $html).val(value.value);

                break;

            case 'fecha_cumplimiento':

                $('#fecha_cumplimiento', $html).val(value.value);

                break;

            case 'objetivos':

                $('#objetivos', $html).val(value.value);

                break;

            case 'estrategias':

                $('#estrategias', $html).val(value.value);

                break;

            case 'metas':

                $('#metas', $html).textext()[0].tags().addTags(JSON.parse(value.value));

                break;

            case 'acciones':

                $('#acciones', $html).textext()[0].tags().addTags(JSON.parse(value.value));

                break;

        }

    });



    bootbox.dialog({

            title:"Plan de mejoramiento",

            message:$html,

            buttons: {

                success: {

                    label: "Guardar",

                    className: "btn-success",

                    callback: function(){

                        sieva_evaluar.plan_mejoramiento = $('#formPlanMejoramiento').serializeArray();

                    }

                },

                cancel: {

                    label: "Cancelar",

                    className: "btn-danger"

                },

            }

       });



}*/

sieva_evaluar.cargar_popup_anexos = function(){    
    var html = '';
    html += '<form>'; 
    html += '<div class="form-group">';
    html += '<div id="msg-uploader"></div>';
    html += '<label>Subir archivo</label>';
    html += '<input type="file" name="file" id="archivo">';       
    html += '</div>';
    html += '</form>';
    $html = $.parseHTML(html); 
    
    $('#archivo', $html).on('change', function(){
        $("#msg-uploader").append('<div class="alert alert-success" role="alert"><img src="public/img/ajax.gif">Cargando archivo...</div>');
        var file = $('#archivo')[0].files[0];
        var lineamiento_id = $('#arbol').jstree('get_selected')[0];
        var momento = $('#guardar-item').data('momento');
        var formData = new FormData();
        formData.append('archivo', file);
        formData.append('lineamiento', lineamiento_id);
        formData.append('momento', momento);
        
        var jqxhr = $.ajax({
            url: 'index.php?mod=sievas&controlador=evaluar&accion=upload_anexo',
            data: formData,           
            processData: false,
            contentType: false,
            type: 'POST'            
        });       
        
        jqxhr.done(function(data){
            $('.close').trigger('click');
//            $('.anexos-list').trigger('recargar.archivos');
            $(document).trigger('guardar_evaluacion');
        });        

        jqxhr.fail(function(err){ 
            var error = JSON.parse(err.responseText);
            bootbox.alert(error.error);         
        });
    });

    var d = bootbox.dialog({
        title:"Adjuntar anexo",
        message:$html,
        buttons: {            
             select_archivo: {
                label: "Listado de archivos",
                className: "btn-default",
                callback: function(){
                    d.modal('hide');
                    sieva_evaluar.cargar_popup_listado_archivos();
                }
            },
            cancel: {
                label: "Cancelar",
                className: "btn-danger"
            }
        }
    });
}

sieva_evaluar.cargar_popup_tabla_estadistica = function(){    
    var html = '';
    html += '<form>'; 
    html += '<div class="form-group">';
    html += '<div id="msg-uploader"></div>';
    html += '<label>Subir archivo</label>';
    html += '<input type="file" name="file" id="archivo">';       
    html += '</div>';
    html += '</form>';
    $html = $.parseHTML(html); 
    
    $('#archivo', $html).on('change', function(){
        $("#msg-uploader").append('<div class="alert alert-success" role="alert"><img src="public/img/ajax.gif">Cargando archivo...</div>');
        var file = $('#archivo')[0].files[0];
        var lineamiento_id = $('#arbol').jstree('get_selected')[0];
        var momento = $('#guardar-item').data('momento');
        var formData = new FormData();
        formData.append('archivo', file);
        formData.append('lineamiento', lineamiento_id);
        formData.append('momento', momento);
        
        var jqxhr = $.ajax({
            url: 'index.php?mod=sievas&controlador=evaluar&accion=upload_anexo',
            data: formData,           
            processData: false,
            contentType: false,
            type: 'POST'            
        });       
        
        jqxhr.done(function(data){
            $('.close').trigger('click');
//            $('.anexos-list').trigger('recargar.archivos');
//            $(document).trigger('guardar_evaluacion');
              var selected = $('#arbol').jstree('get_selected')[0]; 
              var node = $('#arbol').jstree('get_node', selected); 
              $('#arbol').trigger('select_node.jstree', { node : node, selected : selected });
        });        

        jqxhr.fail(function(err){ 
            var error = JSON.parse(err.responseText);
            bootbox.alert(error.error);         
        });
    });

    var d = bootbox.dialog({
        title:"Adjuntar tabla estadística",
        message:$html,
        buttons: {           
            cancel: {
                label: "Cancelar",
                className: "btn-danger"
            }
        }
    });
}

sieva_evaluar.cargar_popup_listado_archivos = function(){    
    var momento = $('#guardar-item').data('momento');
    var lineamiento_id = $('#arbol').jstree('get_selected')[0];
    var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=get_listado_anexos',{
        momento : momento,
        lineamiento : lineamiento_id
    });
    
    jqxhr.done(function(data){
        var html = '';
        html += '<p>Seleccione un archivo del listado</p>';
        html += '<div style="max-height: 200px; overflow-y: auto">';
        html += '<table class="table">';       
        $.each(data, function(index, value){
            html += '<tr>';    
            html += '<td><a href="'+value.ruta+'" class="btn btn-xs btn-default" target="_blank"><i class="glyphicon glyphicon-file" title="'+value.nombre+'"></i> '+(value.nombre.length > 40 ? value.nombre.substring(0,37)+'...' : value.nombre)+'</a></td>';    
            html += '<td><input type="radio" name="archivo" value="'+value.id+'"></td>';    
            html += '</tr>'; 
        });         
        html += '</table>';
        html += '</div>';

        $html = $.parseHTML(html); 
        
        $('input[name=archivo]',$html).on('change', function(){
           $('.close').trigger('click'); 
           var archivo_id = $(this).val();
           var lineamiento_id = $('#arbol').jstree('get_selected')[0];
           var momento = $('#guardar-item').data('momento');
           var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=asociar_anexo', {
               documento : archivo_id,
               lineamiento : lineamiento_id,
               momento : momento
           });
           
           jqxhr.done(function(data){
                $('.close').trigger('click');
//                $('.anexos-list').trigger('recargar.archivos');
                $(document).trigger('guardar_evaluacion');
           });    
        });
        
        var d = bootbox.dialog({
        title:"Adjuntar anexo",
        message:$html,
        buttons: {            
             subir_archivo: {
                label: "Subir archivos",
                className: "btn-default",
                callback: function(){
                    d.modal('hide');
                    sieva_evaluar.cargar_popup_anexos();
                }
            },
            cancel: {
                label: "Cancelar",
                className: "btn-danger"
            }
        }
    });
    });

    
}


/*
sieva_evaluar.mostrar_referencia = function(){

    var referencia = {};

    referencia.calificacion_item = '9';

    referencia.fortalezas = "Como todas las elecciones, éstas se celebran para que todos los ciudadanos tengan la opurtinadad de elegir a su representante en el gobierno. Cada cuatro años se vuelven a celebrar por si existe algún desacuerdo con el que se encuentra en el poder actualmente tengas la oportunidad de cambiarlo o si en realidad sigues de acuerdo con su mentalidad y también con sus acciones puedas volver a votarlo para que siga en el poder.";

    referencia.debilidades = "Como todas las elecciones, éstas se celebran para que todos los ciudadanos tengan la opurtinadad de elegir a su representante en el gobierno. Cada cuatro años se vuelven a celebrar por si existe algún desacuerdo con el que se encuentra en el poder actualmente tengas la oportunidad de cambiarlo o si en realidad sigues de acuerdo con su mentalidad y también con sus acciones puedas volver a votarlo para que siga en el poder.";

    referencia.plan_mejoramiento = "Como todas las elecciones, éstas se celebran para que todos los ciudadanos tengan la opurtinadad de elegir a su representante en el gobierno. Cada cuatro años se vuelven a celebrar por si existe algún desacuerdo con el que se encuentra en el poder actualmente tengas la oportunidad de cambiarlo o si en realidad sigues de acuerdo con su mentalidad y también con sus acciones puedas volver a votarlo para que siga en el poder.";



    var html = '';

    html += '<p><b>Calificación:</b> '+referencia.calificacion_item+'</p>';

    html += '<p><b>Fortalezas:</b> '+referencia.fortalezas+'</p>';

    html += '<p><b>Debilidades:</b> '+referencia.debilidades+'</p>';

    html += '<p><b>Plan de mejoramiento:</b> '+referencia.plan_mejoramiento+'</p>';

    

    bootbox.dialog({

        title:"Autoevaluación anterior",

        message:html,

        buttons: {

            success: {

                label: "Aceptar",

                className: "btn-success"

            }

        }

    });

} */



sieva_evaluar.cargardatositem = function(){
    $('#arbol').on('select_node.jstree', function(e,data){
        console.log(data.node);
        $('#guardar-item').data('momento_resultado','');
        $('#guardar-item').data('momento_evaluacion','');
        $('#analisis').data('content', '');    
        $('.anexos-list').trigger('recargar.archivos');
        $('.tablas-list').trigger('recargar.archivos');
        sieva_evaluar.cargarPlantillaRubro();
        sieva_evaluar.cargarnombrerubro();
        var parent = parseInt(data.node.parent);
        var id = data.node.id;
        var text = (data.node.a_attr.caracteristica.length > 0 ? 'Característica: '+data.node.a_attr.caracteristica+'<br/><br/> Indicador: '+data.node.text : data.node.text);
        if(parent > 0){            
            $('#formEvaluacion').fadeIn();            
            var html = '';
            html += '<div id="nombre-item" style="color:#990000">'+text+'</div>';
            html += '<div id="calificacion"></div>';
            $('#datos-item').html(html);            
            var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=get_lineamiento', {
                padre : parent,
                id : id,
                momento : $('#guardar-item').data('momento'),
                indicadores : 1
            });            

            jqxhr.done(function(data){
                if(data.length > 0){                    
                    data = data[0];
                    $('#guardar-item').data('momento_resultado',data.id);
                    $('#guardar-item').data('momento_evaluacion',data.cod_momento_evaluacion);
                    $('#guardar-item').data('privilegiado', data.privilegios.privilegiado);
                    $('#guardar-item').data('privilegio', data.privilegios.privilegio);
                    $('#documentos').html(decodeURIComponent(data.documentos));
                    $('#analisis').data('content', data.analisis !== null ? data.analisis : '').trigger('render.summernote_helper');
                    sieva_evaluar.plan_mejoramiento = data.plan_mejoramiento;
                }   
                else{
                    $('#analisis').trigger('reset.summernote_helper');
                    sieva_evaluar.plan_mejoramiento = [];
                }
            });
        }
        else{
           $('#formEvaluacion').fadeOut();
           var html = '';
           html += '<div id="nombre-item"></div>';
           html += '<div id="calificaciones"></div>';
           $('#datos-item').html(html);
           $('#analisis').trigger('reset.summernote_helper');
        }
    });
}


sieva_evaluar.guardaritem = function(e){
    e.preventDefault();
    var cod_momento = $('#guardar-item').data('momento');
    var cargo = $('#formEvaluacion').data('cargo');
    var momento = $('#formEvaluacion').data('momento');
    var bandera = $('#formEvaluacion').data('bandera-ev');
    var privilegio = $('#guardar-item').data('privilegio');
    var privilegiado = $('#guardar-item').data('privilegiado');

    if(cargo == 1 && bandera == 0 && ((privilegio == 1 && privilegiado == 1) || privilegio == 0)){
        var calificacion = $('#cal').val();
        var fortalezas = $('#fortalezas').data('content');  
        var debilidades = $('#debilidades').data('content');
        var plan_mejoramiento = $('#plan_mejoramiento').data('content');
         
        var selected = $('#arbol').jstree(true).get_selected(true);
        var lineamiento = selected[0];
        if(lineamiento.parent > 0){
            var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=guardar_evaluacion_interna', {
                calificacion : calificacion,
                fortalezas : fortalezas,
                debilidades : debilidades,
                plan_mejoramiento : plan_mejoramiento,
                lineamiento : lineamiento.id,
                cod_momento : cod_momento
            });

            jqxhr.done(function(data){
                bootbox.alert("Los datos han sido guardados exitosamente");
                 var selected = $('#arbol').jstree('get_selected')[0]; 
                 var node = $('#arbol').jstree('get_node', selected); 
                 $('#arbol').trigger('select_node.jstree', { node : node, selected : selected });                 
            });

            jqxhr.fail(function(err){ 
                var error = JSON.parse(err.responseText);
                bootbox.alert(error.error);         
            });
        }
        else{
            bootbox.alert("Debe seleccionar un lineamiento");
        }    
    }
    else{
        bootbox.alert("No tiene permisos para guardar los cambios");
        var selected = $('#arbol').jstree('get_selected')[0]; 
        var node = $('#arbol').jstree('get_node', selected); 
        $('#arbol').trigger('select_node.jstree', { node : node, selected : selected });
    }
}


sieva_evaluar.guardaranalisis = function(e){
    e.preventDefault();
    var cod_momento = $('#guardar-item').data('momento');
    var cargo = $('#formEvaluacion').data('cargo');
    var momento = $('#formEvaluacion').data('momento');
    var bandera = $('#formEvaluacion').data('bandera-ev');
    var privilegio = $('#guardar-item').data('privilegio');
    var privilegiado = $('#guardar-item').data('privilegiado');

    if(cargo == 1 && bandera == 0 && ((privilegio == 1 && privilegiado == 1) || privilegio == 0)){
        var analisis = $('#analisis').data('content');  
         
        var selected = $('#arbol').jstree(true).get_selected(true);
        var lineamiento = selected[0];
        if(lineamiento.parent > 0){
            var jqxhr = $.post('index.php?mod=sievas&controlador=evaluar&accion=guardar_analisis', {
                analisis : analisis,
                lineamiento : lineamiento.id,
                cod_momento : cod_momento
            });

            jqxhr.done(function(data){
                bootbox.alert("Los datos han sido guardados exitosamente");
                 var selected = $('#arbol').jstree('get_selected')[0]; 
                 var node = $('#arbol').jstree('get_node', selected); 
                 $('#arbol').trigger('select_node.jstree', { node : node, selected : selected });                 
            });

            jqxhr.fail(function(err){ 
                var error = JSON.parse(err.responseText);
                bootbox.alert(error.error);         
            });
        }
        else{
            bootbox.alert("Debe seleccionar un indicador");
        }    
    }
    else{
        bootbox.alert("No tiene permisos para guardar los cambios");
        var selected = $('#arbol').jstree('get_selected')[0]; 
        var node = $('#arbol').jstree('get_node', selected); 
        $('#arbol').trigger('select_node.jstree', { node : node, selected : selected });
    }
}

sieva_evaluar.cargar_referencias = function(){
    var momento = $('#formEvaluacion').data('momento');
    var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=get_referencias', {
        momento : momento
    });
    
    jqxhr.done(function(data){
        $.each(data, function(index,value){
            $('#lista-referencias').append('<li><a href="#" data-id="'+value.momento_evaluacion+'" class="referencia-item"> '+value.momento+' '+value.fecha_inicia.substring(0,4)+'</a></li>');
        });
        if(data.length === 0){
            $('#lista-referencias').append('<li><a href="#">No hay referencias</a></li>');
        } 
    });
}


sieva_evaluar.cargar_referencia = function(){
    var momento_evaluacion = $(this).data('id');
    var lineamiento_id = $('#arbol').jstree('get_selected')[0];
    
    var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=get_lineamiento', {
        momento_evaluacion : momento_evaluacion,
        id : lineamiento_id
    });

    jqxhr.done(function(data){
        var html = '';
        html += '<ul class="nav nav-tabs" role="tablist">';
        html += '<li class="active"><a href="#home" role="tab" data-toggle="tab">Calificación</a></li>';
        html += '</ul>';
        html += '<div class="tab-content">';
        html += '<div class="tab-pane active" id="home">';
        html += '<br/>';
        html += '<form>';
        html += '<div class="form-group">';
        html += '<label>Calificacion</label>';
        html += '<input type="hidden" name="calificacion" class="form-control" id="cal_r" readonly>';
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Fortalezas</label>';
        html += '<p id="fortalezas_r"></p>';        
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Debilidades</label>';
        html += '<div id="debilidades_r" class="summernote_modal" data-title="Editar debilidades" style="overflow:auto">';
        html += '<input type="text" class="form-control">';
        html += '</div>';
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Plan de mejoramiento</label>';
        html += '<div id="plan_mejoramiento_r" class="summernote_modal" data-title="Editar mejoramiento" style="overflow:auto">';
        html += '<input type="text" class="form-control">';
        html += '</div>';
        html += '</div>';
        html += ' </form>'; 
        html += '</div>';
/*        html += ' <div class="tab-pane" id="profile">';
        html += '<form>';
        html += '<div class="form-group">';
        html += '<br/>';
        html += '<label>Titulo</label>';
        html += '<input type="text" class="form-control" id="titulo_r" name="titulo" readonly>';
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Subtitulo</label>';
        html += '<input type="text" class="form-control" id="subtitulo_r" name="subtitulo" readonly>';
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Presupuesto</label>';
        html += '<input type="text" class="form-control" id="presupuesto_r" name="presupuesto" readonly>';
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Fecha cumplimiento</label>';
        html += '<div class="input-group">';    
        html += '<input type="text" class="form-control" id="fecha_cumplimiento_r" name="fecha_cumplimiento" data-date-format="yyyy-mm-dd" readonly>';
        html += '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
        html += '</div>';
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Objetivos</label>';
        html += '<textarea class="form-control" id="objetivos_r" name="objetivos" readonly></textarea>';
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Estrategias</label>';
        html += '<textarea class="form-control" id="estrategias_r" name="estrategias" readonly></textarea>';       
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label>Metas</label>';
        html += '<textarea class="form-control etiquetas" id="metas_r" name="metas" readonly></textarea>';       
        html += '</div>';
        html += '<br/>';    
        html += '<div class="form-group">';
        html += '<label>Acciones</label>';
        html += '<textarea class="form-control etiquetas" id="acciones_r" name="acciones" readonly></textarea>';
        html += '</div>';
        html += '</div>';
        html += '</form>';
        html += '</div>';
*/
        html += '</div>';
        
		$html = $.parseHTML(html); 

        if(data.length == 0){
            $('#cal_r', $html).html('No hay datos');
            $('#fortalezas_r', $html).html('No hay datos');
            $('#debilidades_r', $html).html('No hay datos');
            $('#plan_mejoramiento_r', $html).html('No hay datos');
            $('.etiquetas', $html).textext({ plugins: 'tags' }); 
        }
        else{
            $('#cal_r', $html).val(data[0].cod_gradacion_escala);
            $('#fortalezas_r', $html).html((decodeURIComponent(data[0].fortalezas) == 'null' ? 'No hay datos' : decodeURIComponent(data[0].fortalezas)));
            $('#debilidades_r', $html).html(decodeURIComponent(data[0].debilidades) == 'null' ? 'No hay datos' : decodeURIComponent(data[0].debilidades));
            $('#plan_mejoramiento_r', $html).html(decodeURIComponent(data[0].plan_mejoramiento) == 'null' ? 'No hay datos' : decodeURIComponent(data[0].plan_mejoramiento));
            $('.etiquetas', $html).textext({ plugins: 'tags' }); 
        }

//        console.log(data[0].plan_mejoramiento);

        

//       $.each(data[0].plan_mejoramiento, function(index,value){

//            switch(value.name){

//                case 'titulo':

//                     $('#titulo_r', $html).val(value.value);

//                    break;

//                case 'subtitulo':

//                    $('#subtitulo_r', $html).val(value.value);

//                    break;

//                case 'presupuesto':

//                    $('#presupuesto_r', $html).val(value.value);

//                    break;

//                case 'fecha_cumplimiento':

//                    $('#fecha_cumplimiento_r', $html).val(value.value);

//                    break;

//                case 'objetivos':

//                    $('#objetivos_r', $html).val(value.value);

//                    break;

//                case 'estrategias':

//                    $('#estrategias_r', $html).val(value.value);

//                    break;

//                case 'metas':

//                    $('#metas_r', $html).textext()[0].tags().addTags(JSON.parse(value.value));

//                    break;

//                case 'acciones':

//                    $('#acciones_r', $html).textext()[0].tags().addTags(JSON.parse(value.value));

//                    break;

//            }

//        });

        
        $('#cal_r', $html).select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=evaluar&accion=get_escala_completa',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10
                }
            },

            results: function(data, page){
                return {
                    results: data
                }
            }
        },

        initSelection: function(element, callback) {
        // the input tag has a value attribute preloaded and preselected movie's name is shown preselected
        var id=$(element).val();   
            if (id!=="") {
                var url = "index.php?mod=sievas&controlador=evaluar&accion=get_escala&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data[0]);
                });
            }
        },

        formatResult: function(d){
            return d.desc_escala;
        },

        formatSelection: function(d){
            return d.desc_escala;
        },

        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });    

    bootbox.dialog({
	   title:"Antecedentes",
	   message:$html,
	   buttons: {
		   success: {
			   label: "Aceptar",
			   className: "btn-primary"
		   } 
	   }
  	});
	
  });
}

sieva_evaluar.cargarPlantillaRubro = function(){
    var lineamiento_id = $('#arbol').jstree('get_selected','full')[0];
    var rubro = 0;
    if(lineamiento_id.parent > 0){
        //es un item
        rubro = lineamiento_id.parent;
    }
    else{
        //es un rubro o la raiz
        if(lineamiento_id.parent !== '#'){
            rubro = lineamiento_id.id;
        }
    }    

    var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=getPlantillaEstadisticas', {
        rubro : rubro
    });    

    jqxhr.done(function(data){
        $('#plantilla').prop('href', data[0].ruta);
    });
}

sieva_evaluar.cargarDatoRubro = function(e){
    e.preventDefault();
    var lineamiento_id = $('#arbol').jstree('get_selected','full')[0];
    var campo = $(this).data('campo');
    var rubro = 0;
    if(lineamiento_id.parent > 0){
        //es un item
        rubro = lineamiento_id.parent;
    }
    else{
        //es un rubro o la raiz
        if(lineamiento_id.parent !== '#'){
            rubro = lineamiento_id.id;
        }
    }
	
    var jqxhr = $.get('index.php?mod=sievas&controlador=evaluar&accion=getDatoRubro', {
        campo : campo,
        rubro : rubro
    });    

    jqxhr.done(function(data){
        bootbox.alert(decodeURIComponent(data.dato));
    });    
}

$(sieva_evaluar.init);

