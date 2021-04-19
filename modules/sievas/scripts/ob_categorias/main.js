var ob_categorias = {};

ob_categorias.initlistar = function(){
    $('#data_tabla').dataTable({
            "oLanguage":{"sUrl": "public/js/dataTables.spanish.txt"},
            "bServerSide": true,
            "sAjaxSource": "index.php?mod=sievas&controlador=observatorio&accion=get_dt_categorias",
            "bProcessing": true,            
            "fnServerParams": function(aoData){
                var fkeys = [];
                fkeys.push(
                    {nombre : 'observacion_categoria_tipo',fkey : 'cod_tipo_categoria'}                    
                );
                
                aoData.push({"name": "sIndexColumn", "value": "id"});
                aoData.push({"name": "sTable", "value": "observacion_categoria"});
                aoData.push({"name": "fKeys", "value": JSON.stringify(fkeys)});
            },
            "aoColumns":[
                {"mData": 0,"sName": "observacion_categoria.nom_categoria"
                },
                {"mData": 1,"sName": "observacion_categoria_tipo.categoria_tipo"
                },                                     
                {
                    "mData":2,
                    "sName":"id",
                    "mRender":function(id){
                        return '<a href="index.php?mod=sievas&controlador=evaluaciones&accion=editar&id='+id+'" class="editar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-edit"></i></a>\n\
                        <a href="#" class="eliminar-generico" data-id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a>\n\
                        <a href="#" class="finalizar-evaluacion" data-id="'+id+'"><i class="glyphicon glyphicon-flag"></i></a>';
                    },
                    "bSortable" : false                    
                }
            ]   
    });
}

ob_categorias.initaddcategoria = function(){
    $('#tipo_categoria').select2({
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
        var tipo = $(this).val();
         $('#asociado_a').val('');
        switch(parseInt(tipo)){
            case 1:
                $('#asociado_label').html('Rubro');
                $('#asociado_a').select2({
                    minimumInputText: 1,
                    placeholder: "Seleccione...",
                    ajax:{
                        url:'index.php?mod=sievas&controlador=observatorio&accion=get_rubros',
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
                            var url = "index.php?mod=sievas&controlador=observatorio&accion=get_rubro&id="+id;
                            var jqxhr = $.get(url);
                            jqxhr.done(function(data){
                                callback(data);
                            });
                        }
                    },
                    formatResult: function(d){
                        return d.nom_lineamiento;
                    },
                    formatSelection: function(d){
                        return d.nom_lineamiento;
                    },
                    dropdownCssClass: "bigdrop",
                    escapeMarkup: function (m) { return m; }
                });
                break;
            case 2:
                $('#asociado_label').html('Área de conocimiento');               
                $('#asociado_a').select2({
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
                });
                break;
            default:
                $('#asociado_label').html('');
                $('#asociado_a').val('Otros');
                $('#asociado_a').select2('destroy');
                break;
        }
    });
    
    $('#guardar-form').on('click', ob_categorias.add_categoria);
}

ob_categorias.add_categoria = function(e){
    e.preventDefault();
    var nom_categoria = $('#nom_categoria').val();
    var tipo_categoria = $('#tipo_categoria').val();
    var asociado = $('#asociado_a').val();
    var jqxhr = $.post('index.php?mod=sievas&controlador=observatorio&accion=guardar_categoria', {
        nom_categoria : nom_categoria,
        tipo_categoria : tipo_categoria,
        asociado : asociado
    });
    
    jqxhr.done(function(data){
        bootbox.alert("Se ha guardado la categoría exitosamente", function(){
            window.location = 'index.php?mod=sievas&controlador=observatorio&accion=categorias';
        });
    });
    
    jqxhr.fail(function(err){
         bootbox.alert(err.responseText);
    });
}
