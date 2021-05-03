var instituciones = {};

instituciones.oTable = $('#data_tabla');

instituciones.initagregar = function(){
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
        $('#departamento').select2('val', '');
        $('#municipio').select2('val', '');
        var data = $(this).select2('data');
        $('#indicativo').val(data.indicativo);
    });
    
    $('#pais_origen').select2({
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
    
    $('#departamento').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=instituciones&accion=get_departamentos_pais',
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
                var url = "index.php?mod=sievas&controlador=instituciones&accion=get_departamento&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.departamento;
        },
        formatSelection: function(d){
            return d.departamento;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#municipio').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=instituciones&accion=get_municipios_departamento',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    dpto : $('#departamento').val()
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
                var url = "index.php?mod=sievas&controlador=instituciones&accion=get_municipio&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data);
                });
            }
        },
        formatResult: function(d){
            return d.municipio;
        },
        formatSelection: function(d){
            return d.municipio;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#guardar-form').on('click', instituciones.guardar_institucion);
    
}

instituciones.eliminarinstitucion = function(){
    var self = this;
    var dialog = bootbox.dialog({
        message: "¿Esta seguro que desea eliminar la institución?",
        title: 'Eliminar institución',
        buttons: {
            success: {
              label: "Si",
              className: "btn-success",
              callback: function() {
                var id = $(self).data('id');
                var jqxhr = $.post('index.php?mod=sievas&controlador=instituciones&accion=eliminar_institucion', {
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

instituciones.initlistar = function(){
    instituciones.oTable.dataTable({
            "oLanguage":{"sUrl": "public/js/dataTables.spanish.txt"},
            "bServerSide": true,
            "sAjaxSource": "index.php?mod=sievas&controlador=instituciones&accion=get_dt_instituciones",
            "bProcessing": true,            
            "fnServerParams": function(aoData){
                var fkeys = [];
                fkeys.push(
                    {nombre : 'niveles_academicos',fkey : 'cod_nivel_academico'},
                    {nombre : 'gen_paises',fkey : 'cod_pais'}
                );
                
                aoData.push({"name": "sIndexColumn", "value": "id"});
                aoData.push({"name": "sTable", "value": "eval_instituciones"});
                aoData.push({"name": "fKeys", "value": JSON.stringify(fkeys)});
            },
            "aoColumns":[
                {"mData": 0,"sName":"eval_instituciones.nom_institucion"
                    },
                {"mData": 1,"sName":"gen_paises.nom_pais"
                   },
                {"mData": 2,"sName":"niveles_academicos.nivel_academico"
                    },
                {"mData": 3,"sName":"eval_instituciones.email"
                    },              
                {
                    "mData":4,
                    "sName":"id",
                    "mRender":function(id){
                        return '<a href="index.php?mod=sievas&controlador=instituciones&accion=editar&id='+id+'" class="editar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-edit"></i></a><a href="#" class="eliminar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a>';
                    },
                    "bSortable" : false
                    
                }
            ]    
    });
    
    $(document).on('click', '.eliminar-generico', instituciones.eliminarinstitucion);
}

instituciones.guardar_institucion = function(e){
    e.preventDefault();
    //validaciones
    var form = $('#formInstitucion').serialize(); 
    form += '&id='+$('#guardar-form').data('id');

    var jqxhr = $.post('index.php?mod=sievas&controlador=instituciones&accion=guardar_institucion', form);
    
    jqxhr.done(function(data){
        bootbox.alert(data.mensaje, function(){
            window.location = "index.php?mod=sievas&controlador=instituciones";
        });

    });
        
    jqxhr.fail(function(err){
       if(err.responseText != ''){
           bootbox.alert(err.responseText);
       } 
    });
    
}

