var evaluaciones = {};

evaluaciones.oTable = $('#data_tabla');

evaluaciones.initpredictamen = function(){
//    $('.calificacion').select2();
     $('.calificacion').select2({
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
}

evaluaciones.initlistar = function(){
    evaluaciones.oTable.dataTable({
            "oLanguage":{"sUrl": "public/js/dataTables.spanish.txt"},
            "bServerSide": true,
            "sAjaxSource": "index.php?mod=sievas&controlador=evaluaciones&accion=get_dt_evaluaciones",
            "bProcessing": true,            
            "fnServerParams": function(aoData){
                var fkeys = [];
                fkeys.push(
                    {nombre : 'lineamientos_conjuntos',fkey : 'cod_conjunto'}                    
                );
                
                aoData.push({"name": "sIndexColumn", "value": "id"});
                aoData.push({"name": "sTable", "value": "evaluacion"});
                aoData.push({"name": "fKeys", "value": JSON.stringify(fkeys)});
            },
            "aoColumns":[
                {"mData": 0,"sName": "evaluacion.etiqueta"
                },
                {"mData": 1,"sName": "evaluacion.cod_evaluado"
                },
                {"mData": 2,"sName": "evaluacion.tipo_evaluado"
                },
                {"mData": 3,"sName":"evaluacion.fecha_inicia"
                    },              
                {"mData": 4,"sName":"lineamientos_conjuntos.nom_conjunto",
                    },                       
                {
                    "mData":5,
                    "sName":"id",
                    "mRender":function(id){
                        return '<a href="index.php?mod=sievas&controlador=evaluaciones&accion=editar&id='+id+'" class="editar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-edit"></i></a>\n\
                        <a href="#" class="eliminar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a>\n\
                        <a href="#" class="finalizar-evaluacion" data-id="'+id+'"><i class="glyphicon glyphicon-flag"></i></a>\n\
                        <a href="index.php?mod=sievas&controlador=evaluaciones&accion=configuracion&id='+id+'" class="configurar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-cog"></i></a>';
                    },
                    "bSortable" : false                    
                }
            ]   
    });
    
    $(document).on('click','.finalizar-evaluacion', evaluaciones.finalizar_evaluacion);
    $(document).on('click','.eliminar-generico', evaluaciones.eliminarevaluacion);
}

evaluaciones.initdictamen = function(){
    $('#guardar-dictamen').on('click', function(e){
        e.preventDefault();
        var form = $('#formDictamen').serialize();
        var jqxhr = $.post('index.php?mod=sievas&controlador=evaluaciones&accion=guardar_dictamen', form);
        
        jqxhr.done(function(data){
           data = JSON.parse(data);
           bootbox.alert(data.mensaje);
        });

        jqxhr.fail(function(err){
           bootbox.alert(err.responseText); 
        });
    });
}

evaluaciones.eliminarevaluacion = function(){
    var self = this;
    var dialog = bootbox.dialog({
        message: "¿Esta seguro que desea eliminar la evaluación?",
        title: 'Eliminar evaluación',
        buttons: {
            success: {
              label: "Si",
              className: "btn-success",
              callback: function() {
                var id = $(self).data('id');
                var jqxhr = $.post('index.php?mod=sievas&controlador=evaluaciones&accion=eliminar_evaluacion', {
                    id : id
                });

                jqxhr.done(function(data){
                   bootbox.alert(data.mensaje, function(){
                       location.reload();
                   });
                });

                jqxhr.fail(function(err){
                   bootbox.alert(err.responseText); 
                });
              }
            },
            danger: {
              label: "No",
              className: "btn-danger",
              callback: function() {
                
              }
            }
          }
        });
}

evaluaciones.finalizar_evaluacion = function(){
    var evaluacion = $(this).data('id');
    var self = this;
    var dialog = bootbox.dialog({
        message: "¿Esta seguro que desea finalizar la evaluación?",
        title: 'Finalizar evaluación',
        buttons: {
            success: {
              label: "Si",
              className: "btn-success",
              callback: function() {
                var jqxhr = $.post('index.php?mod=sievas&controlador=evaluaciones&accion=finalizar_evaluacion', {
                    evaluacion : evaluacion
                });

                jqxhr.done(function(data){
                   bootbox.alert(data.mensaje);
                });

                jqxhr.fail(function(err){
                   if(err.responseText !== ''){
                       bootbox.dialog({
                        message: err.responseText,
                        title: 'Reapertura evaluación',
                        buttons: {
                            success: {
                              label: "Si",
                              className: "btn-success",
                              callback: function() {
                                  window.location = 'index.php?mod=sievas&controlador=evaluaciones&accion=reabrir_evaluacion&id='+evaluacion;
                              }
                            },
                            danger: {
                              label: "No",
                              className: "btn-danger",
                              callback: function() {

                              }
                            }
                          }
                        });
                   }
                   
                });
              }
            },
            danger: {
              label: "No",
              className: "btn-danger",
              callback: function() {
                
              }
            }
          }
        });
}

evaluaciones.initadd = function(){
    
    Dropzone.options.dictamenDropzone = {
        init: function(){
            this.on('success', function(file, data, xhr){
                console.log("ok");
                console.log(data);
                data = JSON.parse(data);
                console.log($(this.element).parent());
                $(this.element).parent().find('.dropzone').addClass('hide');
                $(this.element).parent().find('.dictamen_link a').prop('href', data.url);
                $(this.element).parent().find('.dictamen_link a').html(data.nombre);
                $(this.element).parent().find('.dictamen_link a').after(' <a href="#" class="modificar_dictamen">[Modificar]</a>');
                $(this.element).parent().find('.dictamen_link').removeClass('hide');
                this.removeAllFiles(true);
            });
            this.on('error', function(file, err, e){
                console.log(e);
            });
        }
    };
    Dropzone.options.predictamenDropzone = {
        init: function(){
            this.on('success', function(file, data, xhr){
                data = JSON.parse(data);
                console.log($(this.element).parent());
                $(this.element).parent().find('.dropzone').addClass('hide');
                $(this.element).parent().find('.predictamen_link a').prop('href', data.url);
                $(this.element).parent().find('.predictamen_link a').html(data.nombre);
                $(this.element).parent().find('.predictamen_link a').after(' <a href="#" class="modificar_predictamen">[Modificar]</a>');
                $(this.element).parent().find('.predictamen_link').removeClass('hide');
                this.removeAllFiles(true);
            });
        }
    };
    
    $(document).on('click', '.modificar_dictamen', function(e){
        e.preventDefault();
        $(this).parent().addClass('hide');        
        $(this).parent().parent().find('.dropzone').removeClass('hide');
        $(this).parent().find('.modificar_dictamen').remove();
    });
    $(document).on('click', '.modificar_predictamen', function(e){
        e.preventDefault();
        $(this).parent().addClass('hide');        
        $(this).parent().parent().find('.dropzone').removeClass('hide');
        $(this).parent().find('.modificar_predictamen').remove();
    });
    $('.fecha').datepicker();
   
    $('.sel-personas').select2({
        allowClear: true,
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=programas&accion=get_personas_p',
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
                var url = "index.php?mod=sievas&controlador=programas&accion=get_persona_p&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nombres+' '+(d.primer_apellido == null ? '' : d.primer_apellido)+' '+(d.segundo_apellido == null ? '' : d.segundo_apellido);
        },
        formatSelection: function(d){
            return d.nombres+' '+(d.primer_apellido == null ? '' : d.primer_apellido)+' '+(d.segundo_apellido == null ? '' : d.segundo_apellido);
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    $('#ev_padre').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluaciones',
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
                var url = "index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluacion&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.etiqueta;
        },
        formatSelection: function(d){
            return d.etiqueta;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    $('#escala').select2({
        minimumInputText: 1,
        allowClear: true,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=evaluaciones&accion=get_escalas',
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
                var url = "index.php?mod=sievas&controlador=evaluaciones&accion=get_escala&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_gradacion;
        },
        formatSelection: function(d){
            return d.nom_gradacion;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    
    $('.sel-usuarios').select2({
        allowClear: true,
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=programas&accion=get_usuarios',
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
                var url = "index.php?mod=sievas&controlador=programas&accion=get_usuario&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nombres+' '+(d.primer_apellido == null ? '' : d.primer_apellido)+' '+(d.segundo_apellido == null ? '' : d.segundo_apellido);
        },
        formatSelection: function(d){
            return d.nombres+' '+(d.primer_apellido == null ? '' : d.primer_apellido)+' '+(d.segundo_apellido == null ? '' : d.segundo_apellido);
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
   

   
   $('#conjunto_lineamientos').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=evaluaciones&accion=get_lineamientos_conjuntos',
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
                var url = "index.php?mod=sievas&controlador=evaluaciones&accion=get_lineamientos_conjunto&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_conjunto;
        },
        formatSelection: function(d){
            return d.nom_conjunto;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
   
   $('#evaluado').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluados',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    tipo_evaluado: $('#tipo_evaluado').val()
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
                var url = "index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluado&id="+id+'&tipo_evaluado='+$('#tipo_evaluado').val();
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            switch(parseInt($('#tipo_evaluado').val())){
                case 1:
                    return d.nom_institucion;
                    break;
                case 2:
                    return '<b>'+d.programa+'</b><br/>'+d.nom_institucion;
                    break;
                case 3:
                    return '<b>'+d.nombres+'</b><br/>'+d.nom_institucion;
                    break;
                case 6:
                    return '<b>'+d.programa+'</b><br/>'+d.nom_institucion;
                    break;
                case 10:
                    return '<b>'+d.programa+'</b><br/>'+d.nom_institucion;
                    break;
                case 11:
                    return '<b>'+d.titulo+'</b>';
                    break;
            }
            
        },
        formatSelection: function(d){
            switch(parseInt($('#tipo_evaluado').val())){
                case 1:
                    return d.nom_institucion;
                    break;
                case 2:
                    return d.programa;
                    break;
                case 3:
                    return '<b>'+d.nombres+'</b><br/>'+d.nom_institucion;
                    break;
                case 6:
                    return d.programa;
                    break;
                case 10:
                    return d.programa;
                    break;
                case 11:
                    return d.titulo;
                    break;
            }
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
   
   $('#tipo_evaluado').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=evaluaciones&accion=get_niveles_autoevaluacion',
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
                var url = "index.php?mod=sievas&controlador=evaluaciones&accion=get_nivel_autoevaluacion&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nivel;
            
        },
        formatSelection: function(d){
            return d.nivel;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    }).on('change', function(){
        $('#evaluado').select2('val', '');
    });;
   
   $(document).on('load_select_momentos', function(e, data){
       if(typeof data === 'undefined'){
           $('.momento').select2({
                minimumInputText: 1,
                placeholder: "Seleccione...",
                ajax:{
                    url:'index.php?mod=sievas&controlador=evaluaciones&accion=get_momentos',
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
                        var url = "index.php?mod=sievas&controlador=evaluaciones&accion=get_momento&id="+id;
                        var jqxhr = $.get(url);
                        jqxhr.done(function(data){
                            callback(data);
                        });
                    }
                },
                formatResult: function(d){
                    return d.momento;

                },
                formatSelection: function(d){
                    return d.momento;
                },
                dropdownCssClass: "bigdrop",
                escapeMarkup: function (m) { return m; }
            });
       }
       else{
           $(data.tipo_momento).select2({
                minimumInputText: 1,
                placeholder: "Seleccione...",
                ajax:{
                    url:'index.php?mod=sievas&controlador=evaluaciones&accion=get_momentos',
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
                        var url = "index.php?mod=sievas&controlador=instituciones&accion=get_momento&id="+id;
                        var jqxhr = $.get(url);
                        jqxhr.done(function(data){
                            callback(data);
                        });
                    }
                },
                formatResult: function(d){
                    return d.momento;

                },
                formatSelection: function(d){
                    return d.momento;
                },
                dropdownCssClass: "bigdrop",
                escapeMarkup: function (m) { return m; }
            });
       }
       
   });
   
   $(document).trigger('load_select_momentos');
   
   $(document).on('click', '.add-row', function(e){
        e.preventDefault();
        var data = $(this).data('index');
        data = data+1;
        $(this).html('-');
        $(this).removeClass('add-row');
        $(this).addClass('del-row');
        $('#fechas_inicio').append('<div class="input-group"><input type="text" name="fecha_inicio[]" class="fecha form-control fecha_inicio_'+data+'" data-date-format="yyyy-mm-dd" /><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span></div> ');
        $('#fechas_fin').append('<div class="input-group"><input type="text" name="fecha_fin[]" class="fecha form-control fecha_fin_'+data+'" data-date-format="yyyy-mm-dd" /><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span></div> ');
        $('#tipos_momentos').append('<input type="text" name="tipo_momento[]" class="momento form-control tipo_momento_'+data+'" />');
        $('#botones').append('<span data-index="'+(data)+'" class="boton_'+(data)+'"><a href="#" class="btn btn-default add-row row" data-index="'+(data)+'">+</a><br/></span>');
        $('.fecha').datepicker();
        $(document).trigger('load_select_momentos', { tipo_momento : '.tipo_momento_'+data});
   });
    
    $(document).on('click', '.del-row', function(e){
        e.preventDefault();
        var index = $(this).data('index');
        $(this).remove();
         $('#fechas_inicio .fecha_inicio_'+index).parent().remove();
         $('#fechas_fin .fecha_fin_'+index).parent().remove();
         $('#tipos_momentos .tipo_momento_'+index).remove();
         $('#botones .boton_'+index).remove();
    });
    
    $('#guardar-form').on('click', evaluaciones.guardar_evaluacion);
    
     for(var i = 1; i <= 10; i++){
         evaluaciones.loadpopup(i);
     }
    
}

evaluaciones.loadpopup = function(indice){
    $('#popup'+indice).popover({
           title : 'Añadir miembro comité',
           content: function(){
                var html = '';
                html += '<form>';
                html += '<div class="form-group">';
                html += '<label>Nombre</label>';
                html += '<input type="text" class="form-control nombre_miembro">';
                html += '<label>Email</label>';
                html += '<input type="text" class="form-control email_miembro">';
                html += '</div>';
                html += '<a href="#" class="btn btn-primary btn-sm agregar-miembro" data-target="#'+($('#popup'+indice).parent().find('input[type=hidden]').prop('id'))+'">Agregar</a>';              
                html += '</form>';
                return html;
           },
           placement: 'top',
           html : 'true',
           trigger : 'click',
           container : '#popover-wrapper-'+indice
       }).on('shown.bs.popover', function(){
           var self = this;
           $('#popover-wrapper-'+indice+' .agregar-miembro').on('click', function(e){               
               e.preventDefault();
               var nombre_miembro = $(e.target).parent().find('.nombre_miembro').val();
               var email_miembro = $(e.target).parent().find('.email_miembro').val();
               var selector = $(this).data('target');
               var cargo = $('#popup'+indice).data('cargo');
               var jqxhr = $.post('index.php?mod=sievas&controlador=evaluaciones&accion=crear_persona', {
                  nombre : nombre_miembro,
                  email : email_miembro,
                  cargo : cargo
               });
               
               jqxhr.done(function(data){
                  $(selector).select2('val', data.id); 
                  $('#popup'+indice).popover('hide');
               });
               
               jqxhr.fail(function(err){
                  bootbox.alert(err.responseText);
               });
           });
       });
       
}

evaluaciones.guardar_evaluacion = function(e){
    e.preventDefault();
    var form = $('#formEvaluacion').serialize();
    form += '&id='+$('#guardar-form').data('id');
    form += '&evaluacion_base='+$('#guardar-form').data('base');
    form += '&ev_red='+($('#ev_red').prop('checked') ? '1': '0');
    form += '&traduccion='+($('#traduccion').prop('checked') ? '1': '0');
    form += '&padre='+$('#ev_padre').select2('val');
    
    var escala = $('#escala').select2('val');
    if(escala > 0){
        form += '&escala='+$('#escala').select2('val');
    }
    
    var jqxhr = $.post('index.php?mod=sievas&controlador=evaluaciones&accion=guardar_evaluacion', form);
    
    jqxhr.done(function(data){
        bootbox.alert(data.mensaje, function(){
            window.location = "index.php?mod=sievas&controlador=evaluaciones&accion=listar_evaluaciones";
        });

    });
        
    jqxhr.fail(function(err){
       if(err.responseText != ''){
           bootbox.alert(err.responseText);
       } 
    });
}

evaluaciones.initmetaevaluacion = function(){
    $('#formMetaevaluacion').on('submit', function(e){
        e.preventDefault();
        var op_1 = $('#op_1').val();
        var op_2 = $('#op_2').val();
        var op_3 = $('#op_3').val();
        var op_4 = $('#op_4').val();
        var op_5 = $('#op_5').val();
        var op_6 = $('#op_6').val();
        var op_7 = $('#op_7').val();
        var op_8 = $('#op_8').val();
        var op_9 = $('#op_9').val();
        var op_10 = $('#op_10').val();
        var cal_1 = $('#cal_1').val();
        var cal_2 = $('#cal_2').val();
        var cal_3 = $('#cal_3').val();
        var cal_4 = $('#cal_4').val();
        var cal_5 = $('#cal_5').val();
        var cal_6 = $('#cal_6').val();
        var cal_7 = $('#cal_7').val();
        var cal_8 = $('#cal_8').val();
        var cal_9 = $('#cal_9').val();
        var cal_10 = $('#cal_10').val();
        
        var valid = true;
        var err_msg = [];
        
        if(!(cal_1 > 0)){
            valid = false;
            err_msg.push('No olvide ingresar la calificación del modelo de evaluación')
        }
        if(!(cal_2 > 0)){
            valid = false;
            err_msg.push('No olvide ingresar la calificación del contenido de los ítem')
        }
        if(!(cal_3 > 0)){
            valid = false;
            err_msg.push('No olvide ingresar la calificación de la estructura del sistema')
        }
        if(!(cal_4 > 0)){
            valid = false;
            err_msg.push('No olvide ingresar la calificación de las tablas estadísticas')
        }
        if(!(cal_5 > 0)){
            valid = false;
            err_msg.push('No olvide ingresar la calificación del uso informático del sistema SIEVAS')
        }
        if(!(cal_6 > 0)){
            valid = false;
            err_msg.push('No olvide ingresar la calificación del significado, referencias, bibliografía')
        }
        if(!(cal_7 > 0)){
            valid = false;
            err_msg.push('No olvide ingresar la calificación de las gráficas')
        }
        if(!(cal_8 > 0)){
            valid = false;
            err_msg.push('No olvide ingresar la calificación de la capacitación para la evaluacion interna')
        }
        if(!(cal_9 > 0)){
            valid = false;
            err_msg.push('No olvide ingresar la calificación de la evaluación externa')
        }
        if(!(cal_10 > 0)){
            valid = false;
            err_msg.push('No olvide ingresar la calificación de los procesos y procedimientos')
        }
        
        if(valid){
            var jqxhr = $.post('index.php?mod=sievas&controlador=evaluaciones&accion=guardar_metaevaluacion', {
                op_1 : op_1,
                op_2 : op_2,
                op_3 : op_3,
                op_4 : op_4,
                op_5 : op_5,
                op_6 : op_6,
                op_7 : op_7,
                op_8 : op_8,
                op_9 : op_9,
                op_10 : op_10,
                cal_1 : cal_1,
                cal_2 : cal_2,
                cal_3 : cal_3,
                cal_4 : cal_4,
                cal_5 : cal_5,
                cal_6 : cal_6,
                cal_7 : cal_7,
                cal_8 : cal_8,
                cal_9 : cal_9,
                cal_10 : cal_10
            });
            jqxhr.done(function(){
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
