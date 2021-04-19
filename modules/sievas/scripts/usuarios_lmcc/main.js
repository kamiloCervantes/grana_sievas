var usuarios = {};

usuarios.oTable = $('#data_tabla');
usuarios.crear_formacion = 0;
usuarios.initlistarmaestros = function(){
     usuarios.oTable.dataTable({
            "oLanguage":{"sUrl": "public/js/dataTables.spanish.txt"},
            "bServerSide": true,
            "sAjaxSource": "index.php?mod=sievas&controlador=usuarios&accion=get_dt_profesores",
            "bProcessing": true,            
            "fnServerParams": function(aoData){
                var fkeys = [];
                fkeys.push(
                    {nombre : 'gen_persona',fkey : 'cod_persona'}
                );
                
                aoData.push({"name": "sIndexColumn", "value": "id"});
                aoData.push({"name": "sTable", "value": "eval_profesores"});
                aoData.push({"name": "fKeys", "value": JSON.stringify(fkeys)});
            },
            "aoColumns":[
                {"mData": 0,"sName":"gen_persona.nombres"
                    },
                {"mData": 1,"sName":"gen_persona.email",
                   },                               
                {
                    "mData":2,
                    "sName":"eval_profesores.id",
                    "mRender":function(id){
                        return '<a href="index.php?mod=sievas&controlador=usuarios&accion=editar_docente&id='+id+'"><i class="glyphicon glyphicon-edit"></i></a><a href="#" class="eliminar-evaluador" data-id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a>';
                    },
                    "bSortable" : false,
                    
                }
            ]    
    });
    
}

usuarios.initlistarusuarios = function(){
    $('#data_tabla').dataTable({
            "oLanguage":{"sUrl": "public/js/dataTables.spanish.txt"},
            "bServerSide": true,
            "sAjaxSource": "index.php?mod=sievas&controlador=usuarios&accion=get_dt_usuarios",
            "bProcessing": true,            
            "fnServerParams": function(aoData){
                var fkeys = [];
                fkeys.push(
                    {nombre : 'gen_persona',fkey : 'cod_persona'}
                );
                
                aoData.push({"name": "sIndexColumn", "value": "username"});
                aoData.push({"name": "sTable", "value": "sys_usuario"});
                aoData.push({"name": "fKeys", "value": JSON.stringify(fkeys)});
            },
            "aoColumns":[
                {"mData": 0,"sName":"sys_usuario.username"
                    },
                {"mData": 1,"sName":"gen_persona.nombres",
                   },
                {"mData": 2,"sName":"sys_usuario.fecha_creado",
                    },
                {"mData": 3,"sName":"sys_usuario.creador",
                    },
                {
                    "mData":4,
                    "sName":"sys_usuario.username",
                    "mRender":function(id){
                        return '<a href="index.php?mod=sievas&controlador=usuarios&accion=cambiar_clave&u='+id+'"><i class="glyphicon glyphicon-lock"></i></a> <a href="index.php?mod=sievas&controlador=usuarios&accion=editar_usuario&id='+id+'"><i class="glyphicon glyphicon-edit"></i></a> <a href="#" class="eliminar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a>';
                    },
                    "bSortable" : false,
                    
                }
            ]    
    });
}

usuarios.initautorizar = function(){
    $('#data_tabla').dataTable({
            "oLanguage":{"sUrl": "public/js/dataTables.spanish.txt"},
             "aoColumns":[
                 null, 
                 null, 
                 null,
                 { "bSortable" : false }
             ]            
    });    
    $(document).on('click', '.autorizar-usuarios', usuarios.autorizar_usuario);
}

