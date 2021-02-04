var programas = {};

programas.oTable = $('#data_tabla');

programas.initlistar = function(){
    programas.oTable.dataTable({
            "oLanguage":{"sUrl": "public/js/dataTables.spanish.txt"},
            "bServerSide": true,
            "sAjaxSource": "index.php?mod=sievas&controlador=programas&accion=get_dt_programas",
            "bProcessing": true,            
            "fnServerParams": function(aoData){
                var fkeys = [];
                fkeys.push(
                    {nombre : 'eval_instituciones',fkey : 'cod_institucion'},
                    {nombre : 'gen_paises',fkey : 'cod_pais'},
                    {nombre : 'niveles_academicos',fkey : 'cod_nivel_academico'}
                );
                
                aoData.push({"name": "sIndexColumn", "value": "id"});
                aoData.push({"name": "sTable", "value": "eval_programas"});
                aoData.push({"name": "fKeys", "value": JSON.stringify(fkeys)});
            },
            "aoColumns":[
                {"mData": 0,"sName":"eval_programas.programa"
                    },
                {"mData": 1,"sName":"eval_instituciones.nom_institucion",
                   },
                {"mData": 2,"sName":"gen_paises.nom_pais",
                    },
                {"mData": 3,"sName":"niveles_academicos.nivel_academico",
                    },
                {"mData": 4,"sName":"eval_programas.nombre_director",
                    },
                
                {
                    "mData":5,
                    "sName":"id",
                    "mRender":function(id){
                        return '<a href="index.php?mod=sievas&controlador=programas&accion=editar&id='+id+'" class="editar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-edit"></i></a><a href="#" class="eliminar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a>';
                    },
                    "bSortable" : false,
                    
                }
            ]    
    });
    $(document).on('click', '.eliminar-generico', programas.eliminarprograma);
}

programas.eliminarprograma = function(){
    var self = this;
    var dialog = bootbox.dialog({
        message: "¿Esta seguro que desea eliminar el programa?",
        title: 'Eliminar programa',
        buttons: {
            success: {
              label: "Si",
              className: "btn-success",
              callback: function() {
                var id = $(self).data('id');
                var jqxhr = $.post('index.php?mod=sievas&controlador=programas&accion=eliminar_programa', {
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

programas.initadd = function(){
    $('#area_conocimiento').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=observatorio&accion=get_areas_conocimiento',
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
                var url = "index.php?mod=sievas&controlador=observatorio&accion=get_area_conocimiento&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.area;
        },
        formatSelection: function(d){
            return d.area;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    }).on('change', function(){
         $('#area_nucleo').select2('val', '');
    });
    
    var tipo_cargo = $('#cargo_responsable').data('tipo_cargo');
    
    $('#cargo_responsable').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=programas&accion=get_cargos&tipo_cargo='+tipo_cargo,
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    tipo_cargo :tipo_cargo
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
                var url = "index.php?mod=sievas&controlador=programas&accion=get_cargo&id="+id;
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
    
    $('#area_nucleo').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=observatorio&accion=get_areas_nucleos',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    area_conocimiento: $('#area_conocimiento').val()
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
                var url = "index.php?mod=sievas&controlador=observatorio&accion=get_area_nucleo&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nucleo;
        },
        formatSelection: function(d){
            return d.nucleo;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
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
    }).on('change', function(){
        $('#institucion').select2('val', '');
    });
    
    $('#nivel_academico').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=instituciones&accion=get_niveles_academicos',
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
                var url = "index.php?mod=sievas&controlador=instituciones&accion=get_nivel_academico&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.nivel_academico;
        },
        formatSelection: function(d){
            return d.nivel_academico;
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
                    pais : $('#pais').val()
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
    
    $('.sel-personas').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=programas&accion=get_personas',
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
                var url = "index.php?mod=sievas&controlador=programas&accion=get_persona&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data[0]);
                });
            }
        },
        formatResult: function(d){
            return d.nombres;
        },
        formatSelection: function(d){
            return d.nombres;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#agregar-cargo').popover({
           title : 'Añadir título',
           content: function(){
                var html = '';
                html += '<form>';
                html += '<div class="form-group">';
                html += '<label>Título</label>';
                html += '<input type="text" class="form-control titulo-responsable">';  
                html += '</div>';
                html += '<br/>';
                html += '<br/>';
                html += '<a href="#" class="btn btn-primary btn-sm agregar-cargo">Agregar</a>';              
                html += '</form>';
                return html;
           },
           placement: 'top',
           html : 'true',
           trigger : 'click'
       }).on('shown.bs.popover', function(){
           $('.agregar-cargo').on('click', function(e){
               e.preventDefault();
               var titulo = $('.titulo-responsable').val();
               
               var jqxhr = $.post('index.php?mod=sievas&controlador=programas&accion=guardar_cargo', {
                   titulo : titulo,
                   tipo_cargo : tipo_cargo
               });
               
               jqxhr.done(function(data){
                  $('#cargo_responsable').select2('val', data.id); 
                  $('#agregar-cargo').popover('hide');
               });
               
               jqxhr.fail(function(err){
                  bootbox.alert(err.responseText);
               });
           });
//           var self = this;
//           $('#popover-wrapper-'+indice+' .agregar-miembro').on('click', function(e){               
//               e.preventDefault();
//               var nombre_miembro = $(e.target).parent().find('.nombre_miembro').val();
//               var email_miembro = $(e.target).parent().find('.email_miembro').val();
//               var selector = $(this).data('target');
//               var cargo = $('#popup'+indice).data('cargo');
//               var jqxhr = $.post('index.php?mod=sievas&controlador=evaluaciones&accion=crear_persona', {
//                  nombre : nombre_miembro,
//                  email : email_miembro,
//                  cargo : cargo
//               });
//               
//               jqxhr.done(function(data){
//                  $(selector).select2('val', data.id); 
//                  $('#popup'+indice).popover('hide');
//               });
//               
//               jqxhr.fail(function(err){
//                  bootbox.alert(err.responseText);
//               });
//           });
       });
    
    $('#guardar-form').on('click', programas.guardar_programa);
}


programas.guardar_programa = function(e){
    e.preventDefault();
    
    var form = $('#formPrograma').serialize();
    form += '&id='+$(this).data('id');
    var jqxhr = $.post('index.php?mod=sievas&controlador=programas&accion=guardar_programa', form);
    
    jqxhr.done(function(data){
        bootbox.alert(data.mensaje, function(){
            window.location = "index.php?mod=sievas&controlador=programas&accion=index";
        });

    });
        
    jqxhr.fail(function(err){
       if(err.responseText != ''){
           bootbox.alert(err.responseText);
       } 
    });
}