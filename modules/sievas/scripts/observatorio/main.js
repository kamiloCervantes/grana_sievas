var observatorio = {};

observatorio.initadd = function(){

    $('.summernote').summernote({
        height: 400,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
//            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
          ]
    });
    
     $('#filtro').select2({
                        minimumInputText: 1,
                        placeholder: "Seleccione...",
                        ajax:{
                            url:'index.php?mod=sievas&controlador=observatorio&accion=get_tipos_categoria',
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
                                var url = "index.php?mod=sievas&controlador=observatorio&accion=get_tipo_categoria&id="+id;
                                var jqxhr = $.get(url);
                                jqxhr.done(function(data){
                                    callback(data);
                                });
                            }
                        },
                        formatResult: function(d){
                            return d.categoria_tipo;
                        },
                        formatSelection: function(d){
                            return d.categoria_tipo;
                        },
                        dropdownCssClass: "bigdrop",
                        escapeMarkup: function (m) { return m; }
                    }).on('change', function(){
                         $('#area_nucleo').select2('val', '');
                    })
        .on('change', function(){
        $('#add-categoria').popover('destroy');       
        var filtro = $(this).val();
        switch(parseInt(filtro)){
            case 1:
                //rubros
                    $('#selector').select2({
                        minimumInputText: 1,
                        placeholder: "Seleccione...",
                        ajax:{
                            url:'index.php?mod=sievas&controlador=observatorio&accion=get_categorias',
                            dataType: 'json',
                            data: function(term, page){
                                return {
                                    q: term,
                                    page_limit: 10,
                                    tipo_categoria: 1
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
                                var url = "index.php?mod=sievas&controlador=observatorio&accion=get_categoria&id="+id;
                                var jqxhr = $.get(url);
                                jqxhr.done(function(data){
                                    callback(data);
                                });
                            }
                        },
                        formatResult: function(d){
                            return d.nom_categoria;
                        },
                        formatSelection: function(d){
                            return d.nom_categoria;
                        },
                        dropdownCssClass: "bigdrop",
                        escapeMarkup: function (m) { return m; }
                    });
                break;
            case 2:
                //areas
                    $('#selector').select2({
                        minimumInputText: 1,
                        placeholder: "Seleccione...",
                        ajax:{
                            url:'index.php?mod=sievas&controlador=observatorio&accion=get_categorias',
                            dataType: 'json',
                            data: function(term, page){
                                return {
                                    q: term,
                                    page_limit: 10,
                                    tipo_categoria: 2
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
                                var url = "index.php?mod=sievas&controlador=observatorio&accion=get_categoria&id="+id;
                                var jqxhr = $.get(url);
                                jqxhr.done(function(data){
                                    callback(data);
                                });
                            }
                        },
                        formatResult: function(d){
                            return d.nom_categoria;
                        },
                        formatSelection: function(d){
                            return d.nom_categoria;
                        },
                        dropdownCssClass: "bigdrop",
                        escapeMarkup: function (m) { return m; }
                    });
                break;
            case 3:
                observatorio.add_categoria();
                $('#selector').select2({
                        minimumInputText: 1,
                        placeholder: "Seleccione...",
                        ajax:{
                            url:'index.php?mod=sievas&controlador=observatorio&accion=get_categorias',
                            dataType: 'json',
                            data: function(term, page){
                                return {
                                    q: term,
                                    page_limit: 10,
                                    tipo_categoria: 3
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
                                var url = "index.php?mod=sievas&controlador=observatorio&accion=get_categoria&id="+id;
                                var jqxhr = $.get(url);
                                jqxhr.done(function(data){
                                    callback(data);
                                });
                            }
                        },
                        formatResult: function(d){
                            return d.nom_categoria;
                        },
                        formatSelection: function(d){
                            return d.nom_categoria;
                        },
                        dropdownCssClass: "bigdrop",
                        escapeMarkup: function (m) { return m; }
                    });
                break;
            default:
                $('#selector').val('');
                 $('#selector').select2('destroy');
                 $('#add-categoria').popover('destroy');     
                 break;
        }
    });
    $('#publicar').on('click', observatorio.guardar_observacion);
    $('#lugar').val(geoplugin_countryName());
    
}

observatorio.guardar_experiencia = function(e){
    e.preventDefault();
    var nucleo = $('#area_nucleo').val();
    var experiencia = $('#experiencia').data('content');
    
    var jqxhr = $.post('index.php?mod=sievas&controlador=observatorio&accion=guardar_experiencia',{
        nucleo : nucleo,
        experiencia : experiencia
    });
}

observatorio.initindex = function(){
//    observatorio.listar_observaciones();
 $('.summernote').summernote({
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
//            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
          ]
    });
    
    $('#observatorio').on('submit', function(e){
        e.preventDefault();
       var valid = true;
       var err_msg = [];
       var observaciones = $.map($('.observacion'), function(value,index){
           if($(value).code() == "<p><br></p>"){
               valid = false;
               err_msg.push("No olvide ingresar la observación del rubro # "+(index+1));
           }
           else{
               return {
                   observacion : $(value).code(),
                   rubro : $(value).data('rubro')
               }
           }
       });
       
       if(valid){
           var jqxhr = $.post('index.php?mod=sievas&controlador=observatorio&accion=guardar_observaciones', {
              observaciones : JSON.stringify(observaciones) 
           });
           
           jqxhr.done(function(data){
               window.location.reload();
           });
           
           jqxhr.fail(function(err){
               alert(err.responseText);
           });
       }
       else{
            alert(err_msg[0]);
       }
    });
}

observatorio.listar_observaciones = function(){
    var filtro = $('#filtro').val();
    observatorio.cargar_observaciones(filtro);
    $('#filtro').on('change', function(){
        observatorio.cargar_observaciones($(this).val());
    });
    
}

observatorio.cargar_observaciones = function(filtro){
    var jqxhr = $.get('index.php?mod=sievas&controlador=observatorio&accion=get_observaciones', {
        filtro : filtro
    });
    
    jqxhr.done(function(data){
        $('#observaciones').html('');
        $.each(data, function(index,value){
            var html = '';
            html += '<div class="observacion">';
            html += '<div class="observacion-title fresh"><h3>'+value.tema+'</h3></div>';
            html += '<div class="observacion-content fresh" style="max-height: 100px;overflow-y:auto">';
            html += decodeURIComponent(value.comentario);
            html += '</div>';
            html += '<div class="observacion-actions">';
            html += '<table>';
            html += '<tr>';
            html += '<td><b>'+(value.tipo_categoria == 1 ? 'Rubro' : 'Área')+': '+value.categoria+'</b></td>';
            html += '<td><b>Autor: '+value.nombres+'</b></td>';
            html += '<td><b>Fecha: '+value.fecha+'</b></td>';
            html += '<td><div class="dropdown">';
            html += '<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">';
            html += 'Acciones';
            html += '<span class="caret"></span>';
            html += '</button>';
            html += '<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
            html += '<li role="presentation"><a role="menuitem" href="index.php?mod=sievas&controlador=observatorio&accion=ver_observacion&id='+value.id+'">Ver observación</a></li>';
            html += '<li role="presentation"><a role="menuitem" href="#">Ver cita bibliográfica</a></li>';
            html += '<li role="presentation"><a role="menuitem" href="#">Editar observación</a></li>';
            html += '<li role="presentation"><a role="menuitem" href="#">Eliminar observación</a></li>';
            html += '</ul>';
            html += '</div></td>';
            html += '</tr>';
            html += '</table>';
            html += '</div>';
            html += '</div>';            
            $('#observaciones').append(html);
        });
        
        
    });
}

observatorio.guardar_observacion = function(e){
    e.preventDefault();
    var tema = $('#tema').val();
    var comentario = encodeURIComponent($('#observacion').code());
    var tipo_categoria = $('#filtro').val();
    var categoria = $('#selector').val();
    var lugar = $('#lugar').val();
    
    var jqxhr = $.post('index.php?mod=sievas&controlador=observatorio&accion=guardar_observacion', {
       tema : tema,
       comentario : comentario,
       tipo_categoria : tipo_categoria,
       categoria : categoria,
       lugar : lugar
    });
    
    jqxhr.done(function(data){
       var self = this;
        var dialog = bootbox.dialog({
            message: "Su observación ha sido registrada correctamente. ¿Desea añadir una nueva observación?",
            title: 'Continuar',
            buttons: {
                success: {
                  label: "Si",
                  className: "btn-success",
                  callback: function(){
                      
                  }
                },
                danger: {
                  label: "No",
                  className: "btn-danger",
                  callback: function() {
                      window.location = 'index.php?mod=sievas&controlador=observatorio';
                  }
                }
              }
            }); 
        });
}

observatorio.add_categoria = function(){
    console.log("hola");
    var tipo_categoria = $('#filtro').val();
    console.log(tipo_categoria);
    if(parseInt(tipo_categoria) === 3){
         $('#add-categoria').popover({
           title : 'Nueva categoría',
           content: function(){
                var html = '';
                html += '<form>';
                html += '<div class="form-group">';
                html += '<label>Nombre</label>';
                html += '<input type="text" class="form-control nombre_categoria">';
                html += '</div>';
                html += '<a href="#" class="btn btn-primary btn-sm agregar-categoria">Agregar</a>';              
                html += '</form>';
                return html;
           },
           placement: 'right',
           html : 'true',
           trigger : 'click',
           container : 'body'
       }).on('shown.bs.popover', function(){
           var self = this;
           $('.agregar-categoria').on('click', function(e){               
               e.preventDefault();
               var nombre_categoria = $(e.target).parent().find('.nombre_categoria').val();
               var jqxhr = $.post('index.php?mod=sievas&controlador=observatorio&accion=guardar_categoria', {
                  nom_categoria : nombre_categoria,
                  tipo_categoria : 3
               });
               
               jqxhr.done(function(data){
                  $('#selector').select2('val', data.id); 
                  $('#add-categoria').popover('hide');
               });
               
               jqxhr.fail(function(err){
                  bootbox.alert(err.responseText);
               });
           });
       });
    }
}