usuarios.autorizar_usuario = function(e){
    e.preventDefault();
    var self = this;
    var dialog = bootbox.dialog({
        message: "¿Esta seguro que desea autorizar al usuario?",
        title: 'Autorizar usuario',
        buttons: {
            success: {
              label: "Si",
              className: "btn-success",
              callback: function() {
                var username = $(self).data('usuario');
                var jqxhr = $.post('index.php?mod=sievas&controlador=usuarios&accion=autorizar_usuario', {
                    username : username
                });

                jqxhr.done(function(data){
                   bootbox.alert(data.mensaje, function(){
                       window.location.reload();
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



usuarios.initevaluador = function(){
    $('#fecha_nacimiento').datepicker();
    $('#genero').select2({
        minimumInputText: 1,
        placeholder: "Seleccione..."
    });
    $('#tipo_evaluador').select2({
        minimumInputText: 1,
        placeholder: "Seleccione..."
    });
    $('#pais').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=gen_paises&accion=get_paises',
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
                var url = "index.php?mod=sievas&controlador=gen_paises&accion=get_pais&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_pais;
        },
        formatSelection: function(d){
            return d.nom_pais;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#estado_civil').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=usuarios&accion=get_estados_civiles',
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
                var url = "index.php?mod=sievas&controlador=usuarios&accion=get_estado_civil&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.estado_civil;
        },
        formatSelection: function(d){
            return d.estado_civil;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#cargo_evaluador').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=usuarios&accion=get_cargos_evaluador',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    tipo_evaluacion : $('#tipo_evaluador').val()
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
                var url = "index.php?mod=sievas&controlador=usuarios&accion=get_cargo_evaluador&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.cargo;
        },
        formatSelection: function(d){
            return d.cargo;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#nivel_formacion').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=instituciones&accion=get_niveles_formacion',
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
                var url = "index.php?mod=sievas&controlador=instituciones&accion=get_nivel_formacion&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nivel_formacion;
        },
        formatSelection: function(d){
            return d.nivel_formacion;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    $('#guardar-form').on('click', usuarios.guardar_evaluador);
    
    $(document).on('click', '.add-row', function(e){
        e.preventDefault();
        var data = $(this).data('index');
        data = data+1;
        $(this).html('-');
        $(this).prop('title', 'Eliminar experiencia laboral');
        $(this).removeClass('add-row');
        $(this).addClass('del-row');
        $('#instituciones').append('<input type="text" name="exp_institucion_experiencia[]" class="form-control institucion_'+data+'" />');
        $('#status_laborales').append('<input type="text" name="exp_status_laboral[]" class="form-control status_laboral_'+data+'" />');
        $('#periodos').append('<input type="text" name="exp_periodo[]" class="form-control periodo_'+data+'" />');
        $('#botones').append('<span data-index="'+(data)+'" class="boton_'+(data)+'"><a href="#" class="btn btn-default add-row row" data-index="'+(data)+'"  title="Añadir nueva experiencia laboral">+</a><br/></span>');
    });
    
    $(document).on('click', '.del-row', function(e){
        e.preventDefault();
        var index = $(this).data('index');
        $(this).remove();
         $('#instituciones .institucion_'+index).remove();
         $('#status_laborales .status_laboral_'+index).remove();
         $('#periodos .periodo_'+index).remove();
         $('#botones .boton_'+index).remove();
    });
    
    $('#foto').on('change', function(e){
        var file = $(this)[0].files[0];
        var type = file.type;
        var index = type.indexOf('image');
        
        if(index > -1){
            var formData = new FormData();
            formData.append('archivo', file);
            var jqxhr = $.ajax({
                url: 'index.php?mod=sievas&controlador=usuarios&accion=guardar_imagen',
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST'                
              });
              
            jqxhr.done(function(data){
                $('#profile-pic').prop('src', data.ruta);
                $('#profile-pic').data('foto', data.id);
            });
        }
        else{
            bootbox.alert("El archivo debe ser una imagen");
        }
        
    });
    
    
}

usuarios.guardarusuario = function(rol_check){
       //guardar usuario
      var usuario = $('#guardar-form').data('usuario'); 
      var nombre = $('#nombre').val();
      var pais = $('#pais').val();
      var genero = $('#genero').val();
      var email = $('#email').val();
      var direccion = $('#direccion').val();
      var celular = $('#celular').val();
      var tipodocumento = $('input[name=tipodocumento]:checked').val();
      var documento = $('#documento').val();
      var fechanacimiento = $('#fecha_nacimiento').val();
      var emailpersonal = $('#email_personal').val();
      var telefono = $('#telefono').val();
      var skype = $('#skype').val();
      var foto = $('#foto').val();
      var nombreusuario = $('#nombre_usuario').val();
      var passwd1 = $('#passwd1').val();
      var passwd2 = $('#passwd2').val();
      var jqxhr = $.post('index.php?mod=sievas&controlador=usuarios&accion=guardar_usuario', {
          nombre : nombre,
          pais : pais,
          genero : genero,
          email : email,
          direccion : direccion,
          celular : celular,
          tipodocumento : tipodocumento,
          documento : documento,
          fechanacimiento : fechanacimiento,
          emailpersonal : emailpersonal,
          telefono : telefono,
          skype : skype,
          foto : foto,
          nombreusuario : nombreusuario,
          passwd1 : passwd1,
          passwd2 : passwd2,
          usuario_id : usuario
      });
      
      jqxhr.fail(function(err){
          bootbox.alert(err.responseText);
          if(typeof(rol_check) != 'undefined'){
              $(rol_check).prop('checked', false);
          }
      });
      
      jqxhr.done(function(data){
          $('#guardar-form').data('usuario', data.usuario); 
          usuarios.asignarroles(rol_check);
      });
}

usuarios.asignarroles = function(rol_check){
    if(parseInt($(rol_check).val()) === 2 && $(rol_check).prop('checked') === true){
        if($(rol_check).parent().find('.ver_evaluaciones').length === 0){
            $(rol_check).parent().append('<a href="#" title="Ver evaluaciones asociadas al usuario" class="ver_evaluaciones"><i class="glyphicon glyphicon-search"></i></a>');
        }
        
        var usuario = $('#guardar-form').data('usuario'); 
        var jqxhr = $.get('index.php?mod=sievas&controlador=usuarios&accion=get_evaluaciones_usuario', {
            usuario : usuario
        });
        
        jqxhr.done(function(data){
         
        var html = '';
        html += '<div>';
        html += '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
        html += '<div class="panel panel-default">';
        html += '<div class="panel-heading" role="tab" id="headingOne">';
        html += '<h4 class="panel-title">';
        html += '<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">';
        html += '<p>Seleccionar evaluación</p>';
        html += '</a>';
        html += '</h4>';
        html += '</div>';
        html += '<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">';
        html += '<div class="panel-body">';
        html += '<form>';
        html += '<div class="row">';
        html += '<div class="col-sm-6">';
        html += '<div class="form-group">';
        html += '<label class="control-label">País</label>';
        html += '<input type="text" name="filtro_pais" id="filtro_pais" class="form-control" />';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-sm-6">';
        html += '<div class="form-group">';
        html += '<label class="control-label">Tipo de evaluado</label>';
        html += '<input type="text" name="filtro_tipo_evaluado" id="filtro_tipo_evaluado" class="form-control" />';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-sm-6">';
        html += '<div class="form-group">';
        html += '<label class="control-label">Evaluado</label>';
        html += '<input type="text" name="filtro_evaluado" id="filtro_evaluado" class="form-control" />';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-sm-6">';
        html += '<div class="form-group">';
        html += '<label class="control-label">Evaluación</label>';
        html += '<input type="text" name="filtro_evaluacion" id="filtro_evaluacion" class="form-control" />';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '<div>';
        html += '<a href="#" class="btn btn-primary pull-right" id="seleccionar_evaluacion">Seleccionar</a>';
        html += '</div>';
        html += '</form>';        
        html += '</div>';
        html += '</div>';        
        html += '</div>';
        html += '<br/>';
        html += '<table id="evaluaciones" class="table">';
        html += '<tr>';
        html += '<th>';
        html += 'Evaluación';
        html += '</th>';
        html += '<th>';
        html += 'Cargo';
        html += '</th>';
        html += '<th>';
        html += '&nbsp;';
        html += '</th>';
        html += '</tr>';
        $.each(data, function(index,value){
            html += '<tr class="evaluacion" data-comite="'+value.comite+'">';
            html += '<td>'+value.etiqueta+'</td>';
            html += '<td>';
            html += '<select class="form-control input-sm selector-cargo" data-cargo="'+value.cargo+'">';
            html += '<option value="1">Coordinador Comité interno</option>';
            html += '<option value="2">Miembro Comité interno</option>';
            html += '<option value="3">Coordinador Comité externo</option>';
            html += '<option value="4">Miembro Comité externo</option>';
            html += '</select>';
            html += '</td>';
            html += '<td><a href="#" class="eliminar-evaluacion"><i class="glyphicon glyphicon-trash"></i></a></td>';
            html += '</tr>'; 
        });
        
        html += '</table>';
        html += '</div>';
        html += '</div>';
       
        
        $html = $.parseHTML(html);
        $.each($('.selector-cargo', $html), function(idx,val){
           $(val).val($(val).data('cargo')).triggerHandler('change'); 
        });
        
        $('#seleccionar_evaluacion', $html).on('click', function(e){
            e.preventDefault();
            var evaluacion = $('#filtro_evaluacion').select2('data');
            
            
            if(evaluacion !== null && evaluacion.id > 0){
                var jqxhr = $.post('index.php?mod=sievas&controlador=usuarios&accion=guardar_rol_usuario', {
                    evaluacion : evaluacion.id,
                    usuario : usuario,
                    cargo : 1,
                    rol : 2
               });
               
               jqxhr.fail(function(err){
                   alert(err.responseText);
               });
               
               jqxhr.done(function(data){
                    var html = '<tr class="evaluacion" data-comite="'+data.comite+'">';
                    html += '<td>'+evaluacion.etiqueta+'</td>';
                    html += '<td>';
                    html += '<select class="form-control input-sm selector-cargo">';
                    html += '<option value="1">Coordinador Comité interno</option>';
                    html += '<option value="2">Miembro Comité interno</option>';
                    html += '<option value="3">Coordinador Comité externo</option>';
                    html += '<option value="4">Miembro Comité externo</option>';
                    html += '</select>';
                    html += '</td>';
                    html += '<td><a href="#" class="eliminar-evaluacion"><i class="glyphicon glyphicon-trash"></i></a></td>';
                    html += '</tr>';  
                    
                    $html = $.parseHTML(html);
                    
                    $('.selector-cargo', $html).on('change', function(){
                           var cargo = $(this).val();
                           var jqxhr = $.post('index.php?mod=sievas&controlador=usuarios&accion=guardar_rol_usuario', {
                                evaluacion : evaluacion.id,
                                usuario : usuario,
                                cargo : cargo,
                                rol : 2,
                                id_comite : $(this).parent().parent().data('comite')
                           });
                           
                           jqxhr.fail(function(err){
                               alert(err.responseText);
                           });
                    });
                    
                    $('#evaluaciones').append($html);
               });
                
            }
            else{
                alert("Debe seleccionar una evaluación");
            }
           
        });
          
        $('#filtro_pais', $html).select2({
            minimumInputText: 1,
            placeholder: "Seleccione...",
            ajax:{
                url:'index.php?mod=sievas&controlador=gen_paises&accion=get_paises',
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
                    var url = "index.php?mod=sievas&controlador=gen_paises&accion=get_pais&id="+id;
                    var jqxhr = $.get(url);
                    jqxhr.done(function(data){
                        callback(data);
                    });
                }
            },
            formatResult: function(d){
                return d.nom_pais;
            },
            formatSelection: function(d){
                return d.nom_pais;
            },
            dropdownCssClass: "bigdrop",
            escapeMarkup: function (m) { return m; }
        });
          
        $('#filtro_tipo_evaluado', $html).select2({
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
        });
          
        $('#filtro_evaluado', $html).select2({
            minimumInputText: 1,
            placeholder: "Seleccione...",
            ajax:{
                url:'index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluados',
                dataType: 'json',
                data: function(term, page){
                    return {
                        q: term,
                        page_limit: 10,
                        tipo_evaluado : $('#filtro_tipo_evaluado').val(),
                        pais : $('#filtro_pais').val()
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
                    var url = "index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluado&id="+id;
                    var jqxhr = $.get(url);
                    jqxhr.done(function(data){
                        callback(data);
                    });
                }
            },
            formatResult: function(d){
                return d.programa+' :: '+d.nom_institucion;
            },
            formatSelection: function(d){
                return d.programa;
            },
            dropdownCssClass: "bigdrop",
            escapeMarkup: function (m) { return m; }
        });
        
          
        $('#filtro_evaluacion', $html).select2({
            minimumInputText: 1,
            placeholder: "Seleccione...",
            ajax:{
                url:'index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluaciones_evaluado',
                dataType: 'json',
                data: function(term, page){
                    return {
                        q: term,
                        page_limit: 10,
                        tipo_evaluado : $('#filtro_tipo_evaluado').val(),
                        evaluado : $('#filtro_evaluado').val(),
                        rol : 1
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
                    var url = "index.php?mod=sievas&controlador=evaluaciones&accion=get_evaluado&id="+id;
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
        
        var dialog = bootbox.dialog({
        message: $html,
        title: 'Selección de evaluaciones',
        buttons: {
            success: {
              label: "Aceptar",
              className: "btn-success"
            }
          }
        });
        
        });
        
        
      }
      else{
          var usuario = $('#guardar-form').data('usuario'); 
          var rol = parseInt($(rol_check).val());
          var jqxhr = $.post('index.php?mod=sievas&controlador=usuarios&accion=guardar_rol_usuario', {
                usuario : usuario,
                rol : rol
           });
           
           jqxhr.fail(function(err){
               alert(err.responseText);
           });
      }
}

usuarios.eliminar_rol_usuario = function(){
    
}

usuarios.initagregarusuario = function(){
     $('#search').multiselect({
        search: {
            left: '<input type="text" name="q" class="form-control" placeholder="Buscar..." />',
            right: '<input type="text" name="q" class="form-control" placeholder="Buscar..." />',
        }
    });
    
    
    $(document).on('click', '.eliminar-evaluacion', function(){
        var id_comite = $(this).parent().parent().data('comite');
        console.log(id_comite);
        var self = this;
        var jqxhr = $.post('index.php?mod=sievas&controlador=usuarios&accion=eliminar_usuario_comite', {
            id_comite : id_comite
        });
        
        jqxhr.done(function(data){
              $(self).parent().parent().remove();
        });
          
          jqxhr.fail(function(err){
            if(err.responseText != ''){
                bootbox.alert(err.responseText);
            } 
          });
    });
    
    
    $(document).on('click','.ver_evaluaciones', function(e){
        e.preventDefault();
        var rol_check = $(this).parent().find('.rol');
        usuarios.asignarroles(rol_check);
    });
    
    $('.rol').on('change', function(){
      var usuario = $('#guardar-form').data('usuario'); 
      var self = this;
      if($(this).prop('checked')){
          if(usuario == null || usuario == ''){
            var dialog = bootbox.dialog({
              message: "Para asociar roles se deben guardar los cambios. ¿Desea continuar?",
              title: 'Guardar usuario',
              buttons: {
                  success: {
                    label: "Si",
                    className: "btn-success",
                    callback: function() {
                        usuarios.guardarusuario(self);
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
        else{
            usuarios.guardarusuario(self);
        }
      
      }
      else{
          //eliminar rol
          var jqxhr = $.post('index.php?mod=sievas&controlador=usuarios&accion=eliminar_rol_usuario', {
              rol : $(this).val(),
              usuario : usuario
          });
          
          jqxhr.done(function(data){
              $('.ver_evaluaciones').remove();
          });
          
          jqxhr.fail(function(err){
            if(err.responseText != ''){
                bootbox.alert(err.responseText);
            } 
          });
      }
      
    });
    
    $('#fecha_nacimiento').datepicker();
    $('#genero').select2({
        minimumInputText: 1,
        placeholder: "Seleccione..."
    });
    $('#tipo_evaluador').select2({
        minimumInputText: 1,
        placeholder: "Seleccione..."
    });
    $('#pais').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=gen_paises&accion=get_paises',
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
                var url = "index.php?mod=sievas&controlador=gen_paises&accion=get_pais&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_pais;
        },
        formatSelection: function(d){
            return d.nom_pais;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#foto').on('change', function(e){
        var file = $(this)[0].files[0];
        var type = file.type;
        var index = type.indexOf('image');
        
        if(index > -1){
            var formData = new FormData();
            formData.append('archivo', file);
            var jqxhr = $.ajax({
                url: 'index.php?mod=sievas&controlador=usuarios&accion=guardar_imagen',
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST'                
              });
              
            jqxhr.done(function(data){
                $('#profile-pic').prop('src', data.ruta);
                $('#profile-pic').data('foto', data.id);
                $('#foto_perfil').val(data.id);
            });
        }
        else{
            bootbox.alert("El archivo debe ser una imagen");
        }
        
    });
}

usuarios.initeditarusuario = function(){
     $('#search').multiselect({
        search: {
            left: '<input type="text" name="q" class="form-control" placeholder="Buscar..." />',
            right: '<input type="text" name="q" class="form-control" placeholder="Buscar..." />',
        }
    });
    
    var search = $('#search').data('default');

    if(search.toString().indexOf(',') > 0){
        search = search.split(',');
    }
    else{
        var tmp = search;
        search = [];
        search[0] = tmp;
        
    }
    var options = [];
    
    for (var i = 0; i < search.length; i++) {
        var option = $('#search option[value='+search[i]+']');
        if(option.val() > 0){
            options.push(option[0].outerHTML);
            option.remove();
        }
    }
    
    $('#search_to').append(options.join(''));
    
    $.each($('#search option'), function(index,value){
        
    });
    $(document).on('click', '.eliminar-evaluacion', function(){
        var id_comite = $(this).parent().parent().data('comite');
        console.log(id_comite);
        var self = this;
        var jqxhr = $.post('index.php?mod=sievas&controlador=usuarios&accion=eliminar_usuario_comite', {
            id_comite : id_comite
        });
        
        jqxhr.done(function(data){
              $(self).parent().parent().remove();
        });
          
          jqxhr.fail(function(err){
            if(err.responseText != ''){
                bootbox.alert(err.responseText);
            } 
          });
    });
    
    
    $(document).on('click','.ver_evaluaciones', function(e){
        e.preventDefault();
        var rol_check = $(this).parent().find('.rol');
        usuarios.asignarroles(rol_check);
    });
    
    $('.rol').on('change', function(){
      var usuario = $('#guardar-form').data('usuario'); 
      var self = this;
      if($(this).prop('checked')){
          if(usuario == null || usuario == ''){
            var dialog = bootbox.dialog({
              message: "Para asociar roles se deben guardar los cambios. ¿Desea continuar?",
              title: 'Guardar usuario',
              buttons: {
                  success: {
                    label: "Si",
                    className: "btn-success",
                    callback: function() {
                        usuarios.guardarusuario(self);
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
        else{
            usuarios.guardarusuario(self);
        }
      
      }
      else{
          //eliminar rol
          var jqxhr = $.post('index.php?mod=sievas&controlador=usuarios&accion=eliminar_rol_usuario', {
              rol : $(this).val(),
              usuario : usuario
          });
          
          jqxhr.done(function(data){
              $('.ver_evaluaciones').remove();
          });
          
          jqxhr.fail(function(err){
            if(err.responseText != ''){
                bootbox.alert(err.responseText);
            } 
          });
      }
      
    });
    
    var fecha = $('#fecha_nacimiento').data('default');
    $('#fecha_nacimiento').datepicker();
//    $('#fecha_nacimiento').datepicker('setDate', fecha);
//    $('#fecha_nacimiento').datepicker('update');
    $('#genero').select2({
        minimumInputText: 1,
        placeholder: "Seleccione..."
    });
    $('#tipo_evaluador').select2({
        minimumInputText: 1,
        placeholder: "Seleccione..."
    });
    $('#pais').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=gen_paises&accion=get_paises',
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
                var url = "index.php?mod=sievas&controlador=gen_paises&accion=get_pais&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_pais;
        },
        formatSelection: function(d){
            return d.nom_pais;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    }).triggerHandler('change');
    
    $('#foto').on('change', function(e){
        var file = $(this)[0].files[0];
        var type = file.type;
        var index = type.indexOf('image');
        
        if(index > -1){
            var formData = new FormData();
            formData.append('archivo', file);
            var jqxhr = $.ajax({
                url: 'index.php?mod=sievas&controlador=usuarios&accion=guardar_imagen',
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST'                
              });
              
            jqxhr.done(function(data){
                $('#profile-pic').prop('src', data.ruta);
                $('#profile-pic').data('foto', data.id);
                $('#foto_perfil').val(data.id);
            });
        }
        else{
            bootbox.alert("El archivo debe ser una imagen");
        }
        
    });
}

usuarios.guardar_evaluador = function(e){
    e.preventDefault();
    var form = $('#addEvaluador').serialize();
    form = form +'&publicaciones='+$('#publicaciones').data('content');
    form = form +'&foto='+$('#profile-pic').data('foto');
    var jqxhr = $.post('index.php?mod=sievas&controlador=usuarios&accion=guardar_evaluador', form);
    
    jqxhr.done(function(data){
        bootbox.alert(data.mensaje, function(){
            window.location = "index.php?mod=sievas&controlador=usuarios&accion=listar_evaluadores";
        });
    });
        
    jqxhr.fail(function(err){
       if(err.responseText != ''){
           bootbox.alert(err.responseText);
       } 
    });
}

usuarios.initexperto = function(){
    $('#fecha_nacimiento').datepicker();
    $('#genero').select2({
        minimumInputText: 1,
        placeholder: "Seleccione..."
    });
    $('#tipo_evaluador').select2({
        minimumInputText: 1,
        placeholder: "Seleccione..."
    });
    $('#pais').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=gen_paises&accion=get_paises',
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
                var url = "index.php?mod=sievas&controlador=gen_paises&accion=get_pais&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_pais;
        },
        formatSelection: function(d){
            return d.nom_pais;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#estado_civil').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=usuarios&accion=get_estados_civiles',
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
                var url = "index.php?mod=sievas&controlador=usuarios&accion=get_estado_civil&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.estado_civil;
        },
        formatSelection: function(d){
            return d.estado_civil;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#cargo_evaluador').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=usuarios&accion=get_cargos_evaluador',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    tipo_evaluacion : $('#tipo_evaluador').val()
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
                var url = "index.php?mod=sievas&controlador=usuarios&accion=get_cargo_evaluador&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.cargo;
        },
        formatSelection: function(d){
            return d.cargo;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#nivel_formacion').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=instituciones&accion=get_niveles_formacion',
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
                var url = "index.php?mod=sievas&controlador=instituciones&accion=get_nivel_formacion&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nivel_formacion;
        },
        formatSelection: function(d){
            return d.nivel_formacion;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    $('#guardar-form').on('click', usuarios.guardar_experto);
    
    $(document).on('click', '.add-row', function(e){
        e.preventDefault();
        var data = $(this).data('index');
        data = data+1;
        $(this).html('-');
        $(this).prop('title', 'Eliminar experiencia laboral');
        $(this).removeClass('add-row');
        $(this).addClass('del-row');
        $('#instituciones').append('<input type="text" name="exp_institucion_experiencia[]" class="form-control institucion_'+data+'" />');
        $('#status_laborales').append('<input type="text" name="exp_status_laboral[]" class="form-control status_laboral_'+data+'" />');
        $('#periodos').append('<input type="text" name="exp_periodo[]" class="form-control periodo_'+data+'" />');
        $('#botones').append('<span data-index="'+(data)+'" class="boton_'+(data)+'"><a href="#" class="btn btn-default add-row row" data-index="'+(data)+'"  title="Añadir nueva experiencia laboral">+</a><br/></span>');
    });
    
    $(document).on('click', '.del-row', function(e){
        e.preventDefault();
        var index = $(this).data('index');
        $(this).remove();
         $('#instituciones .institucion_'+index).remove();
         $('#status_laborales .status_laboral_'+index).remove();
         $('#periodos .periodo_'+index).remove();
         $('#botones .boton_'+index).remove();
    });
    
    $('#foto').on('change', function(e){
        var file = $(this)[0].files[0];
        var type = file.type;
        var index = type.indexOf('image');
        
        if(index > -1){
            var formData = new FormData();
            formData.append('archivo', file);
            var jqxhr = $.ajax({
                url: 'index.php?mod=sievas&controlador=usuarios&accion=guardar_imagen',
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST'                
              });
              
            jqxhr.done(function(data){
                $('#profile-pic').prop('src', data.ruta);
                $('#profile-pic').data('foto', data.id);
            });
        }
        else{
            bootbox.alert("El archivo debe ser una imagen");
        }
        
    });
}

usuarios.guardar_experto = function(e){
    e.preventDefault();
    var form = $('#addEvaluador').serialize();
    form = form +'&publicaciones='+$('#publicaciones').data('content');
    form = form +'&foto='+$('#profile-pic').data('foto');
    var jqxhr = $.post('index.php?mod=sievas&controlador=usuarios&accion=guardar_experto', form);
    
    jqxhr.done(function(data){
        bootbox.alert(data.mensaje, function(){
            window.location = "index.php?mod=sievas&controlador=usuarios&accion=listar_expertos";
        });
    });
        
    jqxhr.fail(function(err){
       if(err.responseText != ''){
           bootbox.alert(err.responseText);
       } 
    });
}

usuarios.initprofesor = function(){
    $('#fecha_nacimiento').datepicker();
    $('#genero').select2({
        minimumInputText: 1,
        placeholder: "Seleccione..."
    });

    $('#pais').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=gen_paises&accion=get_paises',
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
                var url = "index.php?mod=sievas&controlador=gen_paises&accion=get_pais&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data[0]);
                });
            }
        },
        formatResult: function(d){
            return d.nom_pais;
        },
        formatSelection: function(d){
            return d.nom_pais;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#estado_civil').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=usuarios&accion=get_estados_civiles',
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
                var url = "index.php?mod=sievas&controlador=usuarios&accion=get_estado_civil&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data[0]);
                });
            }
        },
        formatResult: function(d){
            return d.estado_civil;
        },
        formatSelection: function(d){
            return d.estado_civil;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    
    $('#nivel_formacion').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=instituciones&accion=get_niveles_formacion',
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
                var url = "index.php?mod=sievas&controlador=instituciones&accion=get_nivel_formacion&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data[0]);
                });
            }
        },
        formatResult: function(d){
            return d.nivel_formacion;
        },
        formatSelection: function(d){
            return d.nivel_formacion;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#institucion').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=programas&accion=get_instituciones',
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
                var url = "index.php?mod=sievas&controlador=programas&accion=get_institucion&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data[0]);
                });
            }
        },
        formatResult: function(d){
            return d.nom_institucion;
        },
        formatSelection: function(d){
            return d.nom_institucion;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#programa').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=usuarios&accion=get_programas',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    institucion : $('#institucion').val()
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
                var url = "index.php?mod=sievas&controlador=usuarios&accion=get_programa&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data[0]);
                });
            }
        },
        formatResult: function(d){
            return d.programa;
        },
        formatSelection: function(d){
            return d.programa;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    $('#guardar-form').on('click', usuarios.guardar_profesor);
    
    $(document).on('click', '.add-row', function(e){
        e.preventDefault();
        var data = $(this).data('index');
        data = data+1;
        $(this).html('-');
        $(this).removeClass('add-row');
        $(this).addClass('del-row');
        $('#instituciones').append('<input type="text" name="exp_institucion_experiencia[]" class="form-control institucion_'+data+'" />');
        $('#status_laborales').append('<input type="text" name="exp_status_laboral[]" class="form-control status_laboral_'+data+'" />');
        $('#periodos').append('<input type="text" name="exp_periodo[]" class="form-control periodo_'+data+'" />');
        $('#botones').append('<span data-index="'+(data)+'" class="boton_'+(data)+'"><a href="#" class="btn btn-default add-row row" data-index="'+(data)+'">+</a><br/></span>');
    });
    
    $(document).on('click', '.del-row', function(e){
        e.preventDefault();
        var index = $(this).data('index');
        $(this).remove();
         $('#instituciones .institucion_'+index).remove();
         $('#status_laborales .status_laboral_'+index).remove();
         $('#periodos .periodo_'+index).remove();
         $('#botones .boton_'+index).remove();
    });
    
    $('#foto').on('change', function(e){
        var file = $(this)[0].files[0];
        var type = file.type;
        var index = type.indexOf('image');
        
        if(index > -1){
            var formData = new FormData();
            formData.append('archivo', file);
            var jqxhr = $.ajax({
                url: 'index.php?mod=sievas&controlador=usuarios&accion=guardar_imagen',
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST'                
              });
              
            jqxhr.done(function(data){
                $('#profile-pic').prop('src', data.ruta);
                $('#profile-pic').data('foto', data.id);
            });
        }
        else{
            bootbox.alert("El archivo debe ser una imagen");
        }        
    });
}

usuarios.initeditarevaluador = function(){
    $('#fecha_nacimiento').datepicker();
    $('#genero').select2({
        minimumInputText: 1,
        placeholder: "Seleccione..."
    });
    $('#tipo_evaluador').select2({
        minimumInputText: 1,
        placeholder: "Seleccione..."
    });
    $('#pais').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=gen_paises&accion=get_paises',
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
                var url = "index.php?mod=sievas&controlador=gen_paises&accion=get_pais&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_pais;
        },
        formatSelection: function(d){
            return d.nom_pais;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#estado_civil').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=usuarios&accion=get_estados_civiles',
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
                var url = "index.php?mod=sievas&controlador=usuarios&accion=get_estado_civil&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.estado_civil;
        },
        formatSelection: function(d){
            return d.estado_civil;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#cargo_evaluador').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=usuarios&accion=get_cargos_evaluador',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    tipo_evaluacion : $('#tipo_evaluador').val()
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
                var url = "index.php?mod=sievas&controlador=usuarios&accion=get_cargo_evaluador&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.cargo;
        },
        formatSelection: function(d){
            return d.cargo;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#nivel_formacion').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=instituciones&accion=get_niveles_formacion',
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
                var url = "index.php?mod=sievas&controlador=instituciones&accion=get_nivel_formacion&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nivel_formacion;
        },
        formatSelection: function(d){
            return d.nivel_formacion;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    $('#guardar-form').on('click', usuarios.guardar_evaluador);
    
    $(document).on('click', '.add-row', function(e){
        e.preventDefault();
        var data = $(this).data('index');
        data = data+1;
        $(this).html('-');
        $(this).removeClass('add-row');
        $(this).addClass('del-row');
        $('#instituciones').append('<input type="text" name="exp_institucion_experiencia[]" class="form-control institucion_'+data+'" />');
        $('#status_laborales').append('<input type="text" name="exp_status_laboral[]" class="form-control status_laboral_'+data+'" />');
        $('#periodos').append('<input type="text" name="exp_periodo[]" class="form-control periodo_'+data+'" />');
        $('#botones').append('<span data-index="'+(data)+'" class="boton_'+(data)+'"><a href="#" class="btn btn-default add-row row" data-index="'+(data)+'">+</a><br/></span>');
    });
    
    $('#academica input').on('change', function(){
        usuarios.crear_formacion = 1;
    });
    
    $(document).on('click', '.del-row', function(e){
        e.preventDefault();
        var index = $(this).data('index');
        $(this).remove();
         $('#instituciones .institucion_'+index).remove();
         $('#status_laborales .status_laboral_'+index).remove();
         $('#periodos .periodo_'+index).remove();
         $('#botones .boton_'+index).remove();
    });
    
    $('#foto').on('change', function(e){
        var file = $(this)[0].files[0];
        var type = file.type;
        var index = type.indexOf('image');
        
        if(index > -1){
            var formData = new FormData();
            formData.append('archivo', file);
            var jqxhr = $.ajax({
                url: 'index.php?mod=sievas&controlador=usuarios&accion=guardar_imagen',
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST'                
              });
              
            jqxhr.done(function(data){
                $('#profile-pic').prop('src', data.ruta);
                $('#profile-pic').data('foto', data.id);
            });
        }
        else{
            bootbox.alert("El archivo debe ser una imagen");
        }
        
    });
}

usuarios.guardar_evaluador = function(e){
    e.preventDefault();
    $('#addEvaluador').validate({
        rules : {
            nombre: {
                required : true
            },
            email_personal: {
                required : true
            },
            nombre_usuario:{
                required : true
            }
        }
    });
    if($('#addEvaluador').valid()){
        var form = $('#addEvaluador').serialize();
        form = form +'&publicaciones='+$('#publicaciones').data('content');
        form = form +'&foto='+$('#profile-pic').data('foto');
        form = form +'&id='+$(this).data('evaluador');
        form = form +'&formacion='+usuarios.crear_formacion;
        var jqxhr = $.post('index.php?mod=sievas&controlador=usuarios&accion=guardar_evaluador', form);

        jqxhr.done(function(data){
            bootbox.alert(data.mensaje, function(){
                window.location = "index.php?mod=sievas&controlador=usuarios&accion=listar_evaluadores";
            });
        });

        jqxhr.fail(function(err){
           if(err.responseText != ''){
               bootbox.alert(err.responseText);
           } 
        });
    }
    
}

usuarios.initprofesor = function(){
    $('#fecha_nacimiento').datepicker();
    $('#genero').select2({
        minimumInputText: 1,
        placeholder: "Seleccione..."
    });

    $('#pais').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=gen_paises&accion=get_paises',
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
                var url = "index.php?mod=sievas&controlador=gen_paises&accion=get_pais&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_pais;
        },
        formatSelection: function(d){
            return d.nom_pais;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });

    $('#pais_docente').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=gen_paises&accion=get_paises',
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
                var url = "index.php?mod=sievas&controlador=gen_paises&accion=get_pais&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_pais;
        },
        formatSelection: function(d){
            return d.nom_pais;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#estado_civil').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=usuarios&accion=get_estados_civiles',
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
                var url = "index.php?mod=sievas&controlador=usuarios&accion=get_estado_civil&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data[0]);
                });
            }
        },
        formatResult: function(d){
            return d.estado_civil;
        },
        formatSelection: function(d){
            return d.estado_civil;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    
    $('.nivel_formacion').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=instituciones&accion=get_niveles_formacion',
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
                var url = "index.php?mod=sievas&controlador=instituciones&accion=get_nivel_formacion&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nivel_formacion;
        },
        formatSelection: function(d){
            return d.nivel_formacion;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#institucion').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=programas&accion=get_instituciones',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    pais : $('#pais_docente').val()
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
                var url = "index.php?mod=sievas&controlador=programas&accion=get_institucion&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nom_institucion;
        },
        formatSelection: function(d){
            return d.nom_institucion;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#programa').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=usuarios&accion=get_programas',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    institucion : $('#institucion').val()
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
                var url = "index.php?mod=sievas&controlador=usuarios&accion=get_programa&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data[0]);
                });
            }
        },
        formatResult: function(d){
            return d.programa;
        },
        formatSelection: function(d){
            return d.programa;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    $('#guardar-form').on('click', usuarios.guardar_profesor);
    
    $(document).on('click', '.add-row', function(e){
        e.preventDefault();
        
        var html = '';
        html += '<div class="experiencia_laboral_item"> ';
        html += '<div class="col-sm-4">';
        html += '<div class="form-group">';
        html += '<label class="control-label">Institución</label>';
        html += '<div id="instituciones">';
        html += '<input type="text" name="institucion_exp_laboral" class="form-control institucion_exp_laboral" />';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-sm-4">';
        html += '<div class="form-group">';
        html += '<label class="control-label">Status laboral</label>';
        html += '<div id="status_laborales">';
        html += '<input type="text" name="status_laboral" class="form-control status_laboral" />';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-sm-3">';
        html += '<div class="form-group">';
        html += '<label class="control-label">Período</label>';
        html += '<div id="periodos">';
        html += '<input type="text" name="periodo" class="form-control periodo" />';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-sm-1">';
        html += '<div class="form-group">';
        html += '<label class="control-label">&nbsp;</label>';
        html += '<div id="botones">';
        html += '<span class="boton_0" data-index="0" style="margin-left: -15px;"><a href="#" class="btn btn-default add-row boton_0" data-index="0">+</a><br/></span>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        $(this).html(' - ');
        $(this).prop('title', 'Eliminar experiencia laboral');
        $(this).removeClass('add-row');
        $(this).addClass('del-row');
        
        $('#experiencia_laboral').append(html);

    });
    
    $(document).on('click', '.del-row', function(e){
        e.preventDefault();
        $(this).parent().parent().parent().parent().parent().remove();
    });
    
    $(document).on('click', '.add-row-fa', function(e){
        e.preventDefault();
        
        var tpl = '';
        tpl += '<div class="formacion_academica_item">';
        tpl += '<div class="col-sm-2">';
        tpl += '<div class="form-group">';
        tpl += '<label class="control-label">Nivel de formación</label>';
        tpl += '<input type="hidden" name="nivel_formacion" class="form-control nivel_formacion">';
        tpl += '</div>';
        tpl += '</div>';
        tpl += '<div class="col-sm-3">';
        tpl += '<div class="form-group">';
        tpl += '<label class="control-label">Año de egreso</label>';
        tpl += '<input type="text" name="anio_egreso" class="form-control anio_egreso" />';
        tpl += '</div>';
        tpl += '</div>';
        tpl += '<div class="col-sm-3">';
        tpl += '<div class="form-group">';
        tpl += '<label class="control-label">Título obtenido</label>';
        tpl += '<input type="text" name="titulo_profesor" class="form-control titulo_profesor" />';
        tpl += '</div>';
        tpl += '</div>';
        tpl += '<div class="col-sm-3">';
        tpl += '<div class="form-group">';
        tpl += '<label class="control-label">Institución que otorga el título</label>';
        tpl += '<input type="text" name="institucion_titulo" class="form-control institucion_titulo" />';
        tpl += '</div>';
        tpl += '</div>';
        tpl += '<div class="col-sm-1">';
        tpl += '<div class="form-group">';
        tpl += '<label class="control-label">&nbsp;</label>';
        tpl += '<div>';
        tpl += '<span class="formacion_academica_0" data-index="0" style="margin-left: -15px;"><a href="#" class="btn btn-default add-row-fa formacion_academica_0" data-index="0">+</a><br/></span>';
        tpl += '</div>';
        tpl += '</div>';
        tpl += '</div>';
        tpl += '</div>';              
        
        $tpl = $.parseHTML(tpl);
        
        $('.nivel_formacion', $tpl).select2({
            minimumInputText: 1,
            placeholder: "Seleccione...",
            ajax:{
                url:'index.php?mod=sievas&controlador=instituciones&accion=get_niveles_formacion',
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
                    var url = "index.php?mod=sievas&controlador=instituciones&accion=get_nivel_formacion&id="+id;
                    var jqxhr = $.get(url);
                    jqxhr.done(function(data){
                        callback(data);
                    });
                }
            },
            formatResult: function(d){
                return d.nivel_formacion;
            },
            formatSelection: function(d){
                return d.nivel_formacion;
            },
            dropdownCssClass: "bigdrop",
            escapeMarkup: function (m) { return m; }
        });
        
        $(this).removeClass('add-row-fa');
        $(this).addClass('del-row-fa');
        $(this).html('  -  ');
        
        $('#formacion_academica').append($tpl);        
        
    });
    
     $(document).on('click', '.del-row-fa', function(e){
        e.preventDefault();
       $(this).parent().parent().parent().parent().parent().remove();
    });
    
    $(document).on('click', '.add-row-of', function(e){
        e.preventDefault();
        var tpl = '';
        tpl += '<div class="otra_formacion_item">';
        tpl += '<div class="col-sm-11">';
        tpl += '<div class="form-group">';
        tpl += '<label class="control-label">&nbsp;</label>';
        tpl += '<textarea name="otra_formacion" class="form-control otra_formacion"></textarea>';
        tpl += '</div>';
        tpl += '</div>';
        tpl += '<div class="col-sm-1">';
        tpl += '<div class="form-group">';
        tpl += '<label class="control-label">&nbsp;</label>';
        tpl += '<div>';
        tpl += '<span class="otra_formacion_0" data-index="0" style="margin-left: -15px;"><a href="#" class="btn btn-default add-row-of otra_formacion_0" data-index="0">+</a><br/></span>';
        tpl += '</div>';
        tpl += '</div>';
        tpl += '</div>';
        tpl += '</div>';
        
        $tpl = $.parseHTML(tpl);
        
        $(this).removeClass('add-row-of');
        $(this).addClass('del-row-of');
        $(this).html('  -  ');
        
        $('#otra_formacion').append($tpl);       
    });
    
    $(document).on('click', '.del-row-of', function(e){
       e.preventDefault();
       $(this).parent().parent().parent().parent().parent().remove();
    });
    
    $('#foto').on('change', function(e){
        var file = $(this)[0].files[0];
        var type = file.type;
        var index = type.indexOf('image');
        
        if(index > -1){
            var formData = new FormData();
            formData.append('archivo', file);
            var jqxhr = $.ajax({
                url: 'index.php?mod=sievas&controlador=usuarios&accion=guardar_imagen',
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST'                
              });
              
            jqxhr.done(function(data){
                $('#profile-pic').prop('src', data.ruta);
                $('#profile-pic').data('foto', data.id);
            });
        }
        else{
            bootbox.alert("El archivo debe ser una imagen");
        }        
    });
}

