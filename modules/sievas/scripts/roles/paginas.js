
var paginas = {};

paginas.oTable = $('#data_tabla');

paginas.init = function(){
    paginas.oTable.on('click','.eliminar-generico',function(e){
        var id = $(this).data('id');
        paginas.eliminar(e,id);
    });
    paginas.oTable.on('click','.editar-generico',function(e){
        var data = $(this).data();
        paginas.editar(e,data);
    });
    
    $('#add-generic').on('click', paginas.guardar);
    
    paginas.oTable.dataTable({
            "oLanguage":{"sUrl": "public/js/dataTables.spanish.txt"},
            "bServerSide": true,
            "sAjaxSource": "index.php?mod=sievas&controlador=paginas&accion=listar",
            "bProcessing": true,
            "fnServerParams": function(aoData){
                aoData.push({"name": "sIndexColumn", "value": "nom_pagina"});
                aoData.push({"name": "sTable", "value": "paginas_eval"});
            },
            "aoColumns":[
                {"mData": 0,"sName":"nom_pagina"},
                {"mData": 1,"sName":"titulo"},
                {"mData": 2,"sName":"cod_modulo"},
                {
                    "mData":3,
                    "sName":"nom_pagina",
                    "mRender":function(id){
                        return '<a href="#" class="editar-generico" data-id="'+id+'">'
							 + '<i class="glyphicon glyphicon-edit"></i></a><a href="#" class="eliminar-generico"'
							 + ' data-id="'+id+'"><i class="glyphicon glyphicon-trash"></i></a>';
                    }
                }
            ]    
    });
}

paginas.eliminar = function(e, id){
    bootbox.confirm({
    title: 'Eliminar Página',
    message: '¿Esta seguro que desea eliminarlo?',
    buttons: {
        'cancel': {
            label: 'Cancelar',
            className: 'btn-default pull-right'
        },
        'confirm': {
            label: 'Aceptar',
            className: 'btn-danger pull-right'
        }
    },
    callback: function(result) {
        if (result) {
            var data = id+' paginas_eval nom_pagina';
            var jqxhr = $.post('index.php?mod=sievas&controlador=paginas&accion=eliminar',{
                 id : data
             });

             jqxhr.done(function(){
                paginas.oTable.fnReloadAjax();
             });
                    
        }
    }
});
}

paginas.guardar = function(e){
    e.preventDefault();
    $.get('modules/sievas/plantillas/roles/paginas.html',function(html){        
            $html = $.parseHTML(html);
            $($html).attr('id', 'guardar-form');
            $('#activo', $html).checkbox('SI');                
                var modal = bootbox.dialog({
                message: $html,
                title: "Agregar Página",
                buttons: {
                    success: {
                        label: "Guardar",
                        className: "btn-success",
                        callback: function() {                                    
                                if($('#guardar-form').valid()){
                                    $('#guardar-form').on('submit', function(){
                                        var params = $('#guardar-form').serialize();
                                        var jqxhr = $.post('index.php?mod=sievas&controlador=paginas&accion=guardar', params);

                                        jqxhr.done(function(){
                                            paginas.oTable.fnReloadAjax();
                                        });

                                        jqxhr.fail(function(err){
                                           alert(err.responseText); 
                                        });
                                    });       
                                    $('#guardar-form').submit();
                                }       
                                else{                                 
                                    return false;        
                                }
                        }
                    },
                    danger: {
                        label: "Cancelar",
                        className: "btn-danger",
                        callback: function() {
                            console.log("error");
                        }
                    }
                }
            });

             modal.on('shown.bs.modal', function(){
                var validator = $('#guardar-form').validate({
                    debug : true,
                    rules : {
                         idrol: {
                            required: true,
                            maxlength: 70
                        },
                        desc_rol: {
                            required: true,
                            maxlength: 20
                        }
                    }
                });  
            }); 
    });
}

paginas.editar = function(e,data){
    e.preventDefault();
         $.get('modules/sievas/plantillas/roles/paginas.html',function(html){        
            
            var jqxhr = $.get('index.php?mod=sievas&controlador=paginas&accion=get_fkeys',{
                id : data.id,
                tabla : 'roles'
            });
            
            jqxhr.done(function(data2){
                data2 = JSON.parse(data2);
                $html = $.parseHTML(html);
                
                $($html).attr('id', 'editar-form');
                $('#idrol', $html).val(data2.principal.idrol);
                $('#desc_rol', $html).val(data2.principal.desc_rol);
                $('#activo', $html).checkbox(data2.principal.activo);
                $('#pkey', $html).attr({
                    name : 'pkey',
                    value : data.id
                });
                
                var modal = bootbox.dialog({
                message: $html,
                title: "Editar Rol",
                buttons: {
                    success: {
                        label: "Actualizar",
                        className: "btn-success",
                        callback: function() {
                            if($('#editar-form').valid()){
                                $('#editar-form').on('submit', function(){
                                    var params = $('#editar-form').serialize();
                                    var jqxhr = $.post('index.php?mod=sievas&controlador=paginas&accion=editar', params);

                                    jqxhr.done(function(){
                                        paginas.oTable.fnReloadAjax();
                                    });

                                    jqxhr.fail(function(err){
                                       alert(err.responseText); 
                                    });
                                });
                                $('#editar-form').submit();
                            }
                            else{
                                return false;  
                            }
                        }
                    },
                    danger: {
                        label: "Cancelar",
                        className: "btn-danger",
                        callback: function() {
                            console.log("errror");
                        }
                    }

                }
            });

            modal.on('shown.bs.modal', function(){
                    var validator = $('#editar-form').validate({
                        debug : true,
                        rules : {
                             idrol: {
                                required: true,
                                maxlength: 70
                            },
                            desc_rol: {
                                required: true,
                                maxlength: 20
                            }
                        }
                    });  
                });
            });
    })
}

$(paginas.init);

