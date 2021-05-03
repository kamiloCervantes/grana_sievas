var gen_paises = {};

gen_paises.oTable = $('#data_tabla');

gen_paises.init_listar = function(){
     gen_paises.oTable.dataTable({
            "oLanguage":{"sUrl": "public/js/dataTables.spanish.txt"},
            "bServerSide": true,
            "sAjaxSource": "index.php?mod=sievas&controlador=gen_paises&accion=get_dt_paises",
            "bProcessing": true,            
            "fnServerParams": function(aoData){
                var fkeys = [];
                fkeys.push(
                    {nombre : 'gen_continentes',fkey : 'cod_continente'},
                    {nombre : 'gen_paises_zonas',fkey : 'cod_zona'},
                    {nombre : 'gen_idiomas',fkey : 'cod_idioma'}
                );
                
                aoData.push({"name": "sIndexColumn", "value": "id"});
                aoData.push({"name": "sTable", "value": "gen_paises"});
                aoData.push({"name": "fKeys", "value": JSON.stringify(fkeys)});
            },
            "aoColumns":[
                {"mData": 0,"sName":"gen_paises.nom_pais"
                    },
                {"mData": 1,"sName":"gen_paises.nacionalidad",
                   },
                {"mData": 2,"sName":"gen_continentes.continente",
                    },
                {"mData": 3,"sName":"gen_paises_zonas.zona",
                    },
                {"mData": 4,"sName":"gen_idiomas.nombre","bSortable" : false,
                    },
                {
                    "mData":5,
                    "sName":"id",
                    "mRender":function(id){
                        return '<a href="index.php?mod=academico&controlador=aca_colegios&accion=editar&id='+id+'" class="editar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-edit"></i></a><a href="#" class="eliminar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a><a href="index.php?mod=academico&controlador=aca_colegios&accion=sedes&id='+id+'" class="detalle-generico" data-id="'+id+'"><i class="glyphicon glyphicon-search"></i></a>';
                    },
                    "bSortable" : false,
                    
                }
            ]    
    });
}

gen_paises.init_add = function(){
    //guardar pais
    $('#guardar-pais').on('click', gen_paises.guardar_pais);
    
    //init selectores
    $('#continente').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=gen_paises&accion=get_continentes',
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
                var url = "#&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data[0]);
                });
            }
        },
        formatResult: function(d){
            return d.continente;
        },
        formatSelection: function(d){
            return d.continente;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#zona').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=gen_paises&accion=get_zonas',
            dataType: 'json',
            data: function(term, page){
                return {
                    q: term,
                    page_limit: 10,
                    continente: $('#continente').val()
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
                var url = "#&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data[0]);
                });
            }
        },
        formatResult: function(d){
            return d.zona;
        },
        formatSelection: function(d){
            return d.zona;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
    
    $('#idioma').select2({
        minimumInputText: 1,
        placeholder: "Seleccione...",
        ajax:{
            url:'index.php?mod=sievas&controlador=gen_paises&accion=get_idiomas',
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
                var url = "#&id="+id;
                var jqxhr = $.get(url);
                jqxhr.done(function(data){
                    callback(data[0]);
                });
            }
        },
        formatResult: function(d){
            return d.idioma;
        },
        formatSelection: function(d){
            return d.idioma;
        },
        dropdownCssClass: "bigdrop",
        escapeMarkup: function (m) { return m; }
    });
}

gen_paises.guardar_pais = function(){
    //validar
    var valid = true;
    
    $('#formPais').validate({
        rules:{
            pais : {
                required: true
            },
            nacionalidad : {
                required : true
            },
            continente : {
                required : true,
                min : 1
            },
            idioma : {
                required : true,
                min : 1
            },
            zona:{
                
            }
        }
    });
    
    valid = $('#formPais').valid();
    
    if(valid){
        var jqxhr = $.post('index.php?mod=sievas&controlador=gen_paises&accion=guardar_pais', $('#formPais').serialize());
        
        jqxhr.done(function(data){
            bootbox.alert(data.mensaje, function(){
                window.location = "index.php?mod=sievas&controlador=gen_paises";
            });
            
        });
        
        jqxhr.fail(function(err){
           if(err.responseText != ''){
               var error = JSON.parse(err.responseText);
               bootbox.alert(error.error_msg);
           } 
        });
    }
}