usuarios.guardar_profesor = function(e){
    e.preventDefault();
//    var form = $('#addProfesor').serialize();
//    form = form +'&publicaciones='+$('#publicaciones').data('content');
//    form = form +'&foto='+$('#profile-pic').data('foto');

    var nombre = $('#nombre').val();
    var pais = $('#pais').val();
    var genero = $('#genero').val();
    var direccion = $('#direccion').val();
    var tipodocumento = $('#tipodocumento').val();
    var documento = $('#documento').val();
    var fecha_nacimiento = $('#fecha_nacimiento').val();    
    var email_personal = $('#email_personal').val();
    
    var telefono = $('#telefono').val(); 
    var foto = $('#profile-pic').data('foto');

    //usuario
    var nombre_usuario = $('#nombre_usuario').val(); 

    //datos profesor
    var institucion = $('#institucion').val();
    var publicaciones = $('#publicaciones').data('content');

    //info academica
    var formacion_academica = [];
    formacion_academica = $.map($('#formacion_academica .formacion_academica_item'), function(value, index){
        var formacion_academica_item = {};
        formacion_academica_item.nivel_formacion = $(value).find('.nivel_formacion').select2('val');
        formacion_academica_item.anio_egreso = $(value).find('.anio_egreso').val();
        formacion_academica_item.titulo_profesor = $(value).find('.titulo_profesor').val();
        formacion_academica_item.institucion_titulo = $(value).find('.titulo_profesor').val();
        return formacion_academica_item;
    });    
    

    //experiencia
    var experiencia = [];
    experiencia = $.map($('#experiencia_laboral .experiencia_laboral_item'), function(value, index){
        var experiencia_item = {};
        experiencia_item.institucion = $(value).find('.institucion_exp_laboral').val();
        experiencia_item.status_laboral = $(value).find('.status_laboral').val();
        experiencia_item.periodo = $(value).find('.periodo').val();
        return experiencia_item;
    });
    
    //validaciones
    var jqxhr = $.post('index.php?mod=sievas&controlador=usuarios&accion=guardar_profesor', {
        nombre : nombre,
        pais : pais,
        genero : genero,
        direccion : direccion,
        tipodocumento : tipodocumento,
        documento : documento,
        fecha_nacimiento : fecha_nacimiento,
        email_personal : email_personal,
        telefono : telefono,
        foto : foto,
        nombre_usuario : nombre_usuario,
        institucion : institucion,
        publicaciones : publicaciones,
        formacion_academica : JSON.stringify(formacion_academica),
        experiencia : JSON.stringify(experiencia),
        docente_id : $('#guardar-form').data('docente')
    });
    
    jqxhr.done(function(data){
        bootbox.alert(data.mensaje, function(){
            window.location = "index.php?mod=sievas&controlador=usuarios&accion=listar_docentes";
        });
    });
        
    jqxhr.fail(function(err){
       if(err.responseText != ''){
           bootbox.alert(err.responseText);
       } 
    });
}